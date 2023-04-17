--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    1.0,
    .3,
    INF,
    -1 * INF,
    acos(8),
);
?>
--EXPECT--
1.0
0.3
`INF`
`-INF`
`NaN`
