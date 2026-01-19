<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier;

use Respect\Stringifier\Handlers\CompositeHandler;

function stringify(mixed $value): string
{
    static $stringifier;

    if (!$stringifier instanceof Stringifier) {
        $stringifier = new HandlerStringifier(CompositeHandler::create(), new DumpStringifier());
    }

    return $stringifier->stringify($value);
}
