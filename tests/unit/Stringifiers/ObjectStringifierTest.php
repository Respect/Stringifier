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
use ReflectionObject;
use Respect\Stringifier\Stringifiers\ObjectStringifier;
use Respect\Stringifier\Test\Double\FakeQuoter;
use Respect\Stringifier\Test\Double\FakeStringifier;
use SplStack;
use stdClass;
use WithProperties;
use WithUninitializedProperties;

use function sprintf;

#[CoversClass(ObjectStringifier::class)]
final class ObjectStringifierTest extends TestCase
{
    private const DEPTH = 0;
    private const MAXIMUM_DEPTH = 4;
    private const MAXIMUM_NUMBER_OF_PROPERTIES = 5;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnObject(): void
    {
        $sut = new ObjectStringifier(
            new FakeStringifier(),
            new FakeQuoter(),
            self::MAXIMUM_DEPTH,
            self::MAXIMUM_NUMBER_OF_PROPERTIES
        );

        self::assertNull($sut->stringify(true, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithoutProperties(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote('stdClass {}', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithProperties(): void
    {
        $raw = new WithProperties();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $relection = new ReflectionObject($raw);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                '%s { +$publicProperty=%s #$protectedProperty=%s -$privateProperty=%s }',
                $relection->getName(),
                $stringifier->stringify($relection->getProperty('publicProperty')->getValue($raw), self::DEPTH + 1),
                $stringifier->stringify($relection->getProperty('protectedProperty')->getValue($raw), self::DEPTH + 1),
                $stringifier->stringify($relection->getProperty('privateProperty')->getValue($raw), self::DEPTH + 1),
            ),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithUninitializedProperties(): void
    {
        $raw = new WithUninitializedProperties();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $relection = new ReflectionObject($raw);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                '%s { +$uninitializedProperty=%s }',
                $relection->getName(),
                '*uninitialized*',
            ),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithPropertiesThatAreObjects(): void
    {
        $raw = new stdClass();
        $raw->a = 1;
        $raw->b = new stdClass();
        $raw->b->c = true;
        $raw->b->d = new stdClass();
        $raw->b->d->e = [];
        $raw->b->d->f = new stdClass();

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'stdClass { +$a=%s +$b=%s }',
                $stringifier->stringify($raw->a, self::DEPTH + 1),
                $quoter->quote(
                    sprintf(
                        'stdClass { +$c=%s +$d=%s }',
                        $stringifier->stringify($raw->b->c, self::DEPTH + 2),
                        $quoter->quote(
                            sprintf(
                                'stdClass { +$e=%s +$f=%s }',
                                $stringifier->stringify($raw->b->d->e, self::DEPTH + 3),
                                $quoter->quote('stdClass {}', self::DEPTH + 3)
                            ),
                            self::DEPTH + 2
                        )
                    ),
                    self::DEPTH + 1
                )
            ),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenItReachesTheMaximumDepth(): void
    {
        $raw = new stdClass();
        $raw->property = $raw;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $maximumDepth = self::DEPTH + 3;

        $sut = new ObjectStringifier($stringifier, $quoter, $maximumDepth, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'stdClass { +$property=%s }',
                $quoter->quote(
                    sprintf(
                        'stdClass { +$property=%s }',
                        $quoter->quote(
                            sprintf(
                                'stdClass { +$property=%s }',
                                $quoter->quote('stdClass { ... }', $maximumDepth)
                            ),
                            self::DEPTH + 2
                        )
                    ),
                    self::DEPTH + 1
                )
            ),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenItReachesLimitOfItems(): void
    {
        $raw = new stdClass();
        $raw->a = 1;
        $raw->b = 2;
        $raw->c = 3;
        $raw->d = 4;

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $itemsLimit = 3;

        $sut = new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, $itemsLimit);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'stdClass { +$a=%s +$b=%s +$c=%s ... }',
                $stringifier->stringify($raw->a, self::DEPTH + 1),
                $stringifier->stringify($raw->b, self::DEPTH + 1),
                $stringifier->stringify($raw->c, self::DEPTH + 1),
            ),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnAnonymousClass(): void
    {
        $raw = new class {
            public int $foo = 1;
        };

        $stringifier = new FakeStringifier();
        $quoter = new FakeQuoter();

        $sut = new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'class { +$foo=%s }',
                $stringifier->stringify($raw->foo, self::DEPTH + 1)
            ),
            self::DEPTH
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnAnonymousClassExtendingAnotherClass(): void
    {
        $raw = new class extends SplStack {
        };

        $quoter = new FakeQuoter();

        $sut = new ObjectStringifier(
            new FakeStringifier(),
            $quoter,
            self::MAXIMUM_DEPTH,
            self::MAXIMUM_NUMBER_OF_PROPERTIES
        );

        $actual = $sut->stringify($raw, self::DEPTH);
        $expected = $quoter->quote('SplStack@anonymous {}', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
