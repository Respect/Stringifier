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

use DateTimeInterface;
use Respect\Stringifier\Stringifier;

/**
 * Converts an instance of DateTimeInterface into a string.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class DateTimeStringifier implements Stringifier
{
    /**
     * @var Stringifier
     */
    private $stringifier;

    /**
     * @var string
     */
    private $format;

    /**
     * Initializes the stringifier.
     *
     * @param Stringifier $stringifier
     * @param string $format
     */
    public function __construct(Stringifier $stringifier, string $format)
    {
        $this->stringifier = $stringifier;
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function stringify($raw, int $depth): ?string
    {
        if (!$raw instanceof DateTimeInterface) {
            return null;
        }

        return $this->stringifier->stringify($raw->format($this->format), $depth);
    }
}
