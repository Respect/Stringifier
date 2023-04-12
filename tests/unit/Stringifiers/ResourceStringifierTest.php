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
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifiers\ResourceStringifier;

use function tmpfile;

#[CoversClass(ResourceStringifier::class)]
final class ResourceStringifierTest extends TestCase
{
    #[Test]
    public function shouldNotConvertToStringWhenRawValueIsNotResource(): void
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

    #[Test]
    public function shouldConvertToStringWhenRawValueIsNotResource(): void
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
