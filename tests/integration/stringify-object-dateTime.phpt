--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

outputMultiple(
    new DateTime('2017-12-31T23:59:59+00:00'),
    new DateTimeImmutable('2017-12-31T23:59:59+00:00'),
);
?>
--EXPECT--
`[date-time] (DateTime: "2017-12-31T23:59:59+00:00")`
`[date-time] (DateTimeImmutable: "2017-12-31T23:59:59+00:00")`
