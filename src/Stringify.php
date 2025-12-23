<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier;

use Respect\Stringifier\Stringifiers\CompositeStringifier;

use function print_r;

final class Stringify
{
    public const int STARTING_DEPTH = 0;

    public function __construct(
        private readonly Stringifier $stringifier,
    ) {
    }

    public static function createDefault(): self
    {
        return new self(CompositeStringifier::createDefault());
    }

    public function value(mixed $value): string
    {
        return $this->stringifier->stringify($value, self::STARTING_DEPTH) ?? print_r($value, true);
    }

    public function __invoke(mixed $value): string
    {
        return $this->value($value);
    }
}
