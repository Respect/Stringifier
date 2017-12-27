<?php

/*
 * This file is part of Respect/Stringifier.
 *
 * (c) Henrique Moody <henriquemoody@gmail.com>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Quoters;

use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoters\CodeQuoter;

/**
 * @covers \Respect\Stringifier\Quoters\CodeQuoter
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class CodeQuoterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldQuoteStringWhenInZeroDepth(): void
    {
        $quoter = new CodeQuoter();

        $expectedValue = '`code`';
        $actualValue = $quoter->quote('code', 0);

        self::assertSame($expectedValue, $actualValue);
    }

    /**
     * @test
     */
    public function shouldNotQuoteStringDepthIsBiggerThanZero(): void
    {
        $quoter = new CodeQuoter();

        $expectedValue = 'code';
        $actualValue = $quoter->quote('code', 1);

        self::assertSame($expectedValue, $actualValue);
    }
}
