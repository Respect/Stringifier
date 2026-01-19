<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Double;

use Respect\Stringifier\Handler;

final class LameHandler implements Handler
{
    public function handle(mixed $raw, int $depth): string|null
    {
        return null;
    }
}
