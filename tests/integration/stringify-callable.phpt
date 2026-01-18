--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$variable = true;

outputMultiple(
    fn(int $foo): bool => (bool) $foo,
    static function (int $foo) use ($variable): string {
        return $variable::class;
    },
);
?>
--EXPECT--
`Closure { fn(int $foo): bool }`
`Closure { static fn(int $foo) use ($variable): string }`
