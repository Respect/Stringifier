--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo stringify(tmpfile());
?>
--EXPECT--
`[resource] (stream)`
