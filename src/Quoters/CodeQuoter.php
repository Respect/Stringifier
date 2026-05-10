<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Quoters;

use Respect\Stringifier\Quoter;

use function sprintf;

final class CodeQuoter implements Quoter
{
    private readonly LimiterQuoter $limiter;

    public function __construct(int $maximumLength)
    {
        $this->limiter = new LimiterQuoter($maximumLength - 2);
    }

    public function quote(string $string, int $depth): string
    {
        if ($depth > 0) {
            return $string;
        }

        return sprintf('`%s`', $this->limiter->quote($string, $depth));
    }
}
