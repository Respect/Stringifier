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

use ArrayIterator;
use IteratorAggregate;
use Traversable;

final class MyTraversable implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        return new ArrayIterator([1, 2, 3]);
    }
}
