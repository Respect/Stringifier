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
use Respect\Stringifier\Stringifiers\JsonSerializableStringifier;
use Respect\Stringifier\Test\MyJsonSerializable;
use stdClass;

#[CoversClass(JsonSerializableStringifier::class)]
final class JsonSerializableStringifierTest extends TestCase
{
    #[Test]
    public function shouldConvertToStringWhenRawValueIsJsonSerializableObject(): void
    {
        $raw = new MyJsonSerializable();
        $depth = 0;

        $stringifiedData = '-stringified-';

        $expectedValue = '[json-serializable] (' . MyJsonSerializable::class . ': ' . $stringifiedData . ')';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with(MyJsonSerializable::JSON_VALUE, $depth + 1)
            ->willReturn($stringifiedData);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $jsonSerializableStringifier = new JsonSerializableStringifier($stringifierMock, $quoterMock);

        self::assertSame($expectedValue, $jsonSerializableStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldNotConvertToStringWhenRawValueIsNotTraversable(): void
    {
        $raw = new stdClass();
        $depth = 1;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $jsonSerializableStringifier = new JsonSerializableStringifier($stringifierMock, $quoterMock);

        self::assertNull($jsonSerializableStringifier->stringify($raw, $depth));
    }
}
