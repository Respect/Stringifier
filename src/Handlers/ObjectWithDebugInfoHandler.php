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

use function is_object;
use function method_exists;

final class ObjectWithDebugInfoHandler implements Handler
{
    use ObjectHelper;

    public function __construct(
        private readonly Handler $handler,
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!(is_object($raw) && method_exists($raw, '__debugInfo'))) {
            return null;
        }

        return $this->quoter->quote(
            $this->format($raw, '__debugInfo() =>', $this->handler->handle($raw->__debugInfo(), $depth + 1)),
            $depth,
        );
    }
}
