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
use Respect\Stringifier\Stringifiers\CompositeStringifier;
use Respect\Stringifier\Test\Double\FakeStringifier;
use Respect\Stringifier\Test\Double\LameStringifier;
use stdClass;

#[CoversClass(CompositeStringifier::class)]
final class CompositeStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenThereAreNoStringifiersDefined(): void
    {
        $sut = new CompositeStringifier();

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueByTryingToUseAllStringifiersUntilOneCanStringifyIt(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeStringifier();

        $sut = new CompositeStringifier(new LameStringifier(), new LameStringifier(), $stringifier);

        $actual = $sut->stringify(new stdClass(), self::DEPTH);
        $expected = $stringifier->stringify($raw, self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueStartingWithTheLastPrependedStringifier(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeStringifier();

        $sut = new CompositeStringifier(new LameStringifier(), new LameStringifier());
        $sut->prependStringifier($stringifier);

        $actual = $sut->stringify(new stdClass(), self::DEPTH);
        $expected = $stringifier->stringify($raw, self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldNotStringifyWhenNoneOfTheStringifiersCanStringify(): void
    {
        $sut = new CompositeStringifier(new LameStringifier(), new LameStringifier(), new LameStringifier());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldCreateDefaultCompositeStringifier(): void
    {
        self::assertInstanceOf(CompositeStringifier::class, CompositeStringifier::createDefault());
    }
}
