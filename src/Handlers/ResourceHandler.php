<?php

/*
 * This file is part of Respect/Stringifier.
 * Copyright (c) Henrique Moody <henriquemoody@gmail.com>
 * SPDX-License-Identifier: MIT
 */

declare(strict_types=1);

namespace Respect\Stringifier\Handlers;

use Respect\Stringifier\Handler;
use Respect\Stringifier\Quoter;

use function get_resource_type;
use function is_resource;
use function sprintf;

final class ResourceHandler implements Handler
{
    public function __construct(
        private readonly Quoter $quoter,
    ) {
    }

    public function handle(mixed $raw, int $depth): string|null
    {
        if (!is_resource($raw)) {
            return null;
        }

        return $this->quoter->quote(sprintf('resource <%s>', get_resource_type($raw)), $depth);
    }
}
