--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Stringifier\Test\MyTraversable;
use function Respect\Stringifier\stringify;

echo stringify(new MyTraversable());
?>
--EXPECT--
`[traversable] (Respect\Stringifier\Test\MyTraversable: { 1, 2, 3 })`
