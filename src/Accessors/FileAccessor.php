<?php

namespace PHPCsv\Accessors;

class FileAccessor implements AccessorInterface
{
    use GeneralCsvMethods;

    public function createHandle($value)
    {
        $this->handle = is_resource($value) ? $value : fopen($value, 'r');
        return $this;
    }
}
