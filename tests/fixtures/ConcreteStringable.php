<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

final class ConcreteStringable
{
    public function __toString(): string
    {
        return 'This is the return of __toString()';
    }
}
