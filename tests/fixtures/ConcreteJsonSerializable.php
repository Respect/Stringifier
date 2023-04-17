<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

final class ConcreteJsonSerializable implements JsonSerializable
{
    public const JSON_VALUE = [1, 2, 3, 'foo' => true];

    /**
     * @return array<mixed, mixed>
     */
    public function jsonSerialize(): array
    {
        return self::JSON_VALUE;
    }
}
