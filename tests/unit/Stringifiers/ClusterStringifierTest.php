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

namespace Respect\Stringifier\Test\Stringifiers;

use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\ClusterStringifier;
use stdClass;

/**
 * @covers \Respect\Stringifier\Stringifiers\ClusterStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class ClusterStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnNullWhenNoStringifiersAreDefined(): void
    {
        $raw = new stdClass();
        $depth = 0;

        $clusterStringifier = new ClusterStringifier();

        self::assertNull($clusterStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldUseAllStringifierToStringifyUntilNonFails(): void
    {
        $raw = new stdClass();
        $depth = 0;

        $expected = 'foo';

        $stringifier1 = $this->createMock(Stringifier::class);
        $stringifier1
            ->expects($this->once())
            ->method('stringify')
            ->with($raw, $depth)
            ->willReturn(null);

        $stringifier2 = $this->createMock(Stringifier::class);
        $stringifier2
            ->expects($this->once())
            ->method('stringify')
            ->with($raw, $depth)
            ->willReturn($expected);

        $stringifier3 = $this->createMock(Stringifier::class);
        $stringifier3
            ->expects($this->never())
            ->method('stringify');

        $clusterStringifier = new ClusterStringifier($stringifier1, $stringifier2, $stringifier3);

        self::assertSame($expected, $clusterStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldReturnNullWhenAllStringifierCannotConvertToString(): void
    {
        $raw = new stdClass();
        $depth = 0;

        $stringifier1 = $this->createMock(Stringifier::class);
        $stringifier1
            ->expects($this->once())
            ->method('stringify')
            ->with($raw, $depth)
            ->willReturn(null);

        $stringifier2 = $this->createMock(Stringifier::class);
        $stringifier2
            ->expects($this->once())
            ->method('stringify')
            ->with($raw, $depth)
            ->willReturn(null);

        $stringifier3 = $this->createMock(Stringifier::class);
        $stringifier3
            ->expects($this->once())
            ->method('stringify')
            ->with($raw, $depth)
            ->willReturn(null);

        $clusterStringifier = new ClusterStringifier($stringifier1, $stringifier2, $stringifier3);

        self::assertNull($clusterStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldCreateADefaultClusterStringifier(): void
    {
        self::assertInstanceOf(ClusterStringifier::class, ClusterStringifier::createDefault());
    }
}
