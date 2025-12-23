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
use Respect\Stringifier\Stringifiers\NotANumberStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function acos;

#[CoversClass(NotANumberStringifier::class)]
final class NotANumberStringifierTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotFloat(): void
    {
        $sut = new NotANumberStringifier(new FakeQuoter());

        self::assertNull($sut->stringify('string', self::DEPTH));
    }

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNumber(): void
    {
        $sut = new NotANumberStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(1.00000000002, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsNotNumber(): void
    {
        $quoter = new FakeQuoter();

        $sut = new NotANumberStringifier($quoter);

        $actual = $sut->stringify(acos(8), self::DEPTH);
        $expected = $quoter->quote('NaN', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
