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

use Respect\Stringifier\Stringifiers\JsonParsableStringifier;
use PHPUnit\Framework\TestCase;
use function tmpfile;

/**
 * @covers \Respect\Stringifier\Stringifiers\JsonParsableStringifier
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class JsonParsableStringifierTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnNullWhenNotPossibleToConvertToAJsonParsableString(): void
    {
        $raw = tmpfile();
        $depth = 0;

        $jsonSerializableStringifier = new JsonParsableStringifier();

        self::assertNull($jsonSerializableStringifier->stringify($raw, $depth));
    }

    /**
     * @test
     */
    public function shouldConvertRawValueToAJsonParsableString(): void
    {
        $raw = 'É uma \' " string';
        $depth = 0;

        $expected = '"É uma \' \" string"';

        $jsonSerializableStringifier = new JsonParsableStringifier();

        self::assertSame($expected, $jsonSerializableStringifier->stringify($raw, $depth));
    }
}
