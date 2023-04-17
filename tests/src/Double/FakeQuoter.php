<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Double;

use Respect\Stringifier\Quoter;

use function sprintf;

final class FakeQuoter implements Quoter
{
    private const SYMBOL = '#';

    public function quote(string $string, int $depth): string
    {
        return sprintf('%1$s%2$s%1$s%3$d', self::SYMBOL, $string, $depth);
    }
}
