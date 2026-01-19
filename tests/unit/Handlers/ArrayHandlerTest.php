<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\ArrayHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function sprintf;

#[CoversClass(ArrayHandler::class)]
final class ArrayHandlerTest extends TestCase
{
    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnArray(): void
    {
        $sut = new ArrayHandler(new FakeHandler(), new FakeQuoter(), 3, 5);

        self::assertNull($sut->handle(false, 0));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnEmptyArray(): void
    {
        $raw = [];
        $depth = 0;

        $quoter = new FakeQuoter();

        $sut = new ArrayHandler(new FakeHandler(), $quoter, 3, 5);

        $actual = $sut->handle($raw, $depth);
        $expected = $quoter->quote('[]', $depth);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnNonAssociativeArray(): void
    {
        $raw = [1, 2, 3];
        $depth = 0;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ArrayHandler($stringifier, $quoter, 3, 5);

        $actual = $sut->handle($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s, %s, %s]',
                $stringifier->handle($raw[0], $depth + 1),
                $stringifier->handle($raw[1], $depth + 1),
                $stringifier->handle($raw[2], $depth + 1),
            ),
            $depth,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnAssociativeArray(): void
    {
        $raw = ['foo' => 1, 'bar' => 2];
        $depth = 0;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ArrayHandler($stringifier, $quoter, 3, 5);

        $actual = $sut->handle($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s: %s, %s: %s]',
                $stringifier->handle('foo', $depth + 1),
                $stringifier->handle($raw['foo'], $depth + 1),
                $stringifier->handle('bar', $depth + 1),
                $stringifier->handle($raw['bar'], $depth + 1),
            ),
            $depth,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenIsNotSequentialArray(): void
    {
        $raw = [1, 2 => 3];
        $depth = 0;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ArrayHandler($stringifier, $quoter, 3, 5);
        $expected = $quoter->quote(
            sprintf(
                '[%s: %s, %s: %s]',
                $stringifier->handle(0, $depth + 1),
                $stringifier->handle(1, $depth + 1),
                $stringifier->handle(2, $depth + 1),
                $stringifier->handle(3, $depth + 1),
            ),
            $depth,
        );

        self::assertSame($expected, $sut->handle($raw, $depth));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsNestedArray(): void
    {
        $raw = ['a', ['b', 'c', ['d', 'e']]];
        $depth = 0;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ArrayHandler($stringifier, $quoter, 3, 5);

        $actual = $sut->handle($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s, %s]',
                $stringifier->handle('a', $depth + 1),
                $quoter->quote(
                    sprintf(
                        '[%s, %s, %s]',
                        $stringifier->handle('b', $depth + 2),
                        $stringifier->handle('c', $depth + 2),
                        $quoter->quote(
                            sprintf(
                                '[%s, %s]',
                                $stringifier->handle('d', $depth + 3),
                                $stringifier->handle('e', $depth + 3),
                            ),
                            $depth + 2,
                        ),
                    ),
                    $depth + 1,
                ),
            ),
            $depth,
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

        $sut = new ArrayHandler(new FakeHandler(), $quoter, $maximumDepth, 5);

        $actual = $sut->handle($raw, $depth);
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

        $sut = new ArrayHandler(new FakeHandler(), $quoter, $maximumDepth, 5);

        $actual = $sut->handle($raw, $depth);
        $expected = $quoter->quote('...', $depth);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenItReachesTheMaximumDepth(): void
    {
        $raw = ['a', ['b', ['c', ['d', ['e', 'f']]]]];
        $depth = 0;

        $maximumDepth = 3;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ArrayHandler($stringifier, $quoter, $maximumDepth, 5);

        $actual = $sut->handle($raw, $depth);
        $expected = $quoter->quote(
            sprintf(
                '[%s, %s]',
                $stringifier->handle('a', $depth + 1),
                $quoter->quote(
                    sprintf(
                        '[%s, %s]',
                        $stringifier->handle('b', $depth + 2),
                        $quoter->quote(
                            sprintf(
                                '[%s, %s]',
                                $stringifier->handle('c', $depth + 3),
                                $quoter->quote('...', $depth + 3),
                            ),
                            $depth + 2,
                        ),
                    ),
                    $depth + 1,
                ),
            ),
            $depth,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenLimitOfItemsIsReached(): void
    {
        $itemsLimit = 3;

        $raw = [1, 2, 3, 4, 5, 6];
        $depth = 0;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ArrayHandler($stringifier, $quoter, 1, $itemsLimit);

        $expected = $quoter->quote(
            sprintf(
                '[%s, %s, %s, ...]',
                $stringifier->handle(1, $depth + 1),
                $stringifier->handle(2, $depth + 1),
                $stringifier->handle(3, $depth + 1),
            ),
            $depth,
        );
        $actual = $sut->handle($raw, $depth);

        self::assertSame($expected, $actual);
    }
}
