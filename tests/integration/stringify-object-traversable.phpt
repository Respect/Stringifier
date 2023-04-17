--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new Respect\Stringifier\Test\MyTraversable());
?>
--EXPECT--
`[traversable] (Respect\Stringifier\Test\MyTraversable: [1, 2, 3])`
