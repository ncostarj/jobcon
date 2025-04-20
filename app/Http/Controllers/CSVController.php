<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CSVController extends Controller
{
    //
    public function index(){ 

        // dd();
        
        // $csv = "data;data_pagamento;titulo_uid;valor_pago;cod_ocorrencia;descricao\n";

        $handle = fopen(public_path('home_equity_historico_liquidados.txt'), "r");

        $header = fgetcsv($handle, 1000, ";");

        $headerText = implode(";", $header);

        Log::info($headerText);

        $contador = 0;

        while ($row = fgetcsv($handle, 1000, ";")) {
            // $linha[] = array_combine($header, $row);
            // // $csv .=;
            // 24 => 'codigo da operacao',
            // 25 => 'descricao da operacao',
            
            // Log::info(implode(";",$row));
            // 1  Liquidação com financeiro
            // 2  Baixa Normal
            // 7  Liquidacao - Compensacao Eletronica
            // 76  Baixa do contrato
            // =
            // 02
            // Liquidação com Financeiro

            Log::info("antes {$row[24]}|{$row[25]}");

            if($row[24] == "1" || $row[24] == '2' || $row[24] == '7' || $row[24] == '76') {
                Log::info('teste123');
                $row[24] = '02';
                $row[25] = 'Liquidação com financeiro';
            }

            Log::info("depois {$row[24]}|{$row[25]}");

            if($contador == 2) {
                break;
            }

            $contador++;
        }

        // Storage::put(public_path('home_equity_historico_liquidados2.txt'), $csv);

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

        dd('teste123');
    }


}
