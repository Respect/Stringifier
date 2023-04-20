<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use Fiber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\FiberObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use stdClass;
use WithInvoke;

use function sprintf;

#[CoversClass(FiberObjectStringifier::class)]
final class FiberObjectStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnObjectWithDebugInfo(): void
    {
        $sut = new FiberObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithDebugInfo(): void
    {
        $callable = new WithInvoke();

        $raw = new Fiber($callable);

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $string = $stringifier->stringify($callable, self::DEPTH + 1);

        $sut = new FiberObjectStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('Fiber { %s }', $string),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }
}
