<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Throwable;

use function getcwd;
use function str_replace;

final class ThrowableObjectStringifier implements Stringifier
{
    use ObjectHelper;

    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter
    ) {
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        if (!$raw instanceof Throwable) {
            return null;
        }

        if ($raw->getMessage() === '') {
            return $this->quoter->quote($this->format($raw, 'in', $this->getSource($raw)), $depth);
        }

        return $this->quoter->quote(
            $this->format(
                $raw,
                $this->stringifier->stringify($raw->getMessage(), $depth + 1),
                'in',
                $this->getSource($raw)
            ),
            $depth
        );
    }

    private function getSource(Throwable $throwable): string
    {
        return str_replace(getcwd() . '/', '', $throwable->getFile()) . ':' . $throwable->getLine();
    }
}
