--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo stringify(1);
?>
--EXPECT--
1
