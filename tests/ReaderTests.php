<?php

use PHPCsv\Accessors\StringAccessor;
use PHPCsv\Reader;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        mb_internal_encoding('UTF-8');
    }

    public function testReadWithStringAccessor()
    {
        $test = <<<EOF
test,test2,test3,test4
R1Value,R1Value2,R1Value3,R1Value4
R2Value,R2Value2,R2Value3,R2Value4
R3Value,R3Value2,R3Value3,R3Value4
EOF;

        $iterator = new Reader($test, [
            'accessor' => StringAccessor::class,
        ]);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertContains('R' . ($lineNumber + 1) . 'Value', (string) $rows->test);
            $this->assertContains('R' . ($lineNumber + 1) . 'Value2', (string) $rows->test2);
            $this->assertContains('R' . ($lineNumber + 1) . 'Value3', (string) $rows->test3);
            $this->assertContains('R' . ($lineNumber + 1) . 'Value4', (string) $rows->test4);
            $lineNumber++;
        }

    }

    public function testReadInJapaneseWithStringAccessor()
    {
        $test = <<<EOF
test,test2,test3,test4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
EOF;

        $iterator = new Reader($test, [
            'accessor' => StringAccessor::class,
        ]);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertContains($this->utf82sjis('日本語'), (string) $rows->test);
            $this->assertContains($this->utf82sjis('日本語2'), (string) $rows->test2);
            $this->assertContains($this->utf82sjis('日本語3'), (string) $rows->test3);
            $this->assertContains($this->utf82sjis('日本語4'), (string) $rows->test4);
            $lineNumber++;
        }

    }

    public function testReadInJapaneseWithStringAccessor_EncodingTo()
    {
        $test = <<<EOF
test,test2,test3,test4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
EOF;
        $test = $this->utf82sjis($test);

        $iterator = new Reader($test, [
            'accessor' => StringAccessor::class,
            'output.convert_encoding' => false,
        ]);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertContains('日本語', (string) $rows->test);
            $this->assertContains('日本語2', (string) $rows->test2);
            $this->assertContains('日本語3', (string) $rows->test3);
            $this->assertContains('日本語4', (string) $rows->test4);
            $lineNumber++;
        }

    }

    public function testReadInJapaneseWithStringAccessor_NoEncodingTo()
    {
        $test = <<<EOF
test,test2,test3,test4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
EOF;
        $test = $this->utf82sjis($test);

        $iterator = new Reader($test, [
            'accessor' => StringAccessor::class,
            'output.convert_encoding' => false,
        ]);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertContains('日本語', (string) $rows->test);
            $this->assertContains('日本語2', (string) $rows->test2);
            $this->assertContains('日本語3', (string) $rows->test3);
            $this->assertContains('日本語4', (string) $rows->test4);
            $lineNumber++;
        }

    }

    public function testReadWithFileAccessor()
    {
        $test = <<<EOF
test,test2,test3,test4
R1Value,R1Value2,R1Value3,R1Value4
R2Value,R2Value2,R2Value3,R2Value4
R3Value,R3Value2,R3Value3,R3Value4
EOF;

        file_put_contents($file = stream_get_meta_data(tmpfile())['uri'], $test);
        $iterator = new Reader($file);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertContains('R' . ($lineNumber + 1) . 'Value', (string) $rows->test);
            $this->assertContains('R' . ($lineNumber + 1) . 'Value2', (string) $rows->test2);
            $this->assertContains('R' . ($lineNumber + 1) . 'Value3', (string) $rows->test3);
            $this->assertContains('R' . ($lineNumber + 1) . 'Value4', (string) $rows->test4);
            $lineNumber++;
        }

    }

    public function testReadInJapaneseWithFileAccessor()
    {
        $test = <<<EOF
test,test2,test3,test4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
EOF;

        file_put_contents($file = stream_get_meta_data(tmpfile())['uri'], $test);
        $iterator = new Reader($file);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertContains($this->utf82sjis('日本語'), (string) $rows->test);
            $this->assertContains($this->utf82sjis('日本語2'), (string) $rows->test2);
            $this->assertContains($this->utf82sjis('日本語3'), (string) $rows->test3);
            $this->assertContains($this->utf82sjis('日本語4'), (string) $rows->test4);
            $lineNumber++;
        }

    }

    public function testToArray()
    {
        $test = <<<EOF
test,test2,test3,test4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
日本語,日本語2,日本語3,日本語4
EOF;

        file_put_contents($file = stream_get_meta_data(tmpfile())['uri'], $test);
        $iterator = new Reader($file);
        foreach ($iterator as $key => $rows) {
            $values = $rows->toArray();
            $this->assertArrayHasKey('test', $values);
            $this->assertArrayHasKey('test2', $values);
            $this->assertArrayHasKey('test3', $values);
            $this->assertArrayHasKey('test4', $values);


            $this->assertContains($this->utf82sjis('日本語'), $values['test']);
            $this->assertContains($this->utf82sjis('日本語2'), $values['test2']);
            $this->assertContains($this->utf82sjis('日本語3'), $values['test3']);
            $this->assertContains($this->utf82sjis('日本語4'), $values['test4']);
        }
    }

    public function testReadWithFileAccessor_Prefix()
    {
        $test = <<<EOF
test,test2,test3,test4
R1Value,R1Value2,R1Value3,R1Value4
R2Value,R2Value2,R2Value3,R2Value4
R3Value,R3Value2,R3Value3,R3Value4
EOF;

        file_put_contents($file = stream_get_meta_data(tmpfile())['uri'], $test);
        $iterator = new Reader($file, [
            'column_prefix' => 'prefix_',
        ]);

        $lineNumber = 0;
        foreach ($iterator as $key => $rows) {
            $this->assertNotNull($rows->prefix_test);
            $this->assertNotNull($rows->prefix_test2);
            $this->assertNotNull($rows->prefix_test3);
            $this->assertNotNull($rows->prefix_test4);
            $lineNumber++;
        }

    }

    private function utf82sjis($text)
    {
        return mb_convert_encoding($text, 'SJIS-win', 'UTF-8');
    }
}