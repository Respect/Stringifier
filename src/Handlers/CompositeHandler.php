<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Handlers;

use DateTimeInterface;
use Respect\Stringifier\Handler;
use Respect\Stringifier\Quoters\CodeQuoter;

use function array_unshift;

final class CompositeHandler implements Handler
{
    private const int MAXIMUM_DEPTH = 3;
    private const int MAXIMUM_NUMBER_OF_ITEMS = 5;
    private const int MAXIMUM_NUMBER_OF_PROPERTIES = self::MAXIMUM_NUMBER_OF_ITEMS;
    private const int MAXIMUM_LENGTH = 120;

    /** @var array<Handler> */
    private array $handlers = [];

    public function __construct(Handler ...$handlers)
    {
        $this->handlers = $handlers;
    }

    public static function create(): self
    {
        $quoter = new CodeQuoter(self::MAXIMUM_LENGTH);

        $handler = new self(
            new InfiniteNumberHandler($quoter),
            new NotANumberHandler($quoter),
            new ResourceHandler($quoter),
            new BoolHandler($quoter),
            new NullHandler($quoter),
            $jsonEncodableHandler = new JsonEncodableHandler(),
        );
        $handler->prependHandler(
            $arrayHandler = new ArrayHandler(
                $handler,
                $quoter,
                self::MAXIMUM_DEPTH,
                self::MAXIMUM_NUMBER_OF_ITEMS,
            ),
        );
        $handler->prependHandler(
            new ObjectHandler(
                $handler,
                $quoter,
                self::MAXIMUM_DEPTH,
                self::MAXIMUM_NUMBER_OF_PROPERTIES,
            ),
        );
        $handler->prependHandler(new CallableHandler($handler, $quoter));
        $handler->prependHandler(
            new FiberObjectHandler(new CallableHandler($handler, $quoter, closureOnly: false), $quoter),
        );
        $handler->prependHandler(new EnumerationHandler($quoter));
        $handler->prependHandler(new ObjectWithDebugInfoHandler($arrayHandler, $quoter));
        $handler->prependHandler(new ArrayObjectHandler($arrayHandler, $quoter));
        $handler->prependHandler(new JsonSerializableObjectHandler($jsonEncodableHandler, $quoter));
        $handler->prependHandler(new StringableObjectHandler($jsonEncodableHandler, $quoter));
        $handler->prependHandler(new ThrowableObjectHandler($jsonEncodableHandler, $quoter));
        $handler->prependHandler(new DateTimeHandler($quoter, DateTimeInterface::ATOM));
        $handler->prependHandler(new IteratorObjectHandler($handler, $quoter));

        return $handler;
    }

    public function prependHandler(Handler $handler): void
    {
        array_unshift($this->handlers, $handler);
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        foreach ($this->handlers as $handler) {
            $string = $handler->handle($raw, $depth);
            if ($string === null) {
                continue;
            }

            return $string;
        }

        return null;
    }
}
