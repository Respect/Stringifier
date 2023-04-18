--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new InvalidArgumentException());
output(new InvalidArgumentException('My exception message'));
?>
--EXPECTF--
`InvalidArgumentException { in %s:%d }`
`InvalidArgumentException { "My exception message" in %s:%d }`
