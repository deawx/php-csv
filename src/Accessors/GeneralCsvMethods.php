<?php

namespace PHPCsv\Accessors;

trait GeneralCsvMethods
{
    private $config = [];
    private $handle = null;
    private $column = null;

    public function read()
    {
        if ($this->config['reader.supports.multibyte'] == false) {
            return fgetcsv(
                $this->handle,
                null,
                $this->config['reader.delimiter'],
                $this->config['reader.escape']
            );
        }
        $line = fgets($this->handle);
        $withEncodingFrom = mb_detect_encoding(
            $line,
            implode(',', $this->config['reader.charset.detect']),
            true
        );
        return array_map(function($row) {
            return mb_convert_encoding(
                $row,
                $this->config['reader.charset'],
                $this->config['reader.to_encoding']
            );
        }, str_getcsv(
                mb_convert_encoding($line, $this->config['reader.to_encoding'], $withEncodingFrom),
                $this->config['reader.delimiter'],
                $this->config['reader.escape']
            )
        );
    }

    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    public function getHandle()
    {
        return $this->handle;
    }

    public function setConfigures(array $config = [])
    {
        $defaults = [
            'reader.delimiter' => ',',
            'reader.escape' => '\\',
            'reader.supports.multibyte' => true,
            'reader.charset' => 'UTF-8',
            'reader.line_ending.detect' => true,
            'reader.charset.detect' => [
                'ASCII',
                'JIS',
                'UTF-8',
                'CP51932',
                'eucJP-win',
                'SJIS-win',
            ],
            'reader.to_encoding' => 'SJIS-win',
        ];

        $this->config = $config + $defaults;

        if ($this->config['reader.line_ending.detect'] == true) {
            ini_set('auto_detect_line_endings', 1);
        }
        return $this;
    }


    public function readable()
    {
        $value = fread($this->handle, 1);
        if (strlen($value) === 0) {
            return false;
        }
        fseek($this->handle, -1, SEEK_CUR);
        return true;
    }

    public function rewind()
    {
        rewind($this->handle);
        return $this;
    }

}
