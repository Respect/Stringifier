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
use Respect\Stringifier\Stringifiers\ObjectWithDebugInfoStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use stdClass;
use WithDebugInfo;

use function sprintf;

#[CoversClass(ObjectWithDebugInfoStringifier::class)]
final class ObjectWithDebugInfoStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnObjectWithDebugInfo(): void
    {
        $sut = new ObjectWithDebugInfoStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithDebugInfo(): void
    {
        $raw = new WithDebugInfo();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $string = $stringifier->stringify($raw->__debugInfo(), self::DEPTH + 1);

        $sut = new ObjectWithDebugInfoStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('%s { __debugInfo() => %s }', WithDebugInfo::class, $string),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }
}
