--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

final class MyObject implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return [1, 2, 3];
    }
}

echo stringify(new MyObject());
?>
--EXPECT--
`[json-serializable] (MyObject: { 1, 2, 3 })`
