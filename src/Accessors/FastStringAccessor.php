<?php

namespace PHPCsv\Accessors;

class FastStringAccessor implements AccessorInterface
{
    use GeneralCsvMethods;

    public function createHandle($value)
    {
        $temporaryHandle = fopen('php://memory', 'rw');
        fwrite($temporaryHandle, $value);
        rewind($temporaryHandle);
        $this->handle = $temporaryHandle;
        return $this;
    }
}
