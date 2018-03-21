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

use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifiers\BoolStringifier;

/**
 * @covers \Respect\Stringifier\Stringifiers\BoolStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class BoolStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotBoolean(): void
    {
        $raw = 1;
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $boolStringifier = new BoolStringifier($quoterMock);

        self::assertNull($boolStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsTrue(): void
    {
        $raw = true;
        $depth = 0;

        $expected = 'TRUE';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $boolStringifier = new BoolStringifier($quoterMock);

        self::assertSame($expected, $boolStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsFalse(): void
    {
        $raw = false;
        $depth = 0;

        $expected = 'FALSE';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $boolStringifier = new BoolStringifier($quoterMock);

        self::assertSame($expected, $boolStringifier->stringify($raw, $depth));
    }
}
