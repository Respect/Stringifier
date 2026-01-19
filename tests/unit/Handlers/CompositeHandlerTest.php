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
use Respect\Stringifier\Handlers\CompositeHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\LameHandler;
use stdClass;

#[CoversClass(CompositeHandler::class)]
final class CompositeHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenThereAreNoHandlersDefined(): void
    {
        $sut = new CompositeHandler();

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueByTryingToUseAllHandlersUntilOneCanStringifyIt(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeHandler();

        $sut = new CompositeHandler(new LameHandler(), new LameHandler(), $stringifier);

        $actual = $sut->handle(new stdClass(), self::DEPTH);
        $expected = $stringifier->handle($raw, self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueStartingWithTheLastPrependedHandler(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeHandler();

        $sut = new CompositeHandler(new LameHandler(), new LameHandler());
        $sut->prependHandler($stringifier);

        $actual = $sut->handle(new stdClass(), self::DEPTH);
        $expected = $stringifier->handle($raw, self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldNotStringifyWhenNoneOfTheHandlersCanStringify(): void
    {
        $sut = new CompositeHandler(new LameHandler(), new LameHandler(), new LameHandler());

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldCreateDefaultCompositeHandler(): void
    {
        self::assertInstanceOf(CompositeHandler::class, CompositeHandler::create());
    }
}
