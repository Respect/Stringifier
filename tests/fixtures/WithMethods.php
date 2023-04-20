<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

final class WithMethods
{
    public function publicMethod(Iterator&Countable $parameter): ?static
    {
        return new static();
    }

    public static function publicStaticMethod(int|float $parameter): void
    {
    }
}
