<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use ArrayObject;
use BasicEnumeration;
use Countable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Handlers\DeclaredHandler;
use Respect\Stringifier\Helpers\ObjectHelper;
use Respect\Stringifier\Test\Double\FakeQuoter;

#[CoversClass(DeclaredHandler::class)]
final class DeclaredHandlerTest extends TestCase
{
    private const int DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyWhenRawValueIsNotExists(): void
    {
        $sut = new DeclaredHandler(new FakeQuoter());

        self::assertNull($sut->handle('NotAClassInterfaceTraitOrEnum', self::DEPTH));
    }

    #[Test]
    #[DataProvider('existsRawValuesProvider')]
    public function itShouldStringifyWhenRawValueIsExists(string $raw): void
    {
        $quoter = new FakeQuoter();

        $sut = new DeclaredHandler($quoter);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote($raw, self::DEPTH);

        self::assertEquals($expected, $actual);
    }

    /** @return array<int, array<int, string>> */
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
