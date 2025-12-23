<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Helpers;

use function array_filter;
use function count;
use function implode;
use function sprintf;
use function strstr;

trait ObjectHelper
{
    private function format(object $object, string|null ...$pieces): string
    {
        $filteredPieces = array_filter($pieces);
        if (count($filteredPieces) === 0) {
            return $this->getName($object) . ' {}';
        }

        return sprintf('%s { %s }', $this->getName($object), implode(' ', $filteredPieces));
    }

    private function getName(object $object): string
    {
        $name = strstr($object::class, "\000", true);
        if ($name === 'class@anonymous') {
            return 'class';
        }

        if ($name !== false) {
            return $name;
        }

        return $object::class;
    }
}
