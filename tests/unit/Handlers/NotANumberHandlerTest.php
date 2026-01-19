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
use Respect\Stringifier\Handlers\NotANumberHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function acos;

#[CoversClass(NotANumberHandler::class)]
final class NotANumberHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotFloat(): void
    {
        $sut = new NotANumberHandler(new FakeQuoter());

        self::assertNull($sut->handle('string', self::DEPTH));
    }

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNumber(): void
    {
        $sut = new NotANumberHandler(new FakeQuoter());

        self::assertNull($sut->handle(1.00000000002, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsNotNumber(): void
    {
        $quoter = new FakeQuoter();

        $sut = new NotANumberHandler($quoter);

        $actual = $sut->handle(acos(8), self::DEPTH);
        $expected = $quoter->quote('NaN', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
