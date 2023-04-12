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
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\StringableObjectStringifier;
use Respect\Stringifier\Test\MyStringable;
use stdClass;

#[CoversClass(StringableObjectStringifier::class)]
final class StringableObjectStringifierTest extends TestCase
{
    #[Test]
    public function shouldNotConvertToStringWhenValueIsNotAnObject(): void
    {
        $raw = 'not-an-object';
        $depth = 1;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $stringableObjectStringifier = new StringableObjectStringifier($stringifierMock);

        self::assertNull($stringableObjectStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldNotConvertToStringWhenValueIsNonStringableObject(): void
    {
        $raw = new stdClass();
        $depth = 1;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $stringableObjectStringifier = new StringableObjectStringifier($stringifierMock);

        self::assertNull($stringableObjectStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldConvertToStringWhenValueIsAnStringableObject(): void
    {
        $raw = new MyStringable();
        $depth = 0;

        $expectedValue = MyStringable::STRING_VALUE;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with(MyStringable::STRING_VALUE, $depth)
            ->willReturn($expectedValue);

        $stringableObjectStringifier = new StringableObjectStringifier($stringifierMock);

        self::assertSame($expectedValue, $stringableObjectStringifier->stringify($raw, $depth));
    }
}
