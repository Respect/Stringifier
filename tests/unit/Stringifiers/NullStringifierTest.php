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
use Respect\Stringifier\Stringifiers\NullStringifier;

#[CoversClass(NullStringifier::class)]
final class NullStringifierTest extends TestCase
{
    #[Test]
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

    #[Test]
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
