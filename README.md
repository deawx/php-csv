# About PHPCsv
PHPCsv is a CSV parser that fully helps read multibyte characters
(e.g. Japanese) with support for internationalization.

PHP functions require annoying process to properly parse out CSV files. Some
machines even need extra configuration, for instance, calling
`ini_set('auto_detect_line_endings', 1)` is necessary beforehand.
Furthermore, developers often struggle with reading CSV files in SJIS-win format
for the sake of text encoding in Japanese environment.

# Get started
PHPCsv can be easily used as follows:

```php
<?php
use PHPCsv;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // Outputs list contained in CSV column
    var_dump($rows);
}
```

`$rows` has simple accessor that enables direct access to each column.
```php
<?php
use PHPCsv;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // Output column value of CSV
    var_dump((string) $rows->columnName);
}
```

`$rows` implements Iterator for the use of an iterator.

```php
<?php
use PHPCsv;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    foreach ($rows as $row) {
        // Outputs value of each column
        var_dump($row);
    }
}
```
