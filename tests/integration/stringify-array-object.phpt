--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    new ArrayObject([]),
    new ArrayObject([1, 2, 3]),
);
?>
--EXPECT--
`ArrayObject { getArrayCopy() => [] }`
`ArrayObject { getArrayCopy() => [1, 2, 3] }`
