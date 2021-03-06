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

final class MyObject
{
    /**
     * @var bool
     */
    public $foo = true;

    /**
     * @var float
     */
    private $bar = .3;

    /**
     * @var int[]
     */
    private $baz = [1, 2, 3];
}
