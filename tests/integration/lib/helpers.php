<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

use function Respect\Stringifier\stringify;

function output(mixed $value): void
{
    echo stringify($value) . PHP_EOL;
}

function outputMultiple(mixed ...$values): void
{
    foreach ($values as $value) {
        output($value);
    }
}
