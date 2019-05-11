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

namespace Respect\Stringifier\Quoters;

use Respect\Stringifier\Quoter;
use function sprintf;

/**
 * Add "`" quotes around a string depending on its level.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class CodeQuoter implements Quoter
{
    /**
     * {@inheritdoc}
     */
    public function quote(string $string, int $depth): string
    {
        if ($depth === 0) {
            return sprintf('`%s`', $string);
        }

        return $string;
    }
}
