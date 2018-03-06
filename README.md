# What is PHPCsv
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
use PHPCsv\Reader;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // Outputs list contained in CSV column
    var_dump($rows);
}
```

`$rows` has simple accessor that enables direct access to each column.
```php
<?php
use PHPCsv\Reader;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // Output column value of CSV
    var_dump((string) $rows->columnName);
}
```

`$rows` implements Iterator for the use of an iterator.

```php
<?php
use PHPCsv\Reader;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    foreach ($rows as $row) {
        // Outputs value of each column
        var_dump($row);
    }
}
```

Will change accessor, pass values to Reader class various formats with CSV and parseable it.
PHPCsv provide `FileAccessor`, `StringAccessor` and `FastStringAccessor` only.

```php
<?php
use PHPCsv\Reader;
use PHPCsv\Accessors\StringAccessor;

$text = <<<EOF
column,column2,column3,column4
R1Value,R1Value2,R1Value3,R1Value4
R2Value,R2Value2,R2Value3,R2Value4
R3Value,R3Value2,R3Value3,R3Value4
EOF;

$iterator = new Reader($text, [
    'accessor' => StringAccessor::class,
]);
foreach ($iterator as $key => $rows) {
    // Output column value of CSV
    var_dump((string) $rows->columnName);
}
```