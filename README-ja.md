# PHPCsvについて
PHPは元来、CSVを読み込むときに文字列エンコーディング等を含めて、煩わしい前処理が必要となります。
例えば、 `ini_set('auto_detect_line_endings', 1)` などを設定しないと一部のマシンでは動きせん。
他にも、エンコーディングの都合で日本語環境であればCSVは必然とSJIS-winの形式で読み込まれ、開発者を苦悩させます。
PHPCsvではそういった煩わしいものを考えないでCSVを扱うために作られたプロジェクトです。
日本語などのマルチバイトに対応しており、CSVを簡易的にパースすることができます。
また、多言語にも対応できるようにもなっています。

# Get started
PHPCsvは下記のようにして簡単に利用可能です

```
<?php
use PHPCsv;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // CSVのカラムに格納されている一覧を出力します。
    var_dump($rows);
}
```

また、`$rows` は簡易的なアクセッサを持っており、各カラムにダイレクトにアクセスが可能です。
```
<?php
use PHPCsv;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    // CSVのカラムの値を出力します。
    var_dump((string) $rows->columnName);
}
```

`$rows` はIteratorを継承しており、イテレータとして扱うことも可能です。

```
<?php
use PHPCsv;

$iterator = new Reader('/path/to/filename');
foreach ($iterator as $key => $rows) {
    foreach ($rows as $row) {
        // 各カラムの値を出力する
        var_dump($row);
    }
}
```
