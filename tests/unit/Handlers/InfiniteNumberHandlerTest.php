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
use Respect\Stringifier\Handlers\InfiniteNumberHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

use const INF;

#[CoversClass(InfiniteNumberHandler::class)]
final class InfiniteNumberHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotFloat(): void
    {
        $sut = new InfiniteNumberHandler(new FakeQuoter());

        self::assertNull($sut->handle(1, self::DEPTH));
    }

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsFiniteFloat(): void
    {
        $sut = new InfiniteNumberHandler(new FakeQuoter());

        self::assertNull($sut->handle(1.0, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInfinitePositiveNumber(): void
    {
        $quoter = new FakeQuoter();

        $sut = new InfiniteNumberHandler($quoter);

        $actual = $sut->handle(INF, self::DEPTH);
        $expected = $quoter->quote('INF', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInfiniteNegativeNumber(): void
    {
        $quoter = new FakeQuoter();

        $sut = new InfiniteNumberHandler($quoter);

        $actual = $sut->handle(INF * -1, self::DEPTH);
        $expected = $quoter->quote('-INF', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
