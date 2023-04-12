<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifiers\InfiniteStringifier;

use const INF;

#[CoversClass(InfiniteStringifier::class)]
final class InfiniteStringifierTest extends TestCase
{
    #[Test]
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

    #[Test]
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

    #[Test]
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

    #[Test]
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
