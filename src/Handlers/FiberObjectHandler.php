<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Handlers;

use Fiber;
use ReflectionFiber;
use Respect\Stringifier\Handler;
use Respect\Stringifier\Quoter;

use function sprintf;

final class FiberObjectHandler implements Handler
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof Fiber) {
            return null;
        }

        return $this->quoter->quote(
            sprintf(
                'Fiber { %s }',
                $this->handler->handle((new ReflectionFiber($raw))->getCallable(), $depth + 1),
            ),
            $depth,
        );
    }
}
