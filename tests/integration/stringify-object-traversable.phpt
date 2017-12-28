--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

final class MyObject implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        return new ArrayIterator([1, 2, 3]);
    }
}

echo stringify(new MyObject());
?>
--EXPECT--
`[traversable] (MyObject: { 1, 2, 3 })`
