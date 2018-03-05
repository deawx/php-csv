<?php

namespace PHPCsv\Result;

class Row
{
    private $value = null;
    private $config = [];

    public function __construct($row, array $config = [])
    {
        $this->value = $row;
        $this->config = $config;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        $value = $this->value;
        if ($this->config['output.supports.multibyte'] == false) {
            return $value;
        }
        $withEncodingFrom = mb_detect_encoding(
            $value,
            implode(',', $this->config['output.charset.detect']),
            true
        );
        return mb_convert_encoding(
            $value,
            $this->config['output.to_encoding'],
            $withEncodingFrom
        );
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }

}
