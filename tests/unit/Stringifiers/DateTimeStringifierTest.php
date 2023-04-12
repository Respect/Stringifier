<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Stringifiers;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\DateTimeStringifier;

#[CoversClass(DateTimeStringifier::class)]
final class DateTimeStringifierTest extends TestCase
{
    #[Test]
    public function shouldNotConvertWhenNotInstanceOfDateTimeInterface(): void
    {
        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $dateTimeStringifier = new DateTimeStringifier($stringifierMock, $quoterMock, 'c');

        self::assertNull($dateTimeStringifier->stringify('NotDateTimeInterface', 0));
    }

    #[Test]
    #[DataProvider('validValuesProvider')]
    public function shouldConvertDateTimeInterfaceToString(
        DateTimeInterface $raw,
        string $format,
        string $expected
    ): void {
        $depth = 0;

        $formattedDateTime = $raw->format($format);

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with($formattedDateTime, $depth + 1)
            ->willReturn($formattedDateTime);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected)
            ->willReturn($expected);

        $dateTimeStringifier = new DateTimeStringifier($stringifierMock, $quoterMock, $format);

        self::assertSame($expected, $dateTimeStringifier->stringify($raw, $depth));
    }

    /**
     * @return mixed[][]
     */
    public static function validValuesProvider(): array
    {
        $dateTime = new DateTime('2017-12-31T23:59:59+00:00');
        $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);

        return [
            [$dateTime, 'd/m/Y', '[date-time] (DateTime: 31/12/2017)'],
            [$dateTime, 'c', '[date-time] (DateTime: 2017-12-31T23:59:59+00:00)'],
            [$dateTimeImmutable, 'Y-m-d H:i:s', '[date-time] (DateTimeImmutable: 2017-12-31 23:59:59)'],
        ];
    }
}
