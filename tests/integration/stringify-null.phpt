--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo stringify(null);
?>
--EXPECT--
`NULL`
