Integration for the industry standard `symfony/console` package which enables you to easily add commands across multiple configurations.

## Installation

```
composer require hiraeth/console
```

The `console.jin` configuration will be automatically copied to your `config` directory via [opus](https://github.com/imarc/opus).

The `hiraeth` php command will be automatically copied to your `bin` directory via [opus](https://github.com/imarc/opus).

## Delegates

No delegates are included in this package.

## Providers

No providers are included in this package.


## Configuration

```ini
[console]

; Add symfony/console compatible commands to the list below and they will
; be executable via the `bin/hiraeth` application runner.

commands = [
;	"Acme\\Foo\\BarCommand"
]
```

The `[console]` section is globally recognized, so it can be added to any configuration file in the system to add additional commands.

## Usage

See [the Symfony Console documentation](http://symfony.com/doc/current/console.html) for more information on how to create commands.

Once commands are added to the configuration they can be executed from the application root similar to:

```
php bin/hiraeth <command>
```

Commands will be instantiated via the broker, so they are subject to simple constructor injection, delegation, and providers.
