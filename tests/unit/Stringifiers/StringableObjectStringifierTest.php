<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use ConcreteStringable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Stringifiers\StringableObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use stdClass;

use function sprintf;

#[CoversClass(ObjectHelper::class)]
#[CoversClass(StringableObjectStringifier::class)]
final class StringableObjectStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnInstanceOfStringable(): void
    {
        $sut = new StringableObjectStringifier(new FakeStringifier(), new FakeQuoter());

        self::assertNull($sut->stringify(new stdClass(), self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnInstanceOfStringable(): void
    {
        $raw = new ConcreteStringable();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $string = $stringifier->stringify($raw->__toString(), self::DEPTH + 1);

        $sut = new StringableObjectStringifier($stringifier, $quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf('%s { __toString() => %s }', ConcreteStringable::class, $string),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }
}
