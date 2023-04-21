<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringify;
use Respect\Stringifier\Test\Double\FakeStringifier;
use Respect\Stringifier\Test\Double\LameStringifier;
use stdClass;

use function print_r;

#[CoversClass(Stringify::class)]
final class StringifyTest extends TestCase
{
    #[Test]
    public function itShouldStringifyRawValueWithTheGivenStringifier(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeStringifier();

        $sut = new Stringify($stringifier);

        $actual = $sut->value($raw);
        $expected = $stringifier->stringify($raw, Stringify::STARTING_DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueEvenIfTheGivenStringifierCannotStringify(): void
    {
        $raw = new stdClass();

        $sut = new Stringify(new LameStringifier());

        $actual = $sut->value($raw);
        $expected = print_r($raw, true);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueViaInvokeMethod(): void
    {
        $raw = new stdClass();

        $sut = new Stringify(new LameStringifier());

        $actual = $sut($raw);
        $expected = print_r($raw, true);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldCreateDefaultStringifyObject(): void
    {
        self::assertInstanceOf(Stringify::class, Stringify::createDefault());
    }
}
