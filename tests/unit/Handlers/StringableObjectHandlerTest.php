<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use ConcreteStringable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\StringableObjectHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;
use stdClass;

use function sprintf;

#[CoversClass(StringableObjectHandler::class)]
final class StringableObjectHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnInstanceOfStringable(): void
    {
        $sut = new StringableObjectHandler(new FakeHandler(), new FakeQuoter());

        self::assertNull($sut->handle(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInstanceOfStringable(): void
    {
        $raw = new ConcreteStringable();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $string = $stringifier->handle($raw->__toString(), self::DEPTH + 1);

        $sut = new StringableObjectHandler($stringifier, $quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('%s { __toString() => %s }', ConcreteStringable::class, $string),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }
}
