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
use Respect\Stringifier\Handlers\NullHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(NullHandler::class)]
final class NullHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotNull(): void
    {
        $sut = new NullHandler(new FakeQuoter());

        self::assertNull($sut->handle(1, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsNull(): void
    {
        $quoter = new FakeQuoter();

        $sut = new NullHandler($quoter);

        $actual = $sut->handle(null, self::DEPTH);
        $expected = $quoter->quote('null', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
