<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier;

use function print_r;

final class DumpStringifier implements Stringifier
{
    public function stringify(mixed $raw): string
    {
        return print_r($raw, true);
    }
}
