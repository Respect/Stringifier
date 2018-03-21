--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo implode(
    PHP_EOL,
    [
        stringify(1.0),
        stringify(.3),
        stringify(INF),
        stringify(-1 * INF),
        stringify(acos(8)),
    ]
);
?>
--EXPECT--
1.0
0.3
`INF`
`-INF`
`NaN`
