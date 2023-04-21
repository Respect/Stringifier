<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier;

function stringify(mixed $value): string
{
    static $stringify;

    if ($stringify === null) {
        $stringify = Stringify::createDefault();
    }

    return $stringify->value($value);
}
