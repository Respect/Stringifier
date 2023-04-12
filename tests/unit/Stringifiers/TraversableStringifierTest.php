<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use ArrayIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\TraversableStringifier;

#[CoversClass(TraversableStringifier::class)]
final class TraversableStringifierTest extends TestCase
{
    #[Test]
    public function shouldConvertToStringWhenValueIsTraversable(): void
    {
        $raw = new ArrayIterator([1, 2, 3]);
        $depth = 0;

        $stringifiedData = '-stringified-';

        $expectedValue = '[traversable] (ArrayIterator: ' . $stringifiedData . ')';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with($raw->getArrayCopy(), $depth + 1)
            ->willReturn($stringifiedData);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $traversableStringifier = new TraversableStringifier($stringifierMock, $quoterMock);

        self::assertSame($expectedValue, $traversableStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldNotConvertToStringWhenRawValueIsNotTraversable(): void
    {
        $raw = [1, 2, 3, 4];
        $depth = 123;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $traversableStringifier = new TraversableStringifier($stringifierMock, $quoterMock);

        self::assertNull($traversableStringifier->stringify($raw, $depth));
    }
}
