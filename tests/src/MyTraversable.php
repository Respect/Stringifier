<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int>
 */
final class MyTraversable implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        return new ArrayIterator([1, 2, 3]);
    }
}
