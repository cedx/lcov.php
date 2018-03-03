# Installation

## Requirements
Before installing **LCOV Reports for PHP**, you need to make sure you have [PHP](https://secure.php.net)
and [Composer](https://getcomposer.org), the PHP package manager, up and running.

!!! warning
    LCOV Reports for PHP requires PHP >= **7.1.0**.
    
You can verify if you're already good to go with the following commands:

```shell
php --version
# PHP 7.1.11-0ubuntu0.17.10.1 (cli) (built: Nov  1 2017 16:30:52) ( NTS )

composer --version
# Composer version 1.6.2 2018-01-05 15:28:41
```

!!! info
    If you plan to play with the package sources, you will also need
    [Phing](https://www.phing.info) and [Material for MkDocs](https://squidfunk.github.io/mkdocs-material).

## Installing with Composer package manager

### 1. Install it
From a command prompt, run:

```shell
composer require cedx/lcov
```

### 2. Import it
Now in your [PHP](https://secure.php.net) code, you can use:

```php
<?php
use Lcov\{
  BranchCoverage, BranchData,
  FunctionCoverage, FunctionData,
  LineCoverage, LineData,
  Record, Report, Token
};
```
