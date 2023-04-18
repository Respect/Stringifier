<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use DomainException;
use Error;
use ErrorException;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\ThrowableObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use RuntimeException;
use stdClass;
use Throwable;
use TypeError;

use function sprintf;

#[CoversClass(ThrowableObjectStringifier::class)]
final class ThrowableObjectStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function isShouldNotStringifyRawValueWhenItIsNotThrowable(): void
    {
        $sut = new ThrowableObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    #[DataProvider('throwableWithMessageProvider')]
    public function itShouldStringifyRawValueWhenItIsThrowableWithMessage(Throwable $raw): void
    {
        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ThrowableObjectStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expectedValue = $quoter->quote(
            sprintf(
                '%s { %s in tests/unit/Stringifiers/ThrowableObjectStringifierTest.php:%d }',
                $raw::class,
                $stringifier->stringify($raw->getMessage(), self::DEPTH + 1),
                $raw->getLine(),
            ),
            self::DEPTH
        );

        self::assertSame($expectedValue, $actual);
    }

    #[Test]
    #[DataProvider('throwableWithoutMessageProvider')]
    public function itShouldStringifyRawValueWhenItIsThrowableWithoutMessage(Throwable $raw): void
    {
        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ThrowableObjectStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expectedValue = $quoter->quote(
            sprintf(
                '%s { in tests/unit/Stringifiers/ThrowableObjectStringifierTest.php:%d }',
                $raw::class,
                $raw->getLine(),
            ),
            self::DEPTH
        );

        self::assertSame($expectedValue, $actual);
    }

    /**
     * @return array<array{0: Throwable}>
     */
    public static function throwableWithMessageProvider(): array
    {
        return [
            [new Exception('Message for Exception')],
            [new ErrorException('Message for ErrorException')],
            [new Error('Message for Error')],
            [new TypeError('Message for TypeError')],
        ];
    }

    /**
     * @return array<array{0: Throwable}>
     */
    public static function throwableWithoutMessageProvider(): array
    {
        return [
            [new InvalidArgumentException()],
            [new RuntimeException()],
            [new DomainException()],
        ];
    }
}
