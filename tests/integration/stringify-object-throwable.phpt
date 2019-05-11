--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo stringify(new InvalidArgumentException());
?>
--EXPECTF--
`[throwable] (InvalidArgumentException: { "message": "", "code": 0, "file": "%s:%d" })`
