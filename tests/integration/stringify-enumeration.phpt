--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    BasicEnumeration::FOO,
    BackedEnumeration::QUX,
);
?>
--EXPECT--
`BasicEnumeration::FOO`
`BackedEnumeration::QUX`
