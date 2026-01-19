<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\BoolHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(BoolHandler::class)]
final class BoolHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotBoolean(): void
    {
        $sut = new BoolHandler(new FakeQuoter());

        self::assertNull($sut->handle(1, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsTrue(): void
    {
        $quoter = new FakeQuoter();

        $sut = new BoolHandler($quoter);

        $actual = $sut->handle(true, self::DEPTH);
        $expected = $quoter->quote('true', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsFalse(): void
    {
        $quoter = new FakeQuoter();

        $sut = new BoolHandler($quoter);

        $actual = $sut->handle(false, self::DEPTH);
        $expected = $quoter->quote('false', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
