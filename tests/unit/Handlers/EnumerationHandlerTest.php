<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use BackedEnumeration;
use BasicEnumeration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\EnumerationHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(EnumerationHandler::class)]
final class EnumerationHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotAnEnumeration(): void
    {
        $sut = new EnumerationHandler(new FakeQuoter());

        self::assertNull($sut->handle(1, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsBasicEnumeration(): void
    {
        $quoter = new FakeQuoter();

        $sut = new EnumerationHandler($quoter);

        $actual = $sut->handle(BasicEnumeration::BAR, self::DEPTH);
        $expected = $quoter->quote('BasicEnumeration::BAR', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsBackedEnumeration(): void
    {
        $quoter = new FakeQuoter();

        $sut = new EnumerationHandler($quoter);

        $actual = $sut->handle(BackedEnumeration::BAZ, self::DEPTH);
        $expected = $quoter->quote('BackedEnumeration::BAZ', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
