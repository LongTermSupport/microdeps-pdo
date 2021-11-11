# MicroDeps/PDO

MicroDeps are all about very small pieces of code that do a single small thing well

This MicroDep is for PDO

Simply enough it provides a factory for creating PDO instances

Currently this supports MySQL and SQLite Databases, more can be added easily enough, pull requests gratefully received!

## Installing

```
composer require lts/microdeps-pdo
```

## Configuration

You have the choice of defining configuration values as constants using `define`, or you can add these to the 
`$_ENV` super global, perhaps using something like https://github.com/vlucas/phpdotenv

You can see all the possible configuration keys defined in [./src/Config.php](./src/Config.php)

The specific keys you need to define will depend on the database type you want to use, for example:

### MySQL
```
DB_SERVER_USERNAME="foo"
DB_SERVER_PASSWORD="foo"
DB_DATABASE="foo"
```

### SQLite
```
DB_TYPE=sqlite
DB_SQLITE_PATH=/path/to/file
```

## Developing

### PHP QA CI

This package is using PHP QA CI for the quality assurance and continuous integration. You can read more about that here:
https://github.com/LongTermSupport/php-qa-ci

#### To run QA process locally

To run the full QA process locally, simply run:

```bash
./bin/qa
```

## Long Term Support

This package was brought to you by Long Term Support LTD, a company run and founded by Joseph Edmonds

You can get in touch with Joseph at https://joseph.edmonds.contact/

Check out Joseph's recent book [The Art of Modern PHP 8](https://joseph.edmonds.contact/#book)