<?php

/*
 * This file is part of Respect/Stringifier.
 *
 * (c) Henrique Moody <henriquemoody@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Stringifiers;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\DateTimeStringifier;

/**
 * @covers \Respect\Stringifier\Stringifiers\DateTimeStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class DateTimeStringifierTest extends TestCase
{
    /**
     * @test
     */
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

    /**
     * @test
     *
     * @dataProvider validValuesProvider
     *
     * @param DateTimeInterface $raw
     * @param string $format
     * @param string $expected
     */
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

    public function validValuesProvider(): array
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:sP', '2017-12-31T23:59:59+00:00');
        $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);

        return [
            [$dateTime, 'd/m/Y', '[date-time] (DateTime: 31/12/2017)'],
            [$dateTime, 'c', '[date-time] (DateTime: 2017-12-31T23:59:59+00:00)'],
            [$dateTimeImmutable, 'Y-m-d H:i:s', '[date-time] (DateTimeImmutable: 2017-12-31 23:59:59)'],
        ];
    }
}
