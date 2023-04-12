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
use Respect\Stringifier\Stringifiers\BoolStringifier;

#[CoversClass(BoolStringifier::class)]
final class BoolStringifierTest extends TestCase
{
    #[Test]
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

    #[Test]
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

    #[Test]
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
