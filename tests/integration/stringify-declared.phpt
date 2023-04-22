--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    Traversable::class,
    ArrayIterator::class,
    BasicEnumeration::class,
    Respect\Stringifier\Helpers\ObjectHelper::class,
);
?>
--EXPECT--
`Traversable`
`ArrayIterator`
`BasicEnumeration`
`Respect\Stringifier\Helpers\ObjectHelper`