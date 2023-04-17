--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    'A',
    '"B"',
    '\'C\'',
    'É um test',
    'ру́сский',
);
?>
--EXPECT--
"A"
"\"B\""
"'C'"
"É um test"
"ру́сский"
