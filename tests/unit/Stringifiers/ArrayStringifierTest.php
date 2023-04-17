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
use Respect\Stringifier\Stringifiers\ArrayStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;

use function sprintf;

#[CoversClass(ArrayStringifier::class)]
final class ArrayStringifierTest extends TestCase
{
    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnArray(): void
    {
        $sut = new ArrayStringifier(new FakeStringifier(), new FakeQuoter(), 3, 5);

        self::assertNull($sut->stringify(false, 0));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnEmptyArray(): void
    {
        $raw = [];
        $depth = 0;

        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier(new FakeStringifier(), $quoter, 3, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote('[]', $depth);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnNonAssociativeArray(): void
    {
        $raw = [1, 2, 3];
        $depth = 0;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier($stringifier, $quoter, 3, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s, %s, %s]',
                $stringifier->stringify($raw[0], $depth + 1),
                $stringifier->stringify($raw[1], $depth + 1),
                $stringifier->stringify($raw[2], $depth + 1)
            ),
            $depth
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnAssociativeArray(): void
    {
        $raw = ['foo' => 1, 'bar' => 2];
        $depth = 0;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier($stringifier, $quoter, 3, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s: %s, %s: %s]',
                $stringifier->stringify('foo', $depth + 1),
                $stringifier->stringify($raw['foo'], $depth + 1),
                $stringifier->stringify('bar', $depth + 1),
                $stringifier->stringify($raw['bar'], $depth + 1),
            ),
            $depth
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenIsNotSequentialArray(): void
    {
        $raw = [1, 2 => 3];
        $depth = 0;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier($stringifier, $quoter, 3, 5);
        $expected = $quoter->quote(
            sprintf(
                '[%s: %s, %s: %s]',
                $stringifier->stringify(0, $depth + 1),
                $stringifier->stringify(1, $depth + 1),
                $stringifier->stringify(2, $depth + 1),
                $stringifier->stringify(3, $depth + 1),
            ),
            $depth
        );

        self::assertSame($expected, $sut->stringify($raw, $depth));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsNestedArray(): void
    {
        $raw = ['a', ['b', 'c', ['d', 'e']]];
        $depth = 0;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier($stringifier, $quoter, 3, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s, %s]',
                $stringifier->stringify('a', $depth + 1),
                $quoter->quote(
                    sprintf(
                        '[%s, %s, %s]',
                        $stringifier->stringify('b', $depth + 2),
                        $stringifier->stringify('c', $depth + 2),
                        $quoter->quote(
                            sprintf(
                                '[%s, %s]',
                                $stringifier->stringify('d', $depth + 3),
                                $stringifier->stringify('e', $depth + 3),
                            ),
                            $depth + 2
                        )
                    ),
                    $depth + 1
                ),
            ),
            $depth
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenDepthEqualsTheMaximumDepth(): void
    {
        $raw = [1, 2, 3];
        $depth = 42;

        $maximumDepth = $depth;

        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier(new FakeStringifier(), $quoter, $maximumDepth, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote('...', $depth);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenDepthIsBiggerThanMaximumDepth(): void
    {
        $raw = [1, 2, 3];
        $depth = 42;

        $maximumDepth = $depth - 1;

        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier(new FakeStringifier(), $quoter, $maximumDepth, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote('...', $depth);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenItReachesTheMaximumDepth(): void
    {
        $raw = ['a', ['b', ['c', ['d', ['e', 'f']]]]];
        $depth = 0;

        $maximumDepth = 3;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier($stringifier, $quoter, $maximumDepth, 5);

        $actual = $sut->stringify($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s, %s]',
                $stringifier->stringify('a', $depth + 1),
                $quoter->quote(
                    sprintf(
                        '[%s, %s]',
                        $stringifier->stringify('b', $depth + 2),
                        $quoter->quote(
                            sprintf(
                                '[%s, %s]',
                                $stringifier->stringify('c', $depth + 3),
                                $quoter->quote('...', $depth + 3),
                            ),
                            $depth + 2
                        )
                    ),
                    $depth + 1
                ),
            ),
            $depth
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenLimitOfItemsIsReached(): void
    {
        $itemsLimit = 3;

        $raw = [1, 2, 3, 4, 5, 6];
        $depth = 0;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ArrayStringifier($stringifier, $quoter, 1, $itemsLimit);

        $expected = $quoter->quote(
            sprintf(
                '[%s, %s, %s, ...]',
                $stringifier->stringify(1, $depth + 1),
                $stringifier->stringify(2, $depth + 1),
                $stringifier->stringify(3, $depth + 1),
            ),
            $depth
        );
        $actual = $sut->stringify($raw, $depth);

        self::assertSame($expected, $actual);
    }
}
