<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use ArrayObject;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\ArrayObjectHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;
use stdClass;

use function sprintf;

#[CoversClass(ArrayObjectHandler::class)]
final class ArrayObjectHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnArrayObject(): void
    {
        $sut = new ArrayObjectHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnArrayObject(): void
    {
        $raw = new ArrayObject([1, 2, 3]);

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $string = $stringifier->handle($raw->getArrayCopy(), self::DEPTH + 1);

        $sut = new ArrayObjectHandler($stringifier, $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('ArrayObject { getArrayCopy() => %s }', $string),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
