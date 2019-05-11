--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo implode(
    PHP_EOL,
    [
        stringify('A'),
        stringify('"B"'),
        stringify('\'C\''),
        stringify('É um test'),
        stringify('ру́сский'),
    ]
);
?>
--EXPECT--
"A"
"\"B\""
"'C'"
"É um test"
"ру́сский"
