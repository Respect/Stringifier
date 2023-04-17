<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

/**
 * @implements Iterator<int>
 */
final class ConcreteIterator implements Iterator
{
    private const VALUES = [1, 2, 3];

    private int $position = 0;

    public function current(): int
    {
        return self::VALUES[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return array_key_exists($this->position, self::VALUES);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
