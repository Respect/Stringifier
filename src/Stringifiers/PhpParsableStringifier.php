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

use function var_export;
use Respect\Stringifier\Stringifier;

/**
 * Converts any value into PHP parsable string representation.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class PhpParsableStringifier implements Stringifier
{
    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        $string = var_export($raw, true);
        if (null !== $raw && 'NULL' === $string) {
            return null;
        }

        return $string;
    }
}
