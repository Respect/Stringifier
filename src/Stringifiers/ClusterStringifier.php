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

        $jsonParsableStringifier = new JsonParsableStringifier();

        $stringifier->setStringifiers([
            new IteratorObjectStringifier($stringifier, $quoter),
            new DateTimeStringifier($quoter, DateTimeInterface::ATOM),
            new ThrowableStringifier($stringifier, $quoter),
            new StringableObjectStringifier($jsonParsableStringifier, $quoter),
            new JsonSerializableObjectStringifier($jsonParsableStringifier, $quoter),
            new ObjectStringifier($stringifier, $quoter),
            new ArrayStringifier($stringifier, $quoter, self::MAXIMUM_DEPTH, self::MAXIMUM_NUMBER_OF_ITEMS),
            new InfiniteStringifier($quoter),
            new NanStringifier($quoter),
            new ResourceStringifier($quoter),
            new BoolStringifier($quoter),
            new NullStringifier($quoter),
            $jsonParsableStringifier,
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
