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

use function is_float;
use function is_infinite;

final class InfiniteNumberHandler implements Handler
{
    public function __construct(
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!is_float($raw)) {
            return null;
        }

        if (!is_infinite($raw)) {
            return null;
        }

        return $this->quoter->quote(($raw > 0 ? '' : '-') . 'INF', $depth);
    }
}
