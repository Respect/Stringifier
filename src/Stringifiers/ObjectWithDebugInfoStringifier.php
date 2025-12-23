<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

use function is_object;
use function method_exists;

final class ObjectWithDebugInfoStringifier implements Stringifier
{
    use ObjectHelper;

    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter,
    ) {
    }

    public function stringify(mixed $raw, int $depth): string|null
    {
        if (!(is_object($raw) && method_exists($raw, '__debugInfo'))) {
            return null;
        }

        return $this->quoter->quote(
            $this->format($raw, '__debugInfo() =>', $this->stringifier->stringify($raw->__debugInfo(), $depth + 1)),
            $depth,
        );
    }
}
