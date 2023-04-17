--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(tmpfile());
?>
--EXPECT--
`[resource] (stream)`
