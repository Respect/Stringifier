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
use Respect\Stringifier\Stringifiers\NanStringifier;

/**
 * @covers \Respect\Stringifier\Stringifiers\NanStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class NanStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotAFloat(): void
    {
        $raw = 'string';
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $nanStringifier = new NanStringifier($quoterMock);

        self::assertNull($nanStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsANumber(): void
    {
        $raw = 1.00000000002;
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $nanStringifier = new NanStringifier($quoterMock);

        self::assertNull($nanStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsNotANumber(): void
    {
        $raw = acos(8);
        $depth = 0;

        $expected = 'NaN';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $nanStringifier = new NanStringifier($quoterMock);

        self::assertSame($expected, $nanStringifier->stringify($raw, $depth));
    }
}
