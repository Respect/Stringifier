--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    new stdClass(),
    new WithProperties(),
    new WithUninitializedProperties(),
);
?>
--EXPECT--
`stdClass {}`
`WithProperties { +$publicProperty=true #$protectedProperty=42 -$privateProperty="something" }`
`WithUninitializedProperties { +$uninitializedProperty=*uninitialized* }`
