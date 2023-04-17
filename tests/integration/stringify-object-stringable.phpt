--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new ConcreteStringable());
?>
--EXPECT--
`ConcreteStringable { __toString() => "This is the return of __toString()" }`
