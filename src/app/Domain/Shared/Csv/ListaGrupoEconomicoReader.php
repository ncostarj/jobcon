<?php

namespace App\Models\Csv;

class ListaGrupoEconomicoReader extends CsvReaderBase {

    protected $pathFile;

    public function __construct($pathFile) {
        $this->pathFile = $pathFile;
    }

    public function read() {
        $contador = 0;
        $header = [];
        // dd($this->arquivo);
        while ($row = fgetcsv($this->arquivo, 1000, ";")) {
            if($contador == 0){
                $header = $row;
                $contador++;
                continue;
            }
            $linha = array_combine($header, $row);
            $qtdCharInscricao = strlen($linha['inscricao_sacado']);
            if($qtdCharInscricao < 14) {
                dump(implode(';',$linha));
            }
        }
        fclose($this->arquivo);
    }
}