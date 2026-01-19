<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Handlers;

use Iterator;
use Respect\Stringifier\Handler;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;

final class IteratorObjectHandler implements Handler
{
    use ObjectHelper;

    public function __construct(
        private readonly Handler $handler,
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof Iterator) {
            return null;
        }

        if (!$raw->valid()) {
            return null;
        }

        return $this->quoter->quote(
            $this->format($raw, 'current() =>', $this->handler->handle($raw->current(), $depth + 1)),
            $depth,
        );
    }
}
