<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Stringifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\ClusterStringifier;
use stdClass;

#[CoversClass(ClusterStringifier::class)]
final class ClusterStringifierTest extends TestCase
{
    #[Test]
    public function shouldReturnNullWhenNoStringifiersAreDefined(): void
    {
        $raw = new stdClass();
        $depth = 0;

        $clusterStringifier = new ClusterStringifier();

        self::assertNull($clusterStringifier->stringify($raw, $depth));
    }

    #[Test]
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

    #[Test]
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

    #[Test]
    public function shouldCreateDefaultClusterStringifier(): void
    {
        self::assertInstanceOf(ClusterStringifier::class, ClusterStringifier::createDefault());
    }
}
