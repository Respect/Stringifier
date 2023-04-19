<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

class WithProperties
{
    public bool $publicProperty = true;

    protected int $protectedProperty = 42;

    private string $privateProperty = 'something'; // @phpstan-ignore-line
}
