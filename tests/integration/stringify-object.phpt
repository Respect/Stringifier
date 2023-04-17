--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new Respect\Stringifier\Test\MyObject());
?>
--EXPECT--
`[object] (Respect\Stringifier\Test\MyObject: ["foo": TRUE])`
