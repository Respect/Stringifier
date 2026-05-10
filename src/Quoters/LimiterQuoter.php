<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Quoters;

use Respect\Stringifier\Quoter;

use function mb_strlen;
use function mb_substr;
use function str_contains;
use function strpos;

final class LimiterQuoter implements Quoter
{
    private const string OBJECT_PLACEHOLDER = ' ... }';
    private const string ARRAY_PLACEHOLDER = ' ... ]';
    private const string GENERIC_PLACEHOLDER = ' ...';

    public function __construct(private readonly int $maximumLength)
    {
    }

    public function quote(string $string, int $depth): string
    {
        if (mb_strlen($string) <= $this->maximumLength) {
            return $string;
        }

        $filtered = mb_substr($string, 0, $this->maximumLength);
        if (strpos($filtered, '[') === 0) {
            return $this->truncate($filtered, self::ARRAY_PLACEHOLDER);
        }

        if (str_contains($filtered, '{')) {
            return $this->truncate($filtered, self::OBJECT_PLACEHOLDER);
        }

        return $this->truncate($filtered, self::GENERIC_PLACEHOLDER);
    }

    private function truncate(string $string, string $placeholder): string
    {
        return mb_substr($string, 0, -1 * mb_strlen($placeholder)) . $placeholder;
    }
}
