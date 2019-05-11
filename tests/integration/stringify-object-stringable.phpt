--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Respect\Stringifier\Test\MyStringable;
use function Respect\Stringifier\stringify;

echo stringify(new MyStringable());
?>
--EXPECT--
"Respect\\Stringifier\\Test\\MyStringable"
