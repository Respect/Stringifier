--FILE--
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$variable = new WithInvoke();

outputMultiple(
    'chr',
    $variable,
    [new WithMethods(), 'publicMethod'],
    'WithMethods::publicStaticMethod',
    ['WithMethods', 'publicStaticMethod'],
    static fn(int $foo): bool => (bool) $foo,
    static function (int $foo) use ($variable): string {
        return $variable::class;
    }
);
?>
--EXPECT--
`chr(int $codepoint): string`
`WithInvoke->__invoke(int $parameter = 0): never`
`WithMethods->publicMethod(Iterator&Countable $parameter): ?static`
`WithMethods::publicStaticMethod(int|float $parameter): void`
`WithMethods::publicStaticMethod(int|float $parameter): void`
`function (int $foo): bool`
`function (int $foo) use ($variable): string`