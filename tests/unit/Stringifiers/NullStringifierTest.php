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
use Respect\Stringifier\Stringifiers\NullStringifier;

/**
 * @covers \Respect\Stringifier\Stringifiers\NullStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class NullStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotNull(): void
    {
        $raw = 1;
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $nullStringifier = new NullStringifier($quoterMock);

        self::assertNull($nullStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsNull(): void
    {
        $raw = null;
        $depth = 0;

        $expected = 'NULL';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $nullStringifier = new NullStringifier($quoterMock);

        self::assertSame($expected, $nullStringifier->stringify($raw, $depth));
    }
}
