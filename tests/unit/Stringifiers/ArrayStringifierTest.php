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
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\ArrayStringifier;
use function is_array;

final class ArrayStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotAnArray(): void
    {
        $raw = false;
        $depth = 0;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 3, 5);

        self::assertNull($arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldReturnPlaceHolderWhenDepthIsEqualsToMaximumDepth(): void
    {
        $raw = [1, 2, 3];
        $depth = 42;
        $maximumDepth = 42;

        $expected = '...';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, $maximumDepth, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldReturnPlaceHolderWhenDepthIsBiggerThanMaximumDepth(): void
    {
        $raw = [1, 2, 3];
        $depth = 42;
        $maximumDepth = 41;

        $expected = '...';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, $maximumDepth, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldReturnPlaceHolderWhenRawValueIsAnEmptyArray(): void
    {
        $raw = [];
        $depth = 0;

        $expected = '{ }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 3, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldReturnPlaceHolderWhenRawValueIsAnEmptyArrayEvenThenReachedTheMaximumDepth(): void
    {
        $raw = [];
        $depth = 0;

        $expected = '{ }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, $depth, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsAnArray(): void
    {
        $raw = [1, 2, 3];
        $depth = 0;

        $expected = '{ 1, 2, 3 }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->any())
            ->method('stringify')
            ->willReturnCallback(static function ($raw): string {
                return (string) $raw;
            });

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 3, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsNested(): void
    {
        $raw = [1, [2, 3], 4, 5, [6]];
        $depth = 0;

        $expected = '{ 1, nested, 4, 5, nested }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->any())
            ->method('stringify')
            ->willReturnCallback(static function ($raw): string {
                if (is_array($raw)) {
                    return 'nested';
                }

                return (string) $raw;
            });

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 3, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenKeysAreNotSequential(): void
    {
        $raw = [1, 2, 3 => 3];
        $depth = 0;

        $expected = '{ 0: 1, 1: 2, 3: 3 }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->any())
            ->method('stringify')
            ->willReturnCallback(static function ($raw): string {
                return (string) $raw;
            });

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 3, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenKeysAreNotInteger(): void
    {
        $raw = ['foo' => 1, 'bar' => 2];
        $depth = 0;

        $expected = '{ foo: 1, bar: 2 }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->any())
            ->method('stringify')
            ->willReturnCallback(static function ($raw): string {
                return (string) $raw;
            });

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 3, 5);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderWhenLimitOfItemsIsReached(): void
    {
        $itemsLimit = 5;

        $raw = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $depth = 0;

        $expected = '{ 1, 2, 3, 4, 5, ... }';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->any())
            ->method('stringify')
            ->willReturnCallback(static function ($raw): string {
                return (string) $raw;
            });

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expected, $depth)
            ->willReturn($expected);

        $arrayStringifier = new ArrayStringifier($stringifierMock, $quoterMock, 1, $itemsLimit);

        self::assertSame($expected, $arrayStringifier->stringify($raw, $depth));
    }
}
