<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Handlers;

use Respect\Stringifier\Handler;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;
use Stringable;

final class StringableObjectHandler implements Handler
{
    use ObjectHelper;

    public function __construct(
        private readonly Handler $handler,
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!$raw instanceof Stringable) {
            return null;
        }

        return $this->quoter->quote(
            $this->format($raw, '__toString() =>', $this->handler->handle($raw->__toString(), $depth + 1)),
            $depth,
        );
    }
}
