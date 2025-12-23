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
use Respect\Stringifier\Stringifiers\InfiniteNumberStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

use const INF;

#[CoversClass(InfiniteNumberStringifier::class)]
final class InfiniteNumberStringifierTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotFloat(): void
    {
        $sut = new InfiniteNumberStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(1, self::DEPTH));
    }

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsFiniteFloat(): void
    {
        $sut = new InfiniteNumberStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(1.0, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInfinitePositiveNumber(): void
    {
        $quoter = new FakeQuoter();

        $sut = new InfiniteNumberStringifier($quoter);

        $actual = $sut->stringify(INF, self::DEPTH);
        $expected = $quoter->quote('INF', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInfiniteNegativeNumber(): void
    {
        $quoter = new FakeQuoter();

        $sut = new InfiniteNumberStringifier($quoter);

        $actual = $sut->stringify(INF * -1, self::DEPTH);
        $expected = $quoter->quote('-INF', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
