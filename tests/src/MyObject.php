<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test;

final class MyObject
{
    public bool $foo = true;

    private float $bar = .3; // @phpstan-ignore-line

    /**
     * @var int[]
     */
    private array $baz = [1, 2, 3]; // @phpstan-ignore-line
}
