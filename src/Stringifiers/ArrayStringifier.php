<?php

/*
 * This file is part of Respect/Stringifier.
 *
 * (c) Henrique Moody <henriquemoody@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use function array_keys;
use function implode;
use function is_array;
use function is_int;
use function sprintf;
use Respect\Stringifier\Stringifier;

/**
 * Converts an array value into a string.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ArrayStringifier implements Stringifier
{
    /**
     * @var Stringifier
     */
    private $stringifier;

    /**
     * @var int
     */
    private $maximumDepth;

    /**
     * @var int
     */
    private $itemsLimit;

    /**
     * Initializes the stringifier.
     *
     * @param Stringifier $stringifier
     * @param int $maximumDepth
     * @param int $itemsLimit
     */
    public function __construct(Stringifier $stringifier, int $maximumDepth, int $itemsLimit)
    {
        $this->stringifier = $stringifier;
        $this->maximumDepth = $maximumDepth;
        $this->itemsLimit = $itemsLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        if (!is_array($raw)) {
            return null;
        }

        if (empty($raw)) {
            return '{ }';
        }

        if ($depth >= $this->maximumDepth) {
            return '...';
        }

        $items = [];
        $itemsCount = 0;
        $isSequential = $this->isSequential($raw);
        foreach ($raw as $key => $value) {
            if (++$itemsCount > $this->itemsLimit) {
                $items[$itemsCount] = ' ... ';
                break;
            }

            $items[$itemsCount] = '';
            if (false === $isSequential) {
                $items[$itemsCount] .= sprintf('%s: ', $this->stringifier->stringify($key, $depth + 1));
            }
            $items[$itemsCount] .= $this->stringifier->stringify($value, $depth + 1);
        }

        return sprintf('{ %s }', implode(', ', $items));
    }

    private function isSequential(array $raw): bool
    {
        return array_keys($raw) === range(0, count($raw) - 1);
    }
}
