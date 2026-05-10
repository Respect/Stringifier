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
use Respect\Stringifier\Quoters\LimiterQuoter;

use function mb_strlen;

#[CoversClass(LimiterQuoter::class)]
final class LimiterQuoterTest extends TestCase
{
    private const int LIMIT = 18;

    #[Test]
    #[DataProvider('provideData')]
    public function itShouldLimitString(string $string, string $expected): void
    {
        $sut = new LimiterQuoter(self::LIMIT);

        $actual = $sut->quote($string, 0);

        self::assertSame($expected, $actual);
        self::assertLessThanOrEqual(self::LIMIT, mb_strlen($actual));
    }

    /** @return array<int, array<int, string>> */
    public static function provideData(): array
    {
        return [
            ['short string', 'short string'],
            ['1234567890ABCDEFGH', '1234567890ABCDEFGH'],
            ['1234567890ABCDEFGHI', '1234567890ABCD ...'],
            ['class { 90ABCDEFGH }', 'class { 90AB ... }'],
            ['[2, 5, 7, A, D, G, H]', '[2, 5, 7, A, ... ]'],
        ];
    }
}
