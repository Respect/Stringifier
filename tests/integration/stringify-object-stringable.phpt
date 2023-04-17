--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

output(new Respect\Stringifier\Test\MyStringable());
?>
--EXPECT--
"Respect\\Stringifier\\Test\\MyStringable"
