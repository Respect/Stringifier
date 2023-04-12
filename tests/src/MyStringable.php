<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test;

final class MyStringable
{
    public const STRING_VALUE = self::class;

    public function __toString(): string
    {
        return self::STRING_VALUE;
    }
}
