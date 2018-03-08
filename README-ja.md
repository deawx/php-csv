# What is PHPCsv
PHPは元来、CSVを読み込むときに文字列エンコーディング等を含めて、煩わしい前処理が必要となります。
例えば、 `ini_set('auto_detect_line_endings', 1)` などを設定しないと一部のマシンでは動きせん。
他にも、エンコーディングの都合で日本語環境であればCSVは必然とSJIS-winの形式で読み込まれ、開発者を苦悩させます。
PHPCsvではそういった煩わしいものを考えないでCSVを扱うために作られたプロジェクトです。
日本語などのマルチバイトに対応しており、CSVを簡易的にパースすることができます。
また、多言語にも対応できるようにもなっています。

# Installation
下記のコマンドを実行することにより開始することができます。

```bash
composer require memory-agape/php-csv
```

# Quick start
PHPCsvは下記のようにして簡単に利用可能です

```php
<?php
use PHPCsv\Reader;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // CSVのカラムに格納されている一覧を出力します。
    var_dump($rows);
}
```

また、`$rows` は簡易的なアクセッサを持っており、各カラムにダイレクトにアクセスが可能です。
```php
<?php
use PHPCsv\Reader;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // CSVのカラムの値を出力します。
    var_dump((string) $rows->columnName);
}
```

`$rows` はIteratorを継承しており、イテレータとして扱うことも可能です。

```php
<?php
use PHPCsv\Reader;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    foreach ($rows as $row) {
        // 各カラムの値を出力する
        var_dump($row);
    }
}
```

アクセッサを変更することにより、様々なフォーマットをReaderに渡してCSVとしてパースすることができます。
デフォルトで提供しているアクセッサは `FileAccessor`, `StringAccessor` と `FastStringAccessor` のみです。
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
    // CSVのカラムの値を出力します。
    var_dump((string) $rows->columnName);
}
```

# Supported options

`Reader` クラスの第二パラメータに設定することによりオプションを設定可能です。 

|オプション名        |型         |デフォルト         |説明        |
|:-------------:|:-------------:|:-------------:|:-------------:|
|read_column|bool|true|一行目をフィールド名として扱うかどうかを設定します。|
|column_prefix|string|null|フィールド名のプリフィックスを設定します。|
|accessor|string|FileAccessor::class|CSVを読み込むアクセッサを設定できます|
|fields.auto.fill|bool|true|フィールドとレコードの件数が合わない場合、自動的に埋めるかを設定できます。|
|reader.delimiter|string|,|読み込み時のCSVの区切り文字を設定します。|
|reader.escape|string|\ |読み込み時のCSVのメタ文字のエスケープを設定します。|
|reader.enclosure|string|"|CSVのレコードの文字列の範囲を設定します。|
|reader.locale|string|ja-jp|CSVを読み込むための言語設定を指定します。|
|reader.from_encoding|string|SJIS-win|読み込むCSVのエンコーディングを設定します。|
|reader.supports.multibyte|bool|true|読み込むCSVをマルチバイトとして扱うかを設定します。|
|reader.line_ending.detect|bool|true|改行を自動判定するかどうかを設定します。|
|reader.encoding.detect|array|['ASCII', 'JIS', 'UTF-8', 'CP51932', 'eucJP-win', 'SJIS-win']|読み込むCSVの文字エンコーディングを配列から判定します。|
|output.delimiter|string|,|出力時のCSVの区切り文字を設定します。|
|output.escape|string|\ |出力時のCSVのメタ文字のエスケープを設定します。|
|output.enclosure|string|"|CSVのレコードの文字列の範囲を設定します。|
|output.apply.enclosure|bool|true|出力時に文字列の範囲を設定する文字を埋め込むかどうかを設定します。|
|output.date_with_equals|bool|false|出力時に値が日付の場合戦闘にイコールを入れるかどうかを設定します。|
|output.supports.multibyte|bool|true|読み込むCSVをマルチバイトとして扱うかを設定します。|
|output.line_ending.detect|bool|true|改行を自動判定するかどうかを設定します。|
|output.convert_encoding|bool|true|出力時に文字をエンコードするかどうかを指定します。|
|output.encoding.detect|array|['ASCII', 'JIS', 'UTF-8', 'CP51932', 'eucJP-win', 'SJIS-win']|出力時のCSVの文字エンコーディングを配列から判定します。|
|output.to_encoding|string|SJIS-win|出力時の文字エンコーディングを指定します。|
