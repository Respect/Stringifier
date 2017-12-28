--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

final class MyObject
{
    public $foo = true;
    protected $bar = .3;
    private $baz = [1, 2, 3];
}

echo stringify(new MyObject());
?>
--EXPECT--
`[object] (MyObject: { "foo": TRUE })`
