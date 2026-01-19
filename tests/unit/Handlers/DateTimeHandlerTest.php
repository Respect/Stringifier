<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\DateTimeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(DateTimeHandler::class)]
final class DateTimeHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotInstanceOfDateTimeInterface(): void
    {
        $sut = new DateTimeHandler(new FakeQuoter(), 'c');

        self::assertNull($sut->handle('NotDateTimeInterface', self::DEPTH));
    }

    #[Test]
    #[DataProvider('stringableRawValuesProvider')]
    public function itShouldStringifyRawValueWhenItIsInstanceOfDateTimeInterface(
        DateTimeInterface $raw,
        string $format,
        string $string,
    ): void {
        $quoter = new FakeQuoter();

        $sut = new DateTimeHandler($quoter, $format);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote($string, self::DEPTH);

        self::assertSame($expected, $actual);
    }

    /** @return array<int, array{0: DateTimeInterface, 1: string, 2: string}> */
    public static function stringableRawValuesProvider(): array
    {
        $dateTime = new DateTime('2017-12-31T23:59:59+00:00');
        $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);

        return [
            [$dateTime, 'd/m/Y', 'DateTime { 31/12/2017 }'],
            [$dateTime, 'c', 'DateTime { 2017-12-31T23:59:59+00:00 }'],
            [$dateTime, DateTimeInterface::ATOM, 'DateTime { 2017-12-31T23:59:59+00:00 }'],
            [$dateTimeImmutable, 'Y-m-d H:i:s', 'DateTimeImmutable { 2017-12-31 23:59:59 }'],
        ];
    }
}
