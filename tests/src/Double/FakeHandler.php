<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Double;

use Respect\Stringifier\Handler;

use function hash;
use function implode;
use function serialize;

final class FakeHandler implements Handler
{
    public function handle(mixed $raw, int $depth = 0): string
    {
        return implode('.', ['fake', $depth, hash('crc32', serialize($raw))]);
    }
}
