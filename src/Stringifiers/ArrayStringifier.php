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

use function array_keys;
use function count;
use function implode;
use function is_array;
use function range;
use function sprintf;

final class ArrayStringifier implements Stringifier
{
    private const LIMIT_EXCEEDED_PLACEHOLDER = '...';

    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter,
        private readonly int $maximumDepth,
        private readonly int $maximumNumberOfItems
    ) {
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        if (!is_array($raw)) {
            return null;
        }

        if (empty($raw)) {
            return $this->quoter->quote('[]', $depth);
        }

        if ($depth >= $this->maximumDepth) {
            return $this->quoter->quote(self::LIMIT_EXCEEDED_PLACEHOLDER, $depth);
        }

        $items = [];
        $isSequential = $this->isSequential($raw);
        foreach ($raw as $key => $value) {
            if (count($items) >= $this->maximumNumberOfItems) {
                $items[] = self::LIMIT_EXCEEDED_PLACEHOLDER;
                break;
            }

            $stringifiedValue = $this->stringifyKeyValue($value, $depth + 1);
            if ($isSequential === true) {
                $items[] = $stringifiedValue;
                continue;
            }

            $items[] = sprintf('%s: %s', $this->stringifier->stringify($key, $depth + 1), $stringifiedValue);
        }

        return $this->quoter->quote(sprintf('[%s]', implode(', ', $items)), $depth);
    }

    private function stringifyKeyValue(mixed $value, int $depth): ?string
    {
        if (is_array($value)) {
            return $this->stringify($value, $depth);
        }

        return $this->stringifier->stringify($value, $depth);
    }

    /**
     * @param mixed[] $array
     */
    private function isSequential(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
