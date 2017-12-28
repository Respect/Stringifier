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

use function implode;
use const PHP_EOL;
use Respect\Stringifier\Stringifiers\PhpParsableStringifier;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Respect\Stringifier\Stringifiers\PhpParsableStringifier
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class PhpParsableStringifierTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider validValuesProvider
     *
     * @param mixed $raw
     * @param string $expected
     */
    public function shouldConvertValueToPhpParsable($raw, string $expected): void
    {
        $stringifier = new PhpParsableStringifier();

        self::assertSame($expected, $stringifier->stringify($raw, 0));
    }

    public function validValuesProvider(): array
    {
        return [
            [true, 'true'],
            [false, 'false'],
            [42, '42'],
            [1.2, '1.2'],
            [.2, '0.2'],
            ['string', '\'string\''],
            [null, 'NULL'],
            [[1], implode(PHP_EOL, ['array (', '  0 => 1,', ')'])],
            [new stdClass(), implode(PHP_EOL, ['stdClass::__set_state(array(', '))'])],
        ];
    }

    /**
     * @test
     */
    public function shouldReturnNullWhenNotPossibleToConvert(): void
    {
        $stringifier = new PhpParsableStringifier();

        self::assertNull($stringifier->stringify(tmpfile(), 0));
    }
}
