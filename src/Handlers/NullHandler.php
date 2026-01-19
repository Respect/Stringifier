<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Handlers;

use Respect\Stringifier\Handler;
use Respect\Stringifier\Quoter;

final class NullHandler implements Handler
{
    public function __construct(
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if ($raw !== null) {
            return null;
        }

        return $this->quoter->quote('null', $depth);
    }
}
