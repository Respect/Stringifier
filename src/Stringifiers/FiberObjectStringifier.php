<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Fiber;
use ReflectionFiber;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

use function sprintf;

final class FiberObjectStringifier implements Stringifier
{
    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter,
    ) {
    }

    public function stringify(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof Fiber) {
            return null;
        }

        return $this->quoter->quote(
            sprintf(
                'Fiber { %s }',
                $this->stringifier->stringify((new ReflectionFiber($raw))->getCallable(), $depth + 1),
            ),
            $depth,
        );
    }
}
