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

namespace Respect\Stringifier;

use Respect\Stringifier\Stringifiers\ClusterStringifier;

/**
 * @param mixed $value
 */
function stringify($value): string
{
    static $stringifier;

    if ($stringifier === null) {
        $stringifier = ClusterStringifier::createDefault();
    }

    return $stringifier->stringify($value, 0) ?? '#ERROR#';
}
