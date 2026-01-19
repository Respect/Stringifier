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
use Respect\Stringifier\Handlers\JsonEncodableHandler;

use function tmpfile;

#[CoversClass(JsonEncodableHandler::class)]
final class JsonEncodableHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItCannotBeConvertedToJson(): void
    {
        $sut = new JsonEncodableHandler();

        self::assertNull($sut->handle(tmpfile(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItCanBeConvertedToJson(): void
    {
        $raw = 'É uma \' " string';

        $sut = new JsonEncodableHandler();

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = '"É uma \' \" string"';

        self::assertSame($expected, $actual);
    }
}
