<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use ConcreteJsonSerializable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifiers\JsonSerializableObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use stdClass;

use function sprintf;

#[CoversClass(JsonSerializableObjectStringifier::class)]
final class JsonSerializableObjectStringifierTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnInstanceOfJsonSerializable(): void
    {
        $sut = new JsonSerializableObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInstanceOfJsonSerializable(): void
    {
        $raw = new ConcreteJsonSerializable();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $jsonString = $stringifier->stringify($raw->jsonSerialize(), self::DEPTH + 1);

        $sut = new JsonSerializableObjectStringifier($stringifier, $quoter);
        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('%s { jsonSerialize() => %s }', ConcreteJsonSerializable::class, $jsonString),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
