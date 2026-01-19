<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier;

use Respect\Stringifier\Handlers\CompositeHandler;

final class HandlerStringifier implements Stringifier
{
    public function __construct(
        private readonly Handler $handler,
        private readonly Stringifier $fallback,
    ) {
    }

    public static function create(): self
    {
        return new self(CompositeHandler::create(), new DumpStringifier());
    }

    public function stringify(mixed $raw): string
    {
        return $this->handler->handle($raw, 0) ?? $this->fallback->stringify($raw);
    }
}
