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
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\ObjectStringifier;

#[CoversClass(ObjectStringifier::class)]
final class ObjectStringifierTest extends TestCase
{
    #[Test]
    public function shouldConvertToStringWhenRawValueIsAnObject(): void
    {
        $data = ['foo' => 1, 'bar' => false];

        $raw = (object) $data;
        $depth = 0;

        $stringifiedData = '-stringified-';

        $expectedValue = '[object] (stdClass: ' . $stringifiedData . ')';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with($data, $depth + 1)
            ->willReturn($stringifiedData);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $objectStringifier = new ObjectStringifier($stringifierMock, $quoterMock);

        self::assertSame($expectedValue, $objectStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldNotConvertToStringWhenRawValueIsNotTraversable(): void
    {
        $raw = true;
        $depth = 123;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $objectStringifier = new ObjectStringifier($stringifierMock, $quoterMock);

        self::assertNull($objectStringifier->stringify($raw, $depth));
    }
}
