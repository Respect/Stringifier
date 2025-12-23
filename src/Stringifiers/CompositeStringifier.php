<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use DateTimeInterface;
use Respect\Stringifier\Quoters\StandardQuoter;
use Respect\Stringifier\Stringifier;

use function array_unshift;

final class CompositeStringifier implements Stringifier
{
    private const int MAXIMUM_DEPTH = 3;
    private const int MAXIMUM_NUMBER_OF_ITEMS = 5;
    private const int MAXIMUM_NUMBER_OF_PROPERTIES = self::MAXIMUM_NUMBER_OF_ITEMS;
    private const int MAXIMUM_LENGTH = 120;

    /** @var Stringifier[] */
    private array $stringifiers = [];

    public function __construct(Stringifier ...$stringifiers)
    {
        $this->stringifiers = $stringifiers;
    }

    public static function createDefault(): self
    {
        $quoter = new StandardQuoter(self::MAXIMUM_LENGTH);

        $stringifier = new self(
            new InfiniteNumberStringifier($quoter),
            new NotANumberStringifier($quoter),
            new ResourceStringifier($quoter),
            new BoolStringifier($quoter),
            new NullStringifier($quoter),
            new DeclaredStringifier($quoter),
            $jsonEncodableStringifier = new JsonEncodableStringifier(),
        );
        $stringifier->prependStringifier(
            $arrayStringifier = new ArrayStringifier(
                $stringifier,
                $quoter,
                self::MAXIMUM_DEPTH,
                self::MAXIMUM_NUMBER_OF_ITEMS,
            ),
        );
        $stringifier->prependStringifier(
            new ObjectStringifier(
                $stringifier,
                $quoter,
                self::MAXIMUM_DEPTH,
                self::MAXIMUM_NUMBER_OF_PROPERTIES,
            ),
        );
        $stringifier->prependStringifier($callableStringifier = new CallableStringifier($stringifier, $quoter));
        $stringifier->prependStringifier(new FiberObjectStringifier($callableStringifier, $quoter));
        $stringifier->prependStringifier(new EnumerationStringifier($quoter));
        $stringifier->prependStringifier(new ObjectWithDebugInfoStringifier($arrayStringifier, $quoter));
        $stringifier->prependStringifier(new ArrayObjectStringifier($arrayStringifier, $quoter));
        $stringifier->prependStringifier(new JsonSerializableObjectStringifier($jsonEncodableStringifier, $quoter));
        $stringifier->prependStringifier(new StringableObjectStringifier($jsonEncodableStringifier, $quoter));
        $stringifier->prependStringifier(new ThrowableObjectStringifier($jsonEncodableStringifier, $quoter));
        $stringifier->prependStringifier(new DateTimeStringifier($quoter, DateTimeInterface::ATOM));
        $stringifier->prependStringifier(new IteratorObjectStringifier($stringifier, $quoter));

        return $stringifier;
    }

    public function prependStringifier(Stringifier $stringifier): void
    {
         array_unshift($this->stringifiers, $stringifier);
    }

    public function stringify(mixed $raw, int $depth): string|null
    {
        foreach ($this->stringifiers as $stringifier) {
            $string = $stringifier->stringify($raw, $depth);
            if ($string === null) {
                continue;
            }

            return $string;
        }

        return null;
    }
}
