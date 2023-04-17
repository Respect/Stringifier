--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new Respect\Stringifier\Test\MyJsonSerializable());
?>
--EXPECT--
`[json-serializable] (Respect\Stringifier\Test\MyJsonSerializable: { 1, 2, 3 })`
