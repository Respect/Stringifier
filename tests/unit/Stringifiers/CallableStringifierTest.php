<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use Countable;
use DateTime;
use Iterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\CallableStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;

use function array_sum;
use function sprintf;

#[CoversClass(CallableStringifier::class)]
final class CallableStringifierTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotCallable(): void
    {
        $sut = new CallableStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(1, self::DEPTH));
    }

    #[Test]
    #[DataProvider('callableRawValuesProvider')]
    public function itShouldStringifyWhenRawValueIsCallable(callable $raw, string $expectedWithoutQuotes): void
    {
        $quoter = new FakeQuoter();

        $sut = new CallableStringifier(new FakeStringifier(), $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote($expectedWithoutQuotes, self::DEPTH);

        self::assertEquals($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyWhenRawValueIsCallableWithDefaultValues(): void
    {
        $raw = static fn(int $value = 1): int => $value;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new CallableStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('function (int $value = %s): int', $stringifier->stringify(1, self::DEPTH + 1)),
            self::DEPTH,
        );

        self::assertEquals($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyWhenRawValueIsCallableThatDoesNotHaveAnAccessibleDefaultValue(): void
    {
        $raw = 'array_walk';

        $quoter = new FakeQuoter();

        $sut = new CallableStringifier(new FakeStringifier(), $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            'array_walk(object|array &$array, callable $callback, ?mixed $arg = fake.1.cbade92e): true',
            self::DEPTH,
        );

        self::assertEquals($expected, $actual);
    }

    /** @return array<int, array{0: callable, 1: string}> */
    public static function callableRawValuesProvider(): array
    {
        $var1 = 1;
        $var2 = 2;

        return [
            [static fn() => 1, 'function ()'],
            [static fn(): int => 1, 'function (): int'],
            [static fn(float $value): int => (int) $value, 'function (float $value): int'],
            [static fn(float &$value): int => (int) $value, 'function (float &$value): int'],
            // phpcs:ignore SlevomatCodingStandard.TypeHints.DNFTypeHintFormat
            [static fn(?float $value): int => (int) $value, 'function (?float $value): int'],
            [static fn(int $value = self::DEPTH): int => $value, 'function (int $value = self::DEPTH): int'],
            [static fn(int|float $value): int => (int) $value, 'function (int|float $value): int'],
            [static fn(Countable&Iterator $value): int => $value->count(), 'function (Countable&Iterator $value): int'],
            [static fn(int ...$value): int => array_sum($value), 'function (int ...$value): int'],
            [
                static fn(float $value1, float $value2): float => $value1 + $value2,
                'function (float $value1, float $value2): float',
            ],
            [
                static function (int $value) use ($var1): int {
                    return $value + $var1;
                },
                'function (int $value) use ($var1): int',
            ],
            [
                static function (int $value) use ($var1, $var2): int {
                    return $value + $var1 + $var2;
                },
                'function (int $value) use ($var1, $var2): int',
            ],
            [
                new class {
                    public function __invoke(int $parameter): never
                    {
                        exit($parameter);
                    }
                },
                'class->__invoke(int $parameter): never',
            ],
            [
                [new DateTime(), 'format'],
                'DateTime->format(string $format)',
            ],
            [
                ['DateTime', 'createFromImmutable'],
                'DateTime::createFromImmutable(DateTimeImmutable $object)',
            ],
            [
                'DateTimeImmutable::getLastErrors',
                'DateTimeImmutable::getLastErrors()',
            ],
            ['chr', 'chr(int $codepoint): string'],
        ];
    }
}
