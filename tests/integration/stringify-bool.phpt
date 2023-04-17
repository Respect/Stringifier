--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    true,
    false,
);
?>
--EXPECT--
`TRUE`
`FALSE`
