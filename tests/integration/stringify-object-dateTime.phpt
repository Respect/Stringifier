--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use function Respect\Stringifier\stringify;

$dateTime = new DateTime('2017-12-31T23:59:59+00:00');
$dateTimeImmutable = DateTimeImmutable::createFromMutable($dateTime);

echo implode(
    PHP_EOL,
    [
        stringify($dateTime),
        stringify($dateTimeImmutable),
    ]
);
?>
--EXPECT--
`[date-time] (DateTime: "2017-12-31T23:59:59+00:00")`
`[date-time] (DateTimeImmutable: "2017-12-31T23:59:59+00:00")`
