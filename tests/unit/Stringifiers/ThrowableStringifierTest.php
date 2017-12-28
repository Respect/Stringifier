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

use Error;
use ErrorException;
use Exception;
use function get_class;
use function sprintf;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\ThrowableStringifier;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;
use TypeError;

/**
 * @covers \Respect\Stringifier\Stringifiers\ThrowableStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ThrowableStringifierTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validValuesProvider
     *
     * @param Throwable $raw
     * @param int $line
     */
    public function shouldConvertThrowableToString(Throwable $raw, int $line): void
    {
        $depth = 1;

        $stringifiedData = '-stringifed-';

        $expectedValue = sprintf(
            '[throwable] (%s: %s)',
            get_class($raw),
            $stringifiedData
        );

        $expectedData = [
            'message' => $raw->getMessage(),
            'code' => $raw->getCode(),
            'file' => 'tests/unit/Stringifiers/ThrowableStringifierTest.php:'.$line,
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

    public function validValuesProvider(): array
    {
        $line = 85;

        return [
            [new Exception('Message for Exception', 0), ++$line],
            [new ErrorException('Message for ErrorException', 102), ++$line],
            [new Error('Message for Error', 78), ++$line],
            [new TypeError('Message for TypeError', 1009), ++$line],
        ];
    }

    /**
     * @test
     */
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
}
