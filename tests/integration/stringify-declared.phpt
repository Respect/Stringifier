--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Stringifier\Helpers\ObjectHelper;

outputMultiple(
    Traversable::class,
    ArrayIterator::class,
    BasicEnumeration::class,
    ObjectHelper::class,
);
?>
--EXPECT--
`Traversable`
`ArrayIterator`
`BasicEnumeration`
`Respect\Stringifier\Helpers\ObjectHelper`
