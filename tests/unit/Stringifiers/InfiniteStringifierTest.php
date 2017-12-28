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

use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifiers\InfiniteStringifier;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Respect\Stringifier\Stringifiers\InfiniteStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class InfiniteStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotFloat(): void
    {
        $raw = 1;
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $infiniteStringifier = new InfiniteStringifier($quoterMock);

        self::assertNull($infiniteStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsFiniteFloat(): void
    {
        $raw = 1.0;
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $infiniteStringifier = new InfiniteStringifier($quoterMock);

        self::assertNull($infiniteStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsInfinitePositive(): void
    {
        $raw = INF;
        $depth = 0;

        $expected = '1.0';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($raw, $depth)
            ->willReturn($expected);

        $infiniteStringifier = new InfiniteStringifier($quoterMock);

        self::assertSame($expected, $infiniteStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsInfiniteNegative(): void
    {
        $raw = -1 * INF;
        $depth = 0;

        $expected = '-1.0';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($raw, $depth)
            ->willReturn($expected);

        $infiniteStringifier = new InfiniteStringifier($quoterMock);

        self::assertSame($expected, $infiniteStringifier->stringify($raw, $depth));
    }
}
