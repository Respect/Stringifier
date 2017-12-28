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

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\TraversableStringifier;

/**
 * @covers \Respect\Stringifier\Stringifiers\TraversableStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class TraversableStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConvertToStringWhenValueIsTraversable(): void
    {
        $raw = new ArrayIterator([1, 2, 3]);
        $depth = 0;

        $stringifiedData = '-stringified-';

        $expectedValue = '[traversable] (ArrayIterator: '.$stringifiedData.')';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with($raw->getArrayCopy(), $depth + 1)
            ->willReturn($stringifiedData);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $traversableStringifier = new TraversableStringifier($stringifierMock, $quoterMock);

        self::assertSame($expectedValue, $traversableStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotTraversable(): void
    {
        $raw = [1, 2, 3, 4];
        $depth = 123;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $traversableStringifier = new TraversableStringifier($stringifierMock, $quoterMock);

        self::assertNull($traversableStringifier->stringify($raw, $depth));
    }
}
