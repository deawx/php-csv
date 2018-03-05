<?php

namespace PHPCsv\Accessors;

class StringAccessor implements AccessorInterface
{
    use GeneralCsvMethods;

    public function createHandle($value)
    {
        $temporaryHandle = fopen('php://temp', 'rw');
        fwrite($temporaryHandle, $value);
        rewind($temporaryHandle);
        $this->handle = $temporaryHandle;
        return $this;
    }
}