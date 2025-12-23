<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use UnitEnum;

use function sprintf;

final class EnumerationStringifier implements Stringifier
{
    public function __construct(
        private readonly Quoter $quoter,
    ) {
    }

    public function stringify(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof UnitEnum) {
            return null;
        }

        return $this->quoter->quote(sprintf('%s::%s', $raw::class, $raw->name), $depth);
    }
}
