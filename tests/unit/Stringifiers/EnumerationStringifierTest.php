<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use BackedEnumeration;
use BasicEnumeration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\EnumerationStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(EnumerationStringifier::class)]
final class EnumerationStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotAnEnumeration(): void
    {
        $sut = new EnumerationStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(1, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsBasicEnumeration(): void
    {
        $quoter = new FakeQuoter();

        $sut = new EnumerationStringifier($quoter);

        $actual = $sut->stringify(BasicEnumeration::BAR, self::DEPTH);
        $expected = $quoter->quote('BasicEnumeration::BAR', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsBackedEnumeration(): void
    {
        $quoter = new FakeQuoter();

        $sut = new EnumerationStringifier($quoter);

        $actual = $sut->stringify(BackedEnumeration::BAZ, self::DEPTH);
        $expected = $quoter->quote('BackedEnumeration::BAZ', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
