<?php

namespace PHPCsv;
use PHPCsv\Accessors\AccessorInterface;
use PHPCsv\Accessors\FileAccessor;

class Reader implements \Iterator
{

    private $config = [];
    private $column = [];
    private $position = 0;

    /**
     * @var AccessorInterface|null
     */
    private $accessor = null;

    public function __construct($value, array $config = [])
    {
        $defaults = [
            'read_column' => true,
            'column_prefix' => null,
            'accessor' => FileAccessor::class,
            'fields.auto.fill' => true,
        ];
        $this->config = $config + $defaults;
        $this->accessor = new $this->config['accessor']($this->config);
        $this->accessor
            ->createHandle($value);
    }

    public function setConfigures(array $config = [])
    {
        $this->config = $config + $this->config;
        return $config;
    }

    public function setAccessor(AccessorInterface $accessor)
    {
        $this->accessor = $accessor;
        return $this;
    }

    public function getAccessor()
    {
        return $this->accessor;
    }

    public function rewind()
    {
        $this->accessor->rewind();
        if ($this->config['read_column']) {
            $this->column = $this->accessor->read();
        }
        $this->position = 0;
    }

    public function next()
    {
        $this->position++;
    }

    public function current()
    {
        $column = $this->column;
        $values = $this->accessor->read();
        if ($this->config['fields.auto.fill'] === true) {
            // Apply auto-fill
            $max = max(count($column), count($values));
            $column = array_merge($column, array_keys(array_fill(0, $max - count($column), null)));
            $values = array_merge($values, array_fill(0, $max - count($values), null));
        }
        if ($this->config['read_column'] === true) {
            if ($this->config['column_prefix'] !== null) {
                $column = array_map(function($column) {
                    return $this->config['column_prefix'] . $column;
                }, $column);
            }
            $rows = array_combine($column, $values);
        } else {
            $rows = $values;
        }
        return new Result($rows, $this->config);
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return $this->accessor->readable();
    }

    public function all()
    {
        $stacks = [];
        foreach ($this as $row) {
            $stacks[] = $row;
        }
        return $stacks;
    }
}
