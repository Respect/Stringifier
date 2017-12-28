--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

final class MyObject
{
    public function __toString(): string
    {
        return __METHOD__;
    }
}

echo stringify(new MyObject());
?>
--EXPECT--
"MyObject::__toString"
