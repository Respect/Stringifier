<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\JsonEncodableStringifier;

use function tmpfile;

#[CoversClass(JsonEncodableStringifier::class)]
final class JsonEncodableStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItCannotBeConvertedToJson(): void
    {
        $sut = new JsonEncodableStringifier();

        self::assertNull($sut->stringify(tmpfile(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItCanBeConvertedToJson(): void
    {
        $raw = 'É uma \' " string';

        $sut = new JsonEncodableStringifier();

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = '"É uma \' \" string"';

        self::assertSame($expected, $actual);
    }
}
