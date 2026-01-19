<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use Countable;
use DateTime;
use Iterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\CallableHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function array_sum;
use function sprintf;

#[CoversClass(CallableHandler::class)]
final class CallableHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotCallable(): void
    {
        $sut = new CallableHandler(new FakeHandler(), new FakeQuoter(), closureOnly: false);

        self::assertNull($sut->handle(1, self::DEPTH));
    }

    #[Test]
    #[DataProvider('closureRawValuesProvider')]
    public function itShouldStringifyWhenRawValueIsClosure(callable $raw, string $expectedWithoutQuotes): void
    {
        $quoter = new FakeQuoter();

        $sut = new CallableHandler(new FakeHandler(), $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote($expectedWithoutQuotes, self::DEPTH);

        self::assertEquals($expected, $actual);
    }

    #[Test]
    #[DataProvider('nonClosureCallableRawValuesProvider')]
    public function itShouldNotStringifyNonClosureCallableByDefault(callable $raw, string $useless): void
    {
        $sut = new CallableHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle($raw, self::DEPTH));
    }

    #[Test]
    #[DataProvider('nonClosureCallableRawValuesProvider')]
    public function itShouldStringifyNonClosureCallableWhenClosureOnlyIsFalse(
        callable $raw,
        string $expectedWithoutQuotes,
    ): void {
        $quoter = new FakeQuoter();

        $sut = new CallableHandler(new FakeHandler(), $quoter, closureOnly: false);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote($expectedWithoutQuotes, self::DEPTH);

        self::assertEquals($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyWhenRawValueIsCallableWithDefaultValues(): void
    {
        $raw = static fn(int $value = 1): int => $value;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new CallableHandler($stringifier, $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('Closure { static fn(int $value = %s): int }', $stringifier->handle(1, self::DEPTH + 1)),
            self::DEPTH,
        );

        self::assertEquals($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyWhenRawValueIsCallableThatDoesNotHaveAnAccessibleDefaultValue(): void
    {
        $raw = 'array_walk';

        $quoter = new FakeQuoter();

        $sut = new CallableHandler(new FakeHandler(), $quoter, closureOnly: false);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            'array_walk(object|array &$array, callable $callback, ?mixed $arg = fake.1.cbade92e): true',
            self::DEPTH,
        );

        self::assertEquals($expected, $actual);
    }

    /** @return array<string, array{0: callable, 1: string}> */
    public static function closureRawValuesProvider(): array
    {
        $var1 = 1;
        $var2 = 2;

        return [
            'static closure without parameters' => [
                static fn() => 1,
                'Closure { static fn() }',
            ],
            'non-static closure without parameters' => [
                fn() => 1,
                'Closure { fn() }',
            ],
            'static closure with return type' => [
                static fn(): int => 1,
                'Closure { static fn(): int }',
            ],
            'non-static closure with return type' => [
                fn(): int => 1,
                'Closure { fn(): int }',
            ],
            'static closure with typed parameter' => [
                static fn(float $value): int => (int) $value,
                'Closure { static fn(float $value): int }',
            ],
            'static closure with reference parameter' => [
                static fn(float &$value): int => (int) $value,
                'Closure { static fn(float &$value): int }',
            ],
            'static closure with nullable parameter' => [
                static fn(float|null $value): int => (int) $value,
                'Closure { static fn(?float $value): int }',
            ],
            'static closure with constant default value' => [
                static fn(int $value = self::DEPTH): int => $value,
                'Closure { static fn(int $value = self::DEPTH): int }',
            ],
            'static closure with union type parameter' => [
                static fn(int|float $value): int => (int) $value,
                'Closure { static fn(int|float $value): int }',
            ],
            'static closure with intersection type parameter' => [
                static fn(Countable&Iterator $value): int => $value->count(),
                'Closure { static fn(Countable&Iterator $value): int }',
            ],
            'static closure with variadic parameter' => [
                static fn(int ...$value): int => array_sum($value),
                'Closure { static fn(int ...$value): int }',
            ],
            'static closure with multiple parameters' => [
                static fn(float $value1, float $value2): float => $value1 + $value2,
                'Closure { static fn(float $value1, float $value2): float }',
            ],
            'static closure with single use variable' => [
                static function (int $value) use ($var1): int {
                    return $value + $var1;
                },
                'Closure { static fn(int $value) use ($var1): int }',
            ],
            'static closure with multiple use variables' => [
                static function (int $value) use ($var1, $var2): int {
                    return $value + $var1 + $var2;
                },
                'Closure { static fn(int $value) use ($var1, $var2): int }',
            ],
            'non-static closure with use variable' => [
                function (int $value) use ($var1): int {
                    return $value + $var1;
                },
                'Closure { fn(int $value) use ($var1): int }',
            ],
        ];
    }

    /** @return array<string, array{0: callable, 1: string}> */
    public static function nonClosureCallableRawValuesProvider(): array
    {
        return [
            'invokable object' => [
                new class {
                    public function __invoke(int $parameter): never
                    {
                        exit($parameter);
                    }
                },
                'class->__invoke(int $parameter): never',
            ],
            'object method as array' => [
                [new DateTime(), 'format'],
                'DateTime->format(string $format)',
            ],
            'static method as array' => [
                ['DateTime', 'createFromImmutable'],
                'DateTime::createFromImmutable(DateTimeImmutable $object)',
            ],
            'static method as string' => [
                'DateTimeImmutable::getLastErrors',
                'DateTimeImmutable::getLastErrors()',
            ],
            'function name as string' => [
                'chr',
                'chr(int $codepoint): string',
            ],
        ];
    }
}
