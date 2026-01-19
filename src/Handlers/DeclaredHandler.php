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

use function class_exists;
use function enum_exists;
use function interface_exists;
use function is_string;
use function trait_exists;

final class DeclaredHandler implements Handler
{
    public function __construct(
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!is_string($raw) || $this->isNotDeclared($raw)) {
            return null;
        }

        return $this->quoter->quote($raw, $depth);
    }

    public function isNotDeclared(string $raw): bool
    {
        return !class_exists($raw) && !interface_exists($raw) && !trait_exists($raw) && !enum_exists($raw);
    }
}
