This package provides integration of the industry standard `symfony/console` package and enables you to easily add commands across multiple configurations.  Commands can then be accessed/executed through the `bin/hiraeth` application which will be copied to the `bin` folder your application at install time.  Each configured command will be constructed via the dependency injector and will therefore can undergo constructor injection of its own dependencies and will additionally have any setter injection via applicable interfaces for which there is a provider.

## Installation

```
composer require hiraeth/console
```

## Configuration

The `console.jin` file will be copied to your application's `config` directory and provides an example of how to register commands with the console.

In addition to this primary file, the `[console]` section is globally recognize, so it can be added to any configuration file in the system to add additional commands.  This is useful if you have some commands that are specific to some plugin functionality like a CMS or blog feature that has its own configuration.

```ini
[console]

; Add symfony/console compatible commands to the list below and they will be executable via the `bin/hiraeth`
; application runner.

commands = [
;	"Acme\\Foo\\BarCommand"
]
```

For more information about how to create `symfony/console` commands, check out [their documentation](http://symfony.com/doc/current/console.html).
