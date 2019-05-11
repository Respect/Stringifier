--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Stringifier\Test\MyJsonSerializable;
use function Respect\Stringifier\stringify;

echo stringify(new MyJsonSerializable());
?>
--EXPECT--
`[json-serializable] (Respect\Stringifier\Test\MyJsonSerializable: { 1, 2, 3 })`
