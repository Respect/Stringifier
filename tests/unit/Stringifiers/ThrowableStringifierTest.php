<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use Error;
use ErrorException;
use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\ThrowableStringifier;
use stdClass;
use Throwable;
use TypeError;

use function sprintf;

#[CoversClass(ThrowableStringifier::class)]
final class ThrowableStringifierTest extends TestCase
{
    #[Test]
    #[DataProvider('validValuesProvider')]
    public function shouldConvertThrowableToString(Throwable $raw, int $line): void
    {
        $depth = 1;

        $stringifiedData = '-stringifed-';

        $expectedValue = sprintf(
            '[throwable] (%s: %s)',
            $raw::class,
            $stringifiedData
        );

        $expectedData = [
            'message' => $raw->getMessage(),
            'code' => $raw->getCode(),
            'file' => 'tests/unit/Stringifiers/ThrowableStringifierTest.php:' . $line,
        ];

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with($expectedData, $depth + 1)
            ->willReturn($stringifiedData);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $throwableStringifier = new ThrowableStringifier($stringifierMock, $quoterMock);

        self::assertSame($expectedValue, $throwableStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldReturnNullWhenNotInstanceOfThrowable(): void
    {
        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $throwableStringifier = new ThrowableStringifier($stringifierMock, $quoterMock);

        self::assertNull($throwableStringifier->stringify(new stdClass(), 0));
    }

    /**
     * @return mixed[][]
     */
    public static function validValuesProvider(): array
    {
        return [
            [new Exception('Message for Exception', 0), __LINE__],
            [new ErrorException('Message for ErrorException', 102), __LINE__],
            [new Error('Message for Error', 78), __LINE__],
            [new TypeError('Message for TypeError', 1009), __LINE__],
        ];
    }
}
