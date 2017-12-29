--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo stringify([
    1,
    null,
    [
        1.000,
        [
            tmpfile(),
            [
                1
            ],
            [
            ]
        ]
    ],
    false,
    new stdClass(),
    42
]);
?>
--EXPECT--
{ 1, NULL, { 1.0, { [resource] (stream), ..., { } } }, FALSE, [object] (stdClass: { }),  ...  }
