<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Stringifiers;

use DateTimeInterface;
use Respect\Stringifier\Quoters\CodeQuoter;
use Respect\Stringifier\Stringifier;

final class ClusterStringifier implements Stringifier
{
    private const MAXIMUM_DEPTH = 3;
    private const MAXIMUM_NUMBER_OF_ITEMS = 5;
    private const MAXIMUM_NUMBER_OF_PROPERTIES = self::MAXIMUM_NUMBER_OF_ITEMS;

    /**
     * @var Stringifier[]
     */
    private array $stringifiers = [];

    /**
     * @param Stringifier[] ...$stringifiers
     */
    public function __construct(Stringifier ...$stringifiers)
    {
        $this->setStringifiers($stringifiers);
    }

    public static function createDefault(): self
    {
        $quoter = new CodeQuoter();

        $stringifier = new self();

        $jsonEncodableStringifier = new JsonEncodableStringifier();
        $arrayStringifier = new ArrayStringifier(
            $stringifier,
            $quoter,
            self::MAXIMUM_DEPTH,
            self::MAXIMUM_NUMBER_OF_ITEMS
        );

        $stringifier->setStringifiers([
            new IteratorObjectStringifier($stringifier, $quoter),
            new DateTimeStringifier($quoter, DateTimeInterface::ATOM),
            new ThrowableObjectStringifier($jsonEncodableStringifier, $quoter),
            new StringableObjectStringifier($jsonEncodableStringifier, $quoter),
            new JsonSerializableObjectStringifier($jsonEncodableStringifier, $quoter),
            new ObjectWithDebugInfoStringifier($arrayStringifier, $quoter),
            new ObjectStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_PROPERTIES),
            $arrayStringifier,
            new InfiniteNumberStringifier($quoter),
            new NotANumberStringifier($quoter),
            new ResourceStringifier($quoter),
            new BoolStringifier($quoter),
            new NullStringifier($quoter),
            $jsonEncodableStringifier,
        ]);

        return $stringifier;
    }

    /**
     * @param Stringifier[] $stringifiers
     */
    public function setStringifiers(array $stringifiers): void
    {
        $this->stringifiers = [];

        foreach ($stringifiers as $stringifier) {
            $this->addStringifier($stringifier);
        }
    }

    public function addStringifier(Stringifier $stringifier): void
    {
        $this->stringifiers[] = $stringifier;
    }

    public function stringify(mixed $raw, int $depth): ?string
    {
        foreach ($this->stringifiers as $stringifier) {
            $string = $stringifier->stringify($raw, $depth);
            if ($string === null) {
                continue;
            }

            return $string;
        }

        return null;
    }
}
