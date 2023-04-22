<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use ArrayObject;
use BasicEnumeration;
use Countable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Stringifiers\DeclaredStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(DeclaredStringifier::class)]
final class DeclaredStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotExists(): void
    {
        $sut = new DeclaredStringifier(new FakeQuoter());

        self::assertNull($sut->stringify('NotAClassInterfaceTraitOrEnum', self::DEPTH));
    }

    #[Test]
    #[DataProvider('existsRawValuesProvider')]
    public function itShouldStringifyWhenRawValueIsExists(string $raw): void
    {
        $quoter = new FakeQuoter();

        $sut = new DeclaredStringifier($quoter);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote($raw, self::DEPTH);

        self::assertEquals($expected, $actual);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function existsRawValuesProvider(): array
    {
        return [
            [ArrayObject::class],
            [Countable::class],
            [BasicEnumeration::class],
            [ObjectHelper::class],
        ];
    }
}
