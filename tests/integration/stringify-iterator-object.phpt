--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    new ConcreteIterator(),
    new ArrayIterator([4, 5, 6]),
    (static fn(int $number) => yield $number)(7),
);
?>
--EXPECT--
`ConcreteIterator { current() => 1 }`
`ArrayIterator { current() => 4 }`
`Generator { current() => 7 }`
