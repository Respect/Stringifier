<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use DateTimeInterface;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

final class DateTimeStringifier implements Stringifier
{
    use ObjectHelper;

    public function __construct(
        private readonly Quoter $quoter,
        private readonly string $format,
    ) {
    }

    public function stringify(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof DateTimeInterface) {
            return null;
        }

        return $this->quoter->quote($this->format($raw, $raw->format($this->format)), $depth);
    }
}
