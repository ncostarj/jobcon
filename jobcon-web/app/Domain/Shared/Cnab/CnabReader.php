<?php

namespace App\Models;


class CnabReader {

    protected $path;
    protected $cnab;
    protected $cnabData = [];

    public function __construct() {
        
    }

    public function setCaminhoArquivo(string $path) {
        $this->path = $path;
        return $this;
    }

    public function getColunas(array $colunas, string $linha) {
        $dados = [];
        

        $dados[] = [ $colunas['nome'] => substr($linha, $colunas['posInicial'], $colunas['posFinal']) ];
            // $re = "/([a-zA-Z0-9]+\{{$coluna['posInicial']},{$coluna['posFinal']}\})/";
            // dump($re);
            // preg_match($re, $linha, $matches);
            // dump($matches);

        dump($dados);
        return $dados;
    }

    public function getFirstLine() {
        return exec("head {$this->path} -n 1");
    }

    public function getLastLine() {
        return exec("tail {$this->path} -n 1");
    }

    public function read() {
        $cnabData = [];
        $streamCnab = fopen($this->path, 'r');

        $firstLine = $this->getFirstLine();
        $lastLine = $this->getLastLine();

        $numeroLinha = 0;
        while(!feof($streamCnab)) {
            // $numLine = stream_get_line($streamCnab);
            $linha = fgets($streamCnab);
            if($numeroLinha != 0) {
                $dadosLinha = $this->getColunas([ 
                    'nome' => 'inscricao_sacado', 
                    'posInicial' => 221,
                    'posFinal'=> 234
                ], $linha);    
                dd($linha, $dadosLinha);
            }
            // $cnabData[] = $this->getColunas([
            //     'nome' => 'inscricao_sacado', 'posInicial' => 221,'posFinal'=> 234
            // ], $linha);
            // dump("{$numeroLinha}|{$cnabData[$numeroLinha]}");
            $numeroLinha++;
        }
    
        fclose($streamCnab);

        return $cnabData;
    }
}