<?php

/*
 * This file is part of Respect/Stringifier.
 *
 * (c) Henrique Moody <henriquemoody@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
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
