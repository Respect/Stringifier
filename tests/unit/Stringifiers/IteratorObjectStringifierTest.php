<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use ArrayIterator;
use ConcreteIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\IteratorObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;

use function sprintf;

#[CoversClass(IteratorObjectStringifier::class)]
final class IteratorObjectStringifierTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotTraversable(): void
    {
        $sut = new IteratorObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify([1, 2, 3, 4], self::DEPTH));
    }

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsIterableThatIsNotValid(): void
    {
        $sut = new IteratorObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new ArrayIterator([]), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInstanceOfIterator(): void
    {
        $raw = new ConcreteIterator();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new IteratorObjectStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'ConcreteIterator { current() => %s }',
                $stringifier->stringify($raw->current(), self::DEPTH + 1),
            ),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
