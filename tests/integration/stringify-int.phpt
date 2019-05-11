--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo stringify(1);
?>
--EXPECT--
1
