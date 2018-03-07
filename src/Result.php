<?php

namespace PHPCsv;
use PHPCsv\Result\Row;

class Result implements \Iterator
{
    private $rows = [];
    private $config = [];
    private $position = 0;

    public function __construct(array $rows = [], array $config = [])
    {
        $defaults = [
            'output.delimiter' => ',',
            'output.escape' => '\\',
            'output.enclosure' => '"',
            'output.apply.enclosure' => true,
            'output.date_with_equals' => false,
            'output.supports.multibyte' => true,
            'output.line_ending.detect' => true,
            'output.convert_encoding' => true,
            'output.charset.detect' => [
                'ASCII',
                'JIS',
                'UTF-8',
                'CP51932',
                'eucJP-win',
                'SJIS-win',
            ],
            'output.to_encoding' => 'SJIS-win',
        ];
        $this->config = $config + $defaults;
        $this->rows = array_map(function($row) {
            return new Row($row, $this->config);
        }, $rows);
    }

    public function setConfigures(array $config)
    {
        $this->config = $config + $this->config;
        return $this;
    }

    public function combine()
    {
        $value = [];
        foreach ($this->rows as $row) {
            $row = str_replace($this->config['output.enclosure'], ($this->config['output.escape'] === null ? '' : $this->config['output.escape']) . $this->config['output.enclosure'], $row);
            if (!$this->config['output.apply.enclosure'] || ctype_digit($row)) {
                $value[] = $row;
            } elseif (strtotime($row) !== false) {
                $value[] = ($this->config['output.date_with_equals'] ? '=' : '') . $this->config['output.enclosure'] . $row .  $this->config['output.enclosure'];
            } else {
                $value[] = $this->config['output.enclosure'] . $row . $this->config['output.enclosure'];
            }
        }
        return implode($this->config['output.delimiter'], $value);
    }

    public function getColumn()
    {
        return array_keys($this->rows);
    }

    public function toArray()
    {
        $values = [];
        foreach ($this->rows as $name => $row) {
            $values[$name] = (string) $row;
        }
        return $values;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function get($name)
    {
        return isset($this->rows[$name]) ? $this->rows[$name] : null;
    }

    public function current()
    {
        return current(array_slice($this->rows, $this->position, 1));
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return array_keys(array_slice($this->rows, $this->position, 1))[0];
    }

    public function valid()
    {
        $value = current(array_slice($this->rows, $this->position, 1));
        return $value !== false;
    }

    public function rewind()
    {
        reset($this->rows);
    }
}
