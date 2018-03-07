<?php

namespace PHPCsv\Accessors;

trait GeneralCsvMethods
{
    private $config = [];
    private $handle = null;
    private $column = null;

    public function __construct(array $config) {
        $defaults = [
            'reader.delimiter' => ',',
            'reader.escape' => '\\',
            'reader.enclosure' => '"',
            'reader.locale' => 'ja-jp',
            'reader.from_encoding' => 'SJIS-win',
            'reader.supports.multibyte' => true,
            'reader.line_ending.detect' => true,
            'reader.encoding.detect' => [
                'ASCII',
                'JIS',
                'UTF-8',
                'CP51932',
                'eucJP-win',
                'SJIS-win',
            ],
        ];
        $this->config = $config + $defaults;
    }

    public function read()
    {
        $defaultLineEncodingDetect = ini_get('auto_detect_line_endings');
        if ($this->config['reader.line_ending.detect'] == true) {
            ini_set('auto_detect_line_endings', 1);
        }

        $defaultLocale = null;
        if ($this->config['reader.supports.multibyte'] == true) {
            $defaultLocale = \Locale::getDefault();
            \Locale::setDefault($this->config['reader.locale'] . '.' . $this->config['reader.from_encoding']);
        }

        $line = fgetcsv(
            $this->handle,
            null,
            $this->config['reader.delimiter'],
            $this->config['reader.enclosure'],
            $this->config['reader.escape']
        );

        if ($this->config['reader.line_ending.detect'] == true) {
            ini_set('auto_detect_line_endings', $defaultLineEncodingDetect);
        }
        if ($this->config['reader.supports.multibyte'] == true) {
            \Locale::setDefault($defaultLocale);
        }

        if ($this->config['reader.supports.multibyte'] == true) {
            $line = array_map(function($row) {
                return mb_convert_encoding(
                    $row,
                    mb_internal_encoding(),
                    mb_detect_encoding(
                        $row,
                        implode(',', $this->config['reader.encoding.detect']),
                        true
                    )
                );
            }, $line);
        }

        return $line;
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
        $this->config = $config + $this->config;
        return $this;
    }


    public function readable()
    {
        return !feof($this->handle);
    }

    public function rewind()
    {
        rewind($this->handle);
        return $this;
    }

}
