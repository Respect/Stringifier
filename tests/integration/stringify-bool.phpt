--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

echo implode(
    PHP_EOL,
    [
        stringify(true),
        stringify(false),
    ]
);
?>
--EXPECT--
`TRUE`
`FALSE`
