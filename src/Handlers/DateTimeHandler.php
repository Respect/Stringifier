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
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;

final class DateTimeHandler implements Handler
{
    use ObjectHelper;

    public function __construct(
        private readonly Quoter $quoter,
        private readonly string $format,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof DateTimeInterface) {
            return null;
        }

        return $this->quoter->quote($this->format($raw, $raw->format($this->format)), $depth);
    }
}
