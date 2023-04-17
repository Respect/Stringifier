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
use Respect\Stringifier\Stringifiers\BoolStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(BoolStringifier::class)]
final class BoolStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotBoolean(): void
    {
        $sut = new BoolStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(1, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsTrue(): void
    {
        $quoter = new FakeQuoter();

        $sut = new BoolStringifier($quoter);

        $actual = $sut->stringify(true, self::DEPTH);
        $expected = $quoter->quote('true', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsFalse(): void
    {
        $quoter = new FakeQuoter();

        $sut = new BoolStringifier($quoter);

        $actual = $sut->stringify(false, self::DEPTH);
        $expected = $quoter->quote('false', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
