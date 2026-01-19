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
use Respect\Stringifier\Handlers\ObjectWithDebugInfoHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;
use stdClass;
use WithDebugInfo;

use function sprintf;

#[CoversClass(ObjectWithDebugInfoHandler::class)]
final class ObjectWithDebugInfoHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnObjectWithDebugInfo(): void
    {
        $sut = new ObjectWithDebugInfoHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithDebugInfo(): void
    {
        $raw = new WithDebugInfo();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $string = $stringifier->handle($raw->__debugInfo(), self::DEPTH + 1);

        $sut = new ObjectWithDebugInfoHandler($stringifier, $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('%s { __debugInfo() => %s }', WithDebugInfo::class, $string),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
