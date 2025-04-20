<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CriarCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:criar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Criar csv a partir de um arquivo base';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // dd();
        $this->info('Início');
        
        // $csv = "data;data_pagamento;titulo_uid;valor_pago;cod_ocorrencia;descricao\n";

        // Storage::delete(['public/donegal2.txt']);

        $handle = fopen(public_path('donegal.txt'), "r");

        $header = fgetcsv($handle, 1000, ";");

        $csv = mb_convert_encoding(implode(";", $header) . "\n",'ISO-8859-1');

        $contador = 0;

                    // $linha[] = array_combine($header, $row);
            // // $csv .=;
            // 24 => 'codigo da operacao',
            // 25 => 'descricao da operacao',
            
            // Log::info(implode(";",$row));
            // 1, 2, 7, 76 -> 02
            // Liquidação com Financeiro
            // 63 -> 10


            // dump("antes {$row[24]}|{$row[25]}");

            // if($row[24] == "1" || $row[24] == '2' || $row[24] == '7' || $row[24] == '76') {
            //     dump('ola123');
            //     $row[24] = '02';
            //     $row[25] = 'Liquidação com financeiro';
            // }

            // if($row[24] == '63') {
            //     $row[24] = "10";
            // }

        while ($row = fgetcsv($handle, 1000, ";")) {


            // dump("{$row[24]}|{$row[25]}");

            // if(trim($row[24]) == "1" || trim($row[24]) == '2' || trim($row[24]) == '7' || trim($row[24]) == '76') {
            //     $row[24] = trim('02');
            //     $row[25] = trim('Liquidação com Financeiro');
            // }

            // if(trim($row[24]) == trim('63')) {
            //     $row[24] = trim('10');
            //     $row[25] = trim('Baixa Por Renegociaçao');
            // }

            // if(trim(Str::contains($row[26], 'sem financeiro'))) {
            //     $row[26] = trim('Baixa por inversão de parcela (sem financeiro)');
            // }

            if(trim($row[24]) == "1" || trim($row[24]) == '2') {
                $row[24] = trim('03');
                $row[25] = trim('Liquidação com Financeiro');
            }
            if(trim($row[24]) == trim('53')) {
                $row[24] = trim('23');
                $row[25] = trim('Baixas por pagamento direto ao cedente');
            }

            $csv .= mb_convert_encoding(implode(';', $row)."\n",'ISO-8859-1');

            // if($contador == 100) {
            //     break;
            // }

            $contador++;

            $this->info("Linha: {$contador}|{$row[0]}|{$row[24]}|{$row[25]}|{$row[26]}");
        }

        // Log::info($csv);

        // Storage::put('public/donegal2.txt', $csv);

        fclose($handle);


        // $headers = ['Produto', 'Preço'];

        // $dados = [
        //     [
        //         'produto' => 'Notebook',
        //         'preco' => 3587,
        //     ],
        //     [
        //         'produto' => 'Celular',
        //         'preco' => 2643,
        //     ],
        //     [
        //         'produto' => 'TV',
        //         'preco' => 5876,
        //     ],
        //     [
        //         'produto' => 'Fone',
        //         'preco' => 432,
        //     ],
        // ];

        // $arquivo = fopen('file.csv', 'w');

        // fputcsv($arquivo , $headers);

        // foreach ($dados as $chave => $valor) {
        //     $produto[$chave]  = $valor['produto'];
        //     $preco[$chave] = $valor['preco'];
        // }
        // // SORT_ASC para ordem crescente
        // array_multisort($preco, SORT_ASC, $dados);

        // foreach ($dados as $linha) {
        //     fputcsv($arquivo, $linha);
        // }
        // fclose($arquivo);

        $this->info('Fim');
    }
}
