<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

final class WithDebugInfo
{
    /** @return array<string, string> */
    public function __debugInfo(): array
    {
        return ['info' => 'This is the return of __debugInfo()'];
    }
}
