--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output([
    1,
    null,
    [
        1.000,
        [
            tmpfile(),
            [1],
            [],
        ],
    ],
    false,
    new stdClass(),
    42,
]);
?>
--EXPECT--
`[1, null, [1.0, [resource <stream>, ..., []]], false, stdClass {}, ...]`
