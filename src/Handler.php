<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier;

interface Handler
{
    /**
     * Attempts to stringify the given value.
     *
     * @return string|null The stringified value, or null if this handler cannot handle the type
     */
    public function handle(mixed $raw, int $depth): string|null;
}
