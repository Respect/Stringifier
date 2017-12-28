--FILE--
<?php
require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

$dateTime = DateTime::createFromFormat('Y-m-d\TH:i:sP', '2017-12-31T23:59:59+00:00');

echo stringify($dateTime);
?>
--EXPECT--
"2017-12-31T23:59:59+00:00"
