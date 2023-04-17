<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Iterator;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

use function sprintf;

final class IteratorObjectStringifier implements Stringifier
{
    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter
    ) {
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        if (!$raw instanceof Iterator) {
            return null;
        }

        return $this->quoter->quote(
            sprintf(
                '%s { current() => %s }',
                $raw::class,
                $this->stringifier->stringify($raw->current(), $depth + 1)
            ),
            $depth
        );
    }
}