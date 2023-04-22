<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use Closure;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;

use function array_keys;
use function array_map;
use function count;
use function implode;
use function is_array;
use function is_callable;
use function is_object;
use function is_string;
use function sprintf;
use function str_contains;
use function strrchr;
use function strstr;
use function substr;

final class CallableStringifier implements Stringifier
{
    use ObjectHelper;

    public function __construct(
        private readonly Stringifier $stringifier,
        private readonly Quoter $quoter,
    ) {
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        if (!is_callable($raw)) {
            return null;
        }

        if ($raw instanceof Closure) {
            return $this->buildFunction(new ReflectionFunction($raw), $depth);
        }

        if (is_object($raw)) {
            return $this->buildMethod(new ReflectionMethod($raw, '__invoke'), $raw, $depth);
        }

        if (is_array($raw) && is_object($raw[0])) {
            return $this->buildMethod(new ReflectionMethod($raw[0], $raw[1]), $raw[0], $depth);
        }

        if (is_array($raw) && is_string($raw[0])) {
            return $this->buildStaticMethod(new ReflectionMethod($raw[0], $raw[1]), $depth);
        }

        /** @var callable-string $raw */
        if (str_contains($raw, ':')) {
            /** @var class-string $class */
            $class = (string) strstr($raw, ':', true);
            $method = substr((string) strrchr($raw, ':'), 1);

            return $this->buildStaticMethod(new ReflectionMethod($class, $method), $depth);
        }

        return $this->buildFunction(new ReflectionFunction($raw), $depth);
    }

    public function buildFunction(ReflectionFunction $raw, int $depth): ?string
    {
        return $this->quoter->quote($this->buildSignature($raw, $depth), $depth);
    }

    private function buildMethod(ReflectionMethod $reflection, object $object, int $depth): string
    {
        return $this->quoter->quote(
            sprintf('%s->%s', $this->getName($object), $this->buildSignature($reflection, $depth)),
            $depth
        );
    }

    private function buildStaticMethod(ReflectionMethod $reflection, int $depth): string
    {
        return $this->quoter->quote(
            sprintf('%s::%s', $reflection->class, $this->buildSignature($reflection, $depth)),
            $depth
        );
    }

    private function buildSignature(ReflectionFunctionAbstract $function, int $depth): string
    {
        $signature = $function->isClosure() ? 'function ' : $function->getName();
        $signature .= sprintf(
            '(%s)',
            implode(
                ', ',
                array_map(
                    fn(ReflectionParameter $parameter): string => $this->buildParameter(
                        $parameter,
                        $depth + 1
                    ),
                    $function->getParameters()
                )
            ),
        );

        $closureUsedVariables = $function->getClosureUsedVariables();
        if (count($closureUsedVariables)) {
            $signature .= sprintf(
                ' use ($%s)',
                implode(
                    ', $',
                    array_keys($closureUsedVariables)
                ),
            );
        }

        $returnType = $function->getReturnType();
        if ($returnType !== null) {
            $signature .= ': ' . $this->buildType($returnType, $depth);
        }

        return $signature;
    }

    private function buildParameter(ReflectionParameter $reflectionParameter, int $depth): string
    {
        $parameter = '';

        $type = $reflectionParameter->getType();
        if ($type !== null) {
            $parameter .= $this->buildType($type, $depth);
        }

        if ($reflectionParameter->isVariadic()) {
            return $parameter . ' ...$' . $reflectionParameter->getName();
        }

        $parameter .= $reflectionParameter->isPassedByReference() ? ' &' : ' ';
        $parameter .= '$' . $reflectionParameter->getName();
        if ($reflectionParameter->isOptional()) {
            $parameter  .= ' = ' . $this->buildValue($reflectionParameter, $depth);
        }

        return $parameter;
    }

    private function buildValue(ReflectionParameter $reflectionParameter, int $depth): ?string
    {
        if (!$reflectionParameter->isDefaultValueAvailable()) {
            return $this->stringifier->stringify(null, $depth);
        }

        if ($reflectionParameter->isDefaultValueConstant()) {
            return $reflectionParameter->getDefaultValueConstantName();
        }

        return $this->stringifier->stringify($reflectionParameter->getDefaultValue(), $depth);
    }

    private function buildType(ReflectionType $raw, int $depth): string
    {
        if ($raw instanceof ReflectionUnionType) {
            return implode(
                '|',
                array_map(fn(ReflectionType $type) => $this->buildType($type, $depth), $raw->getTypes())
            );
        }

        if ($raw instanceof ReflectionIntersectionType) {
            return implode(
                '&',
                array_map(fn(ReflectionType $type) => $this->buildType($type, $depth), $raw->getTypes())
            );
        }

        /** @var ReflectionNamedType $raw */

        return ($raw->allowsNull() ? '?' : '') . $raw->getName();
    }
}
