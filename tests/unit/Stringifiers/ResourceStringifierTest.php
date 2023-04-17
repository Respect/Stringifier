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
use Respect\Stringifier\Stringifiers\ResourceStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;

use function tmpfile;

#[CoversClass(ResourceStringifier::class)]
final class ResourceStringifierTest extends TestCase
{
    private const DEPTH = 0;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotOfTypeResource(): void
    {
        $sut = new ResourceStringifier(new FakeQuoter());

        self::assertNull($sut->stringify(true, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsOfTypeResource(): void
    {
        $quoter = new FakeQuoter();

        $sut = new ResourceStringifier($quoter);

        $actual = $sut->stringify(tmpfile(), self::DEPTH);
        $expected = $quoter->quote('resource <stream>', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
