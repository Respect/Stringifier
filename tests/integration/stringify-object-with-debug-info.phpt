--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new WithDebugInfo());
?>
--EXPECT--
`WithDebugInfo { __debugInfo() => ["info": "This is the return of __debugInfo()"] }`