# Respect\Stringifier

[![Build Status](https://img.shields.io/github/actions/workflow/status/Respect/Stringifier/continuous-integration.yml?branch=master&style=flat-square)](https://github.com/Respect/Stringifier/actions/workflows/continuous-integration.yml)
[![Code Coverage](https://img.shields.io/codecov/c/github/Respect/Stringifier?style=flat-square)](https://codecov.io/gh/Respect/Stringifier)
[![Latest Stable Version](https://img.shields.io/packagist/v/respect/stringifier.svg?style=flat-square)](https://packagist.org/packages/respect/stringifier)
[![Total Downloads](https://img.shields.io/packagist/dt/respect/stringifier.svg?style=flat-square)](https://packagist.org/packages/respect/stringifier)
[![License](https://img.shields.io/packagist/l/respect/stringifier.svg?style=flat-square)](https://packagist.org/packages/respect/stringifier)

Converts any PHP value into a string.

## Installation

Package is available on [Packagist](https://packagist.org/packages/respect/stringifier), you can install it
using [Composer](http://getcomposer.org).

```bash
composer require respect/stringifier
```

This library requires PHP >= 8.3.

## IMPORTANT: Be careful with version 2.0

If you are using version 2.0, please be aware of security concerns related to information leakage.

1. **Class/Interface/Enum Detection**: Version 2.0 would automatically detect and format strings that matched internal class, interface, or enum names. This could expose your application's internal architecture.
2. **Callable String Detection**: Version 2.0 would interpret strings and arrays as callables by default, potentially exposing sensitive data. .

If you're not stringifiying strings coming from and end-user, you're not at risk. However, later versions changed this to a "secure-by-default" approach, assuming that strings may come from untrusted sources.

## Usage

Below a quick guide of how to use the library.

### Using as a function

```php
echo Respect\Stringifier\stringify($value);
```

### Using as an object

```php
use Respect\Stringifier\HandlerStringifier;

$stringifier = HandlerStringifier::create();

echo $stringifier->stringify($value);
```

### Examples

```php
use function Respect\Stringifier\stringify;

echo stringify('string') . PHP_EOL;
// "string"

echo stringify(implode(PHP_EOL, ['Multi-line', 'string'])) . PHP_EOL;
// "Multi-line\nstring"

echo stringify(1) . PHP_EOL;
// 1

echo stringify(0.5) . PHP_EOL;
// 0.5

echo stringify(true) . PHP_EOL;
// `true`

echo stringify(false) . PHP_EOL;
// `false`

echo stringify(null) . PHP_EOL;
// `null`

echo stringify(INF) . PHP_EOL;
// `INF`

echo stringify(-INF) . PHP_EOL;
// `-INF`

echo stringify(acos(8)) . PHP_EOL;
// `NaN`

echo stringify([1, 2, 3]) . PHP_EOL;
// `[1, 2, 3]`

echo stringify(['foo' => true, 'bar' => 42, 'baz' => ['qux' => INF, 'quux' => null]]) . PHP_EOL;
// `["foo": true, "bar": 42, "baz": ["qux": INF, "quux": null]]`

echo stringify(tmpfile()) . PHP_EOL;
// `resource <stream>`

echo stringify(new WithProperties()) . PHP_EOL;
// `WithProperties { +$publicProperty=true #$protectedProperty=42 -$privateProperty="something" }`

echo stringify(new WithUninitializedProperties()) . PHP_EOL;
// `WithUninitializedProperties { +$uninitializedProperty=*uninitialized* }`

echo stringify(new class { public int $property = 42; }) . PHP_EOL;
// `class { +$property=42 }`

echo stringify(new class extends WithProperties { }) . PHP_EOL;
// `WithProperties@anonymous { +$publicProperty=true #$protectedProperty=42 }`

echo stringify(fn(int $foo): string => '') . PHP_EOL;
// `Closure { fn(int $foo): string }`

echo stringify(new DateTime()) . PHP_EOL;
// `DateTime { 2023-04-21T11:29:03+00:00 }`

echo stringify(new DateTimeImmutable()) . PHP_EOL;
// `DateTimeImmutable { 2023-04-21T11:29:03+00:00 }`

echo stringify(new Fiber('strlen')) . PHP_EOL;
// `Fiber { strlen(string $string): int }`

echo stringify((static fn(int $number) => yield $number)(7)) . PHP_EOL;
// `Generator { current() => 7 }`

echo stringify(new ConcreteIterator()) . PHP_EOL;
// `ConcreteIterator { current() => 1 }`

echo stringify(new ConcreteStringable()) . PHP_EOL;
// `ConcreteStringable { __toString() => "This is the return of __toString()" }`

echo stringify(new ConcreteJsonSerializable()) . PHP_EOL;
// `ConcreteJsonSerializable { jsonSerialize() => {"0":1,"1":2,"2":3,"foo":true} }`

echo stringify(new WithDebugInfo()) . PHP_EOL;
// `WithDebugInfo { __debugInfo() => ["info": "This is the return of __debugInfo()"] }`

echo stringify(new ArrayObject([1, 2, 3])) . PHP_EOL;
// `ArrayObject { getArrayCopy() => [1, 2, 3] }`

echo stringify(new RuntimeException()) . PHP_EOL;
// `RuntimeException { in file.php:119 }`

echo stringify(new InvalidArgumentException('This is the exception message')) . PHP_EOL;
// `InvalidArgumentException { "This is the exception message" in file.php:112 }`
```

To see more examples of how to use the library check the [integration tests](tests/integration).

### Custom stringifiers

Stringifier library is extensible, you can create your own stringifiers and handlers. Considering the internal design,
it's best to create an implementation of `Handler`, and then use it to create a `HandlerStringifier`.

```php
use Respect\Stringifier\DumpStringifier;
use Respect\Stringifier\Handler;
use Respect\Stringifier\Handlers\CompositeHandler;
use Respect\Stringifier\HandlerStringifier;
use Respect\Stringifier\Stringify;

$compositeHandler = CompositeHandler::create();
$compositeHandler->prependStringifier(new class implements Handler {
    public function handle(mixed $raw, int $depth): ?string
    {
        if (is_object($raw) && method_exists($raw, 'toString')) {
            return $raw->toString();
        }

        return null;
    }
});

$stringifier = new HandlerStringifier($compositeHandler, new DumpStringifier());

echo $stringifier->stringify(new class {
    public function toString(): string
    {
        return 'Hello, world!';
    }
});
// Hello, world!
```

The `DumpStringifier` is a fallback stringifier that uses `print_r`-like output. You can replace it with any other.
