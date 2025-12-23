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
use function sprintf;
use function str_contains;
use function strpos;

final class StandardQuoter implements Quoter
{
    private const string OBJECT_PLACEHOLDER = ' ... }';
    private const string ARRAY_PLACEHOLDER = ' ... ]';
    private const string GENERIC_PLACEHOLDER = ' ...';

    public function __construct(private readonly int $maximumLength)
    {
    }

    public function quote(string $string, int $depth): string
    {
        if ($depth > 0) {
            return $string;
        }

        $limitWithQuotes = $this->maximumLength - 2;
        if (mb_strlen($string) <= $limitWithQuotes) {
            return $this->code($string);
        }

        $filtered = mb_substr($string, 0, $limitWithQuotes);
        if (strpos($filtered, '[') === 0) {
            return $this->placeholder($filtered, self::ARRAY_PLACEHOLDER);
        }

        if (str_contains($filtered, '{')) {
            return $this->placeholder($filtered, self::OBJECT_PLACEHOLDER);
        }

        return $this->placeholder($filtered, self::GENERIC_PLACEHOLDER);
    }

    private function code(string $string): string
    {
        return sprintf('`%s`', $string);
    }

    private function placeholder(string $string, string $placeholder): string
    {
        return $this->code(mb_substr($string, 0, -1 * mb_strlen($placeholder)) . $placeholder);
    }
}
