<?php

namespace App\Models\Csv;

abstract class CsvBase {

    protected $pathFile;
    protected $arquivo;
    protected $header;

    public function __construct($pathFile)
    {
        $this->pathFile = $pathFile;
    }

    public function openFile(string $mode = 'r') {
        $this->arquivo = fopen($this->pathFile, $mode);
        return $this;
    }

    abstract public function read();
	abstract public function write();
}
