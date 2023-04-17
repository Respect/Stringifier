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
use Respect\Stringifier\Stringifiers\NullStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(NullStringifier::class)]
final class NullStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotNull(): void
    {
        $sut = new NullStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(1, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsNull(): void
    {
        $quoter = new FakeQuoter();

        $sut = new NullStringifier($quoter);

        $actual = $sut->stringify(null, self::DEPTH);
        $expected = $quoter->quote('null', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
