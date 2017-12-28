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

use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\StringableObjectStringifier;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Respect\Stringifier\Stringifiers\StringableObjectStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class StringableObjectStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotConvertToStringWhenValueIsNotAnObject(): void
    {
        $raw = 'not-an-object';
        $depth = 1;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $stringableObjectStringifier = new StringableObjectStringifier($stringifierMock);

        self::assertNull($stringableObjectStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldNotConvertToStringWhenValueIsANonStringableObject(): void
    {
        $raw = new stdClass();
        $depth = 1;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $stringableObjectStringifier = new StringableObjectStringifier($stringifierMock);

        self::assertNull($stringableObjectStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertToStringWhenValueIsAnStringableObject(): void
    {
        $raw = new StringableObject();
        $depth = 0;

        $expectedValue = StringableObject::STRING_VALUE;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with(StringableObject::STRING_VALUE, $depth)
            ->willReturn($expectedValue);

        $stringableObjectStringifier = new StringableObjectStringifier($stringifierMock);

        self::assertSame($expectedValue, $stringableObjectStringifier->stringify($raw, $depth));
    }
}

final class StringableObject
{
    public const STRING_VALUE = 'String';

    public function __toString()
    {
        return self::STRING_VALUE;
    }
}
