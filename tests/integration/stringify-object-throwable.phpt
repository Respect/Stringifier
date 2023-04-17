--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new InvalidArgumentException());
?>
--EXPECTF--
`[throwable] (InvalidArgumentException: ["message": "", "code": 0, "file": "%s:%d"])`
