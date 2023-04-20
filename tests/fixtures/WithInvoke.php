<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

final class WithInvoke
{
    public function __invoke(int $parameter = 0): never
    {
        exit($parameter);
    }
}
