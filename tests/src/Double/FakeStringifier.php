<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Double;

use Respect\Stringifier\Stringifier;

use function hash;
use function implode;
use function serialize;

final class FakeStringifier implements Stringifier
{
    public function stringify(mixed $raw, int $depth): string
    {
        return implode('.', ['fake', $depth, hash('crc32', serialize($raw))]);
    }
}
