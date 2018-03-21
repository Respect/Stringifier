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

namespace Respect\Stringifier\Test\Stringifiers;

use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifiers\ResourceStringifier;
use function tmpfile;

/**
 * @covers \Respect\Stringifier\Stringifiers\ResourceStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ResourceStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotAResource(): void
    {
        $raw = true;
        $depth = 0;

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $resourceStringifier = new ResourceStringifier($quoterMock);

        self::assertNull($resourceStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsNotAResource(): void
    {
        $raw = tmpfile();
        $depth = 0;

        $expected = '[resource] (stream)';

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $resourceStringifier = new ResourceStringifier($quoterMock);

        self::assertSame($expected, $resourceStringifier->stringify($raw, $depth));
    }
}
