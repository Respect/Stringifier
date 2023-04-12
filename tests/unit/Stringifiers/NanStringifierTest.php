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
use Respect\Stringifier\Stringifiers\NanStringifier;

use function acos;

#[CoversClass(NanStringifier::class)]
final class NanStringifierTest extends TestCase
{
    #[Test]
    public function shouldNotConvertToStringWhenRawValueIsNotFloat(): void
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

    #[Test]
    public function shouldNotConvertToStringWhenRawValueIsNumber(): void
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

    #[Test]
    public function shouldConvertToStringWhenRawValueIsNotNumber(): void
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
