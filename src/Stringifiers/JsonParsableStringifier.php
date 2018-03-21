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

use Respect\Stringifier\Stringifier;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use function json_encode;

/**
 * Converts any value into JSON parsable string representation.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class JsonParsableStringifier implements Stringifier
{
    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        $string = json_encode($raw, (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION));
        if (false === $string) {
            return null;
        }

        return $string;
    }
}
