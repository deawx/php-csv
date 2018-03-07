<?php

namespace PHPCsv\Result;

class Row
{
    private $value = null;
    private $config = [];

    public function __construct($row, array $config = [])
    {
        $defaults = [
        ];
        $this->config = $config + $defaults;
        $this->value = $row;
    }

    public function setConfigures(array $config = [])
    {
        $this->config = $config + $this->config;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        $value = $this->value;
        if (
            !$this->config['output.supports.multibyte'] ||
            !$this->config['output.convert_encoding']
        ) {
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
