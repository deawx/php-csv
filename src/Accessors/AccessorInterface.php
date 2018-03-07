<?php

namespace PHPCsv\Accessors;

interface AccessorInterface
{

    public function __construct(array $config);
    public function read();
    public function getHandle();

    /**
     * @param $handle
     * @return $this
     */
    public function setHandle($handle);

    /**
     * @param $value
     * @return $this
     */
    public function createHandle($value);

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config = []);
    public function readable();
    public function rewind();
}