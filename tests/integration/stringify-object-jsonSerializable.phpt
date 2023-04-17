--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new ConcreteJsonSerializable());
?>
--EXPECT--
`ConcreteJsonSerializable { jsonSerialize() => {"0":1,"1":2,"2":3,"foo":true} }`
