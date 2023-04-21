<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Quoters;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoters\StandardQuoter;

use function strlen;

#[CoversClass(StandardQuoter::class)]
final class StandardQuoterTest extends TestCase
{
    private const LIMIT = 20;

    #[Test]
    public function itShouldNotQuoteWhenDepthIsBiggerThanZero(): void
    {
        $quoter = new StandardQuoter(self::LIMIT);

        $expectedValue = 'code';
        $actualValue = $quoter->quote('code', 1);

        self::assertSame($expectedValue, $actualValue);
    }

    #[Test]
    #[DataProvider('provideData')]
    public function isShouldQuoteWhenDepthIsBiggerThanZero(string $string, string $expected): void
    {
        $sut = new StandardQuoter(self::LIMIT);

        $actual = $sut->quote($string, 0);

        self::assertSame($expected, $actual);
        self::assertLessThanOrEqual(self::LIMIT, strlen($actual));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function provideData(): array
    {
        return [
            ['É isso aí', '`É isso aí`'],
            ['123456789', '`123456789`'],
            ['1234567890ABCDEFGH', '`1234567890ABCDEFGH`'],
            ['1234567890ABCDEFGHI', '`1234567890ABCD ...`'],
            ['class { 90ABCDEF }', '`class { 90ABCDEF }`'],
            ['class { 90ABCDEFGH }', '`class { 90AB ... }`'],
            ['[2, 5, 7, A, D, G]', '`[2, 5, 7, A, D, G]`'],
            ['[2, 5, 7, A, D, G, H]', '`[2, 5, 7, A, ... ]`'],
        ];
    }
}
