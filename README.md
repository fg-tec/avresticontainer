# AvrestiContainer
[![PHP Composer](https://github.com/fg-tec/avresticontainer/actions/workflows/php.yml/badge.svg)](https://github.com/fg-tec/avresticontainer/actions/workflows/php.yml)

AvrestiContainer is a lightweight and flexible dependency injection (DI) container for PHP. It allows you to manage class dependencies and perform dependency injection in a simple and intuitive way.

## Features

- **Simple Binding**: Bind classes and interfaces to concrete implementations.
- **Singletons**: Register and resolve singletons easily.
- **Method Injection**: Inject dependencies into class methods.
- **Aliases**: Create aliases for bindings to simplify dependency resolution.
- **Dependency Resolution**: Automatically resolve and inject class dependencies.
- **Configurable Definitions**: Add multiple definitions in a single call using `addDefinitions`.
- **Autowiring**: Automatically resolve class dependencies without explicit bindings.

## Installation

You can install AvrestiContainer via Composer:

```bash
composer require avresticontainer/avresticontainer
```

## Usage

### Basic Usage

Here's a basic example of how to use AvrestiContainer:

```php
require __DIR__ . '/vendor/autoload.php';

use Avresticontainer\Container;

$container = Container::getInstance();

$container->bind('config', function() {
    return new class {
        public function get($key) {
            return $key;
        }
    };
});

$config = $container->make('config');
echo $config->get('example_key');  // Outputs: example_key
```

### Using Singletons

Register and resolve singletons:

```php
$container->singleton('config', function() {
    return new class {
        public $value = 'singleton_value';
    };
});

$config1 = $container->make('config');
$config2 = $container->make('config');

$config1->value = 'new_value';

echo $config2->value;  // Outputs: new_value
```

### Adding Definitions

You can add multiple definitions using `addDefinitions`:

```php
$container->addDefinitions([
    'config' => function() {
        return new Config(['db_host' => '127.0.0.1', 'db_name' => 'my_database']);
    },
    'database' => function($container) {
        return new DatabaseConnection($container->make('config'));
    },
    'simple.value' => 'This is a simple value'
]);

$config = $container->make('config');
$database = $container->make('database');
$simpleValue = $container->make('simple.value');
```

### Using Aliases

Create and use aliases for bindings:

```php
$container->bind('original', function() {
    return new class {
        public function getValue() {
            return 'original value';
        }
    };
});

$container->alias('original', 'alias');

$original = $container->make('alias');
echo $original->getValue();  // Outputs: original value
```

### Autowiring

AvrestiContainer can automatically resolve class dependencies:

```php
class DependencyA {
    public function getValue() {
        return 'valueA';
    }
}

class DependencyB {
    protected $dependencyA;

    public function __construct(DependencyA $dependencyA) {
        $this->dependencyA = $dependencyA;
    }

    public function getDependencyValue() {
        return $this->dependencyA->getValue();
    }
}

$container->bind(DependencyA::class);
$container->bind(DependencyB::class);

$dependencyB = $container->make(DependencyB::class);
echo $dependencyB->getDependencyValue();  // Outputs: valueA
```

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributing

We welcome contributions! Please feel free to submit a pull request or open an issue on GitHub.

## Acknowledgements

Inspired by various PHP DI containers, AvrestiContainer aims to be a simple yet powerful tool for managing dependencies in PHP applications.

## Contact

For any questions or inquiries, please create an issue on [GitHub](https://github.com/fg-tec/avresticontainer/issues).
