<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use Fiber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\FiberObjectHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;
use stdClass;
use WithInvoke;

use function sprintf;

#[CoversClass(FiberObjectHandler::class)]
final class FiberObjectHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnObjectWithDebugInfo(): void
    {
        $sut = new FiberObjectHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithDebugInfo(): void
    {
        $callable = new WithInvoke();

        $raw = new Fiber($callable);

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $string = $stringifier->handle($callable, self::DEPTH + 1);

        $sut = new FiberObjectHandler($stringifier, $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('Fiber { %s }', $string),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
