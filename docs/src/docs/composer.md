## Installing to an Existing Project

The Pesa PHP SDK can be an existing project using Composer.

In your project root:

```sh
$ composer require openpesa/pesa
```

As with the earlier two composer install methods, you can omit to install PHPUnit and its dependencies by adding the `“–no-dev”` argument to the `“composer require”` command.

## Set Up

Copy add API_KEY to your env


### Upgrading

Whenever there is a new release, then from the command line in your project root:

```sh
$ composer update
```

Read the upgrade instructions, and check designated app/Config folders for affected changes.


