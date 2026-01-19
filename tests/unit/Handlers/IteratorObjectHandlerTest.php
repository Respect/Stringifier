<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use ArrayIterator;
use ConcreteIterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\IteratorObjectHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function sprintf;

#[CoversClass(IteratorObjectHandler::class)]
final class IteratorObjectHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotTraversable(): void
    {
        $sut = new IteratorObjectHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle([1, 2, 3, 4], self::DEPTH));
    }

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsIterableThatIsNotValid(): void
    {
        $sut = new IteratorObjectHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle(new ArrayIterator([]), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInstanceOfIterator(): void
    {
        $raw = new ConcreteIterator();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new IteratorObjectHandler($stringifier, $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'ConcreteIterator { current() => %s }',
                $stringifier->handle($raw->current(), self::DEPTH + 1),
            ),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
