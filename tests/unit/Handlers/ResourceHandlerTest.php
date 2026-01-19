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
use Respect\Stringifier\Handlers\ResourceHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function tmpfile;

#[CoversClass(ResourceHandler::class)]
final class ResourceHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotOfTypeResource(): void
    {
        $sut = new ResourceHandler(new FakeQuoter());

        self::assertNull($sut->handle(true, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsOfTypeResource(): void
    {
        $quoter = new FakeQuoter();

        $sut = new ResourceHandler($quoter);

        $actual = $sut->handle(tmpfile(), self::DEPTH);
        $expected = $quoter->quote('resource <stream>', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
