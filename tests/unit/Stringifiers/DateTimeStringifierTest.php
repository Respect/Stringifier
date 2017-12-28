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

        $dateTimeStringifier = new DateTimeStringifier($stringifierMock, 'c');

        self::assertNull($dateTimeStringifier->stringify('NotDateTimeInterface', 0));
    }

    /**
     * @test
     *
     * @dataProvider validValuesProvider
     *
     * @param DateTimeInterface $raw
     * @param string $format
     * @param string $expectedValue
     */
    public function shouldConvertDateTimeInterfaceToString(
        DateTimeInterface $raw,
        string $format,
        string $expectedValue
    ): void {
        $depth = 0;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $dateTimeStringifier = new DateTimeStringifier($stringifierMock, $format);

        self::assertSame($expectedValue, $dateTimeStringifier->stringify($raw, $depth));
    }

    public function validValuesProvider(): array
    {
        $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:sP', '2017-12-31T23:59:59+00:00');
        $dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);

        return [
            [$dateTime, 'd/m/Y', '31/12/2017'],
            [$dateTime, 'c', '2017-12-31T23:59:59+00:00'],
            [$dateTimeImmutable, 'Y-m-d H:i:s', '2017-12-31 23:59:59'],
        ];
    }
}
