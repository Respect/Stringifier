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
use Respect\Stringifier\Stringifiers\JsonParsableStringifier;

use function tmpfile;

#[CoversClass(JsonParsableStringifier::class)]
final class JsonParsableStringifierTest extends TestCase
{
    #[Test]
    public function shouldReturnNullWhenNotPossibleToConvertToParsableJsonString(): void
    {
        $raw = tmpfile();
        $depth = 0;

        $jsonSerializableStringifier = new JsonParsableStringifier();

        self::assertNull($jsonSerializableStringifier->stringify($raw, $depth));
    }

    #[Test]
    public function shouldConvertRawValueToParsableJsonString(): void
    {
        $raw = 'É uma \' " string';
        $depth = 0;

        $expected = '"É uma \' \" string"';

        $jsonSerializableStringifier = new JsonParsableStringifier();

        self::assertSame($expected, $jsonSerializableStringifier->stringify($raw, $depth));
    }
}
