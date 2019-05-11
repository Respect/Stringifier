--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Stringifier\Test\MyObject;
use function Respect\Stringifier\stringify;

echo stringify(new MyObject());
?>
--EXPECT--
`[object] (Respect\Stringifier\Test\MyObject: { "foo": TRUE })`
