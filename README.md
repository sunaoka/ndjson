# NDJSON Reader/Writer for PHP

[![Latest](https://poser.pugx.org/sunaoka/ndjson/v)](https://packagist.org/packages/sunaoka/ndjson)
[![License](https://poser.pugx.org/sunaoka/ndjson/license)](https://packagist.org/packages/sunaoka/ndjson)
[![PHP](https://img.shields.io/packagist/php-v/sunaoka/ndjson)](composer.json)
[![Test](https://github.com/sunaoka/ndjson/actions/workflows/test.yml/badge.svg)](https://github.com/sunaoka/ndjson/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/sunaoka/ndjson/branch/develop/graph/badge.svg)](https://codecov.io/gh/sunaoka/ndjson)

---

A PHP library to read and write [NDJSON](https://github.com/ndjson) (Newline Delimited JSON).

Read and write one line at a time to execute with low memory usage.

For better performance, you can also read and write on multiple lines.

## Installation

```bash
composer require sunaoka/ndjson
```

## Usage

### Read

#### Example NDJSON

```json
{"test": "001"}
{"test": "002"}
{"test": "003"}
{"test": "004"}
{"test": "005"}
```

#### Read one line at a time

```php
use Sunaoka\Ndjson\NDJSON;

$ndjson = new NDJSON('/path/to/file.ndjson');

while ($json = $ndjson->readline()) {
    var_dump($json);
}
```

```
array(1) {
  ["test"]=>
  string(3) "001"
}
array(1) {
  ["test"]=>
  string(3) "002"
}
array(1) {
  ["test"]=>
  string(3) "003"
}
array(1) {
  ["test"]=>
  string(3) "004"
}
array(1) {
  ["test"]=>
  string(3) "005"
}
```

#### Read 3 lines at a time

```php
use Sunaoka\Ndjson\NDJSON;

$ndjson = new NDJSON('/path/to/file.ndjson');

foreach ($ndjson->readlines(3) as $jsons) {
    var_dump($jsons);
}
```

```
array(3) {
  [0]=>
  array(1) {
    ["test"]=>
    string(3) "001"
  }
  [1]=>
  array(1) {
    ["test"]=>
    string(3) "002"
  }
  [2]=>
  array(1) {
    ["test"]=>
    string(3) "003"
  }
}
array(2) {
  [0]=>
  array(1) {
    ["test"]=>
    string(3) "004"
  }
  [1]=>
  array(1) {
    ["test"]=>
    string(3) "005"
  }
}

```

### Write

#### Write one line at a time

```php
use Sunaoka\Ndjson\NDJSON;

$ndjson = new NDJSON('/path/to/file.ndjson');
$ndjson->writeline(['test' => '001']);
$ndjson->writeline(['test' => '002']);
```

```
{"test": "001"}
{"test": "002"}
```

#### Write multiple lines at a time

```php
use Sunaoka\Ndjson\NDJSON;

$ndjson = new NDJSON('/path/to/file.ndjson');
$ndjson->writelines([['test' => '001'], ['test' => '002']]);
```

```
{"test": "001"}
{"test": "002"}
```
