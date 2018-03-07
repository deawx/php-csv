<?php

namespace PHPCsv\Result;

class Row
{
    private $value = null;
    private $config = [];

    public function __construct($row, array $config = [])
    {
        $this->config = $config;
        $this->value = $row;
    }

    public function setConfig(array $config = [])
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
        return mb_convert_encoding(
            $value,
            $this->config['output.to_encoding'],
            implode(',', $this->config['output.charset.detect'])
        );
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }

}
