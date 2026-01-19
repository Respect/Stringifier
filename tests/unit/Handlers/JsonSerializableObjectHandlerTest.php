<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use ConcreteJsonSerializable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\JsonSerializableObjectHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;
use stdClass;

use function sprintf;

#[CoversClass(JsonSerializableObjectHandler::class)]
final class JsonSerializableObjectHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnInstanceOfJsonSerializable(): void
    {
        $sut = new JsonSerializableObjectHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInstanceOfJsonSerializable(): void
    {
        $raw = new ConcreteJsonSerializable();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $jsonString = $stringifier->handle($raw->jsonSerialize(), self::DEPTH + 1);

        $sut = new JsonSerializableObjectHandler($stringifier, $quoter);
        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('%s { jsonSerialize() => %s }', ConcreteJsonSerializable::class, $jsonString),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
