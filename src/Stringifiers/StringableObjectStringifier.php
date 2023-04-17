<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Stringable;

use function sprintf;

final class StringableObjectStringifier implements Stringifier
{
    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter
    ) {
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        if (!$raw instanceof Stringable) {
            return null;
        }

        return $this->quoter->quote(
            sprintf(
                '%s { __toString() => %s }',
                $raw::class,
                $this->stringifier->stringify($raw->__toString(), $depth + 1)
            ),
            $depth
        );
    }
}
