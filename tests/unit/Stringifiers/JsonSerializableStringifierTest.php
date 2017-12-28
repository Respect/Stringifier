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

use JsonSerializable;
use Respect\Stringifier\Quoter;
use Respect\Stringifier\Stringifier;
use Respect\Stringifier\Stringifiers\JsonSerializableStringifier;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Respect\Stringifier\Stringifiers\JsonSerializableStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class JsonSerializableStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldConvertToStringWhenRawValueIsAJsonSerializableObject(): void
    {
        $raw = new JsonSerializableObject();
        $depth = 0;

        $stringifiedData = '-stringified-';

        $expectedValue = '[json-serializable] ('.JsonSerializableObject::class.': '.$stringifiedData.')';

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->once())
            ->method('stringify')
            ->with(JsonSerializableObject::JSON_VALUE, $depth + 1)
            ->willReturn($stringifiedData);

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->once())
            ->method('quote')
            ->with($expectedValue, $depth)
            ->willReturn($expectedValue);

        $jsonSerializableStringifier = new JsonSerializableStringifier($stringifierMock, $quoterMock);

        self::assertSame($expectedValue, $jsonSerializableStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldNotConvertToStringWhenRawValueIsNotTraversable(): void
    {
        $raw = new stdClass();
        $depth = 1;

        $stringifierMock = $this->createMock(Stringifier::class);
        $stringifierMock
            ->expects($this->never())
            ->method('stringify');

        $quoterMock = $this->createMock(Quoter::class);
        $quoterMock
            ->expects($this->never())
            ->method('quote');

        $jsonSerializableStringifier = new JsonSerializableStringifier($stringifierMock, $quoterMock);

        self::assertNull($jsonSerializableStringifier->stringify($raw, $depth));
    }
}

final class JsonSerializableObject implements JsonSerializable
{
    public const JSON_VALUE = [1, true, 'foo', null];

    public function jsonSerialize(): array
    {
        return self::JSON_VALUE;
    }
}
