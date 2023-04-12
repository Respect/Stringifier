<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Quoters;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoters\CodeQuoter;

#[CoversClass(CodeQuoter::class)]
final class CodeQuoterTest extends TestCase
{
    #[Test]
    public function shouldQuoteStringWhenInZeroDepth(): void
    {
        $quoter = new CodeQuoter();

        $expectedValue = '`code`';
        $actualValue = $quoter->quote('code', 0);

        self::assertSame($expectedValue, $actualValue);
    }

    #[Test]
    public function shouldNotQuoteStringDepthIsBiggerThanZero(): void
    {
        $quoter = new CodeQuoter();

        $expectedValue = 'code';
        $actualValue = $quoter->quote('code', 1);

        self::assertSame($expectedValue, $actualValue);
    }
}
