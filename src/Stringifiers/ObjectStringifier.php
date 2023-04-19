<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use ReflectionObject;
use ReflectionProperty;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

use function count;
use function implode;
use function is_object;
use function sprintf;
use function trim;

final class ObjectStringifier implements Stringifier
{
    private const LIMIT_EXCEEDED_PLACEHOLDER = '...';

    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter,
        private readonly int $maximumDepth,
        private readonly int $maximumNumberOfProperties
    ) {
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        if (!is_object($raw)) {
            return null;
        }

        if ($depth >= $this->maximumDepth) {
            return $this->quoter->quote(sprintf('%s { %s }', $raw::class, self::LIMIT_EXCEEDED_PLACEHOLDER), $depth);
        }

        $properties = $this->getProperties(new ReflectionObject($raw), $raw, $depth + 1);
        if (count($properties) === 0) {
            return $this->quoter->quote(sprintf('%s {}', $raw::class), $depth);
        }

        return $this->quoter->quote(sprintf('%s { %s }', $raw::class, implode(' ', $properties)), $depth);
    }

    /**
     * @return array<int, string>
     */
    private function getProperties(ReflectionObject $reflectionObject, object $object, int $depth): array
    {
        $reflectionProperties = $reflectionObject->getProperties();
        if (count($reflectionProperties) === 0) {
            return [];
        }

        $properties = [];
        foreach ($reflectionProperties as $reflectionProperty) {
            if (count($properties) >= $this->maximumNumberOfProperties) {
                $properties[] = self::LIMIT_EXCEEDED_PLACEHOLDER;
                break;
            }

            $properties[] = trim(sprintf(
                '%s$%s=%s',
                match (true) {
                    $reflectionProperty->isPrivate() => '-',
                    $reflectionProperty->isProtected() => '#',
                    default => '+',
                },
                $reflectionProperty->getName(),
                $this->getPropertyValue($reflectionProperty, $object, $depth)
            ));
        }

        return $properties;
    }

    private function getPropertyValue(ReflectionProperty $reflectionProperty, object $object, int $depth): ?string
    {
        if (!$reflectionProperty->isInitialized($object)) {
            return '*uninitialized*';
        }

        $value = $reflectionProperty->getValue($object);
        if (is_object($value)) {
            return $this->stringify($value, $depth);
        }

        return $this->stringifier->stringify($value, $depth);
    }
}
