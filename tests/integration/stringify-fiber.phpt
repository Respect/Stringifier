--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new Fiber('strlen'));
?>
--EXPECT--
`Fiber { strlen(string $string): int }`
