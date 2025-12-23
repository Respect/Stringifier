<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use ArrayObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\ArrayObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use stdClass;

use function sprintf;

#[CoversClass(ArrayObjectStringifier::class)]
final class ArrayObjectStringifierTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnArrayObject(): void
    {
        $sut = new ArrayObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnArrayObject(): void
    {
        $raw = new ArrayObject([1, 2, 3]);

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $string = $stringifier->stringify($raw->getArrayCopy(), self::DEPTH + 1);

        $sut = new ArrayObjectStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('ArrayObject { getArrayCopy() => %s }', $string),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
