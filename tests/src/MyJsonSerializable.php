<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test;

use JsonSerializable;

final class MyJsonSerializable implements JsonSerializable
{
    public const JSON_VALUE = [1, 2, 3];

    /**
     * @return int[]
     */
    public function jsonSerialize(): array
    {
        return self::JSON_VALUE;
    }
}
