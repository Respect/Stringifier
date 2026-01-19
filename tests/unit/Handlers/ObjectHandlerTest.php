<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Test\Unit\Handlers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionObject;
use Respect\Stringifier\Handlers\ObjectHandler;
use Respect\Stringifier\Test\Double\FakeHandler;
use Respect\Stringifier\Test\Double\FakeQuoter;
use SplStack;
use stdClass;
use WithProperties;
use WithUninitializedProperties;

use function assert;
use function sprintf;

#[CoversClass(ObjectHandler::class)]
final class ObjectHandlerTest extends TestCase
{
    private const int DEPTH = 0;
    private const int MAXIMUM_DEPTH = 4;
    private const int MAXIMUM_NUMBER_OF_PROPERTIES = 5;

    #[Test]
    public function itShouldNotStringifyRawValueWhenItIsNotAnObject(): void
    {
        $sut = new ObjectHandler(
            new FakeHandler(),
            new FakeQuoter(),
            self::MAXIMUM_DEPTH,
            self::MAXIMUM_NUMBER_OF_PROPERTIES,
        );

        self::assertNull($sut->handle(true, self::DEPTH));
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithoutProperties(): void
    {
        $raw = new stdClass();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ObjectHandler($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote('stdClass {}', self::DEPTH);

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithProperties(): void
    {
        $raw = new WithProperties();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ObjectHandler($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $relection = new ReflectionObject($raw);
        $parentReflection = $relection->getParentClass();
        assert($parentReflection instanceof ReflectionClass);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                '%s { +$publicProperty=%s #$protectedProperty=%s -$privateProperty=%s }',
                $relection->getName(),
                $stringifier->handle($relection->getProperty('publicProperty')->getValue($raw), self::DEPTH + 1),
                $stringifier->handle($relection->getProperty('protectedProperty')->getValue($raw), self::DEPTH + 1),
                $stringifier->handle(
                    $parentReflection->getProperty('privateProperty')->getValue($raw),
                    self::DEPTH + 1,
                ),
            ),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnObjectWithUninitializedProperties(): void
    {
        $raw = new WithUninitializedProperties();

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ObjectHandler($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $relection = new ReflectionObject($raw);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                '%s { +$uninitializedProperty=%s }',
                $relection->getName(),
                '*uninitialized*',
            ),
            self::DEPTH,
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

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ObjectHandler($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'stdClass { +$a=%s +$b=%s }',
                $stringifier->handle($raw->a, self::DEPTH + 1),
                $quoter->quote(
                    sprintf(
                        'stdClass { +$c=%s +$d=%s }',
                        $stringifier->handle($raw->b->c, self::DEPTH + 2),
                        $quoter->quote(
                            sprintf(
                                'stdClass { +$e=%s +$f=%s }',
                                $stringifier->handle($raw->b->d->e, self::DEPTH + 3),
                                $quoter->quote('stdClass {}', self::DEPTH + 3),
                            ),
                            self::DEPTH + 2,
                        ),
                    ),
                    self::DEPTH + 1,
                ),
            ),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWithPlaceholderWhenItReachesTheMaximumDepth(): void
    {
        $raw = new stdClass();
        $raw->property = $raw;

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $maximumDepth = self::DEPTH + 3;

        $sut = new ObjectHandler($stringifier, $quoter, $maximumDepth, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'stdClass { +$property=%s }',
                $quoter->quote(
                    sprintf(
                        'stdClass { +$property=%s }',
                        $quoter->quote(
                            sprintf(
                                'stdClass { +$property=%s }',
                                $quoter->quote('stdClass { ... }', $maximumDepth),
                            ),
                            self::DEPTH + 2,
                        ),
                    ),
                    self::DEPTH + 1,
                ),
            ),
            self::DEPTH,
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

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $itemsLimit = 3;

        $sut = new ObjectHandler($stringifier, $quoter, self::MAXIMUM_DEPTH, $itemsLimit);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'stdClass { +$a=%s +$b=%s +$c=%s ... }',
                $stringifier->handle($raw->a, self::DEPTH + 1),
                $stringifier->handle($raw->b, self::DEPTH + 1),
                $stringifier->handle($raw->c, self::DEPTH + 1),
            ),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnAnonymousClass(): void
    {
        $raw = new class {
            public int $foo = 1;
        };

        $stringifier = new FakeHandler();
        $quoter = new FakeQuoter();

        $sut = new ObjectHandler($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES);

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote(
            sprintf(
                'class { +$foo=%s }',
                $stringifier->handle($raw->foo, self::DEPTH + 1),
            ),
            self::DEPTH,
        );

        self::assertSame($expected, $actual);
    }

    #[Test]
    public function itShouldStringifyRawValueWhenItIsAnAnonymousClassExtendingAnotherClass(): void
    {
        $raw = new class extends SplStack {
        };

        $quoter = new FakeQuoter();

        $sut = new ObjectHandler(
            new FakeHandler(),
            $quoter,
            self::MAXIMUM_DEPTH,
            self::MAXIMUM_NUMBER_OF_PROPERTIES,
        );

        $actual = $sut->handle($raw, self::DEPTH);
        $expected = $quoter->quote('SplStack@anonymous {}', self::DEPTH);

        self::assertSame($expected, $actual);
    }
}
