<?php

namespace App\Console\Commands;

use App\Models\Csv\ListaGrupoEconomicoReader;
use Illuminate\Console\Command;

class LerCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:ler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ler CSV';

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
        $this->info('Início');

        $csvReader = new ListaGrupoEconomicoReader(public_path("assets/csv/lista_grupo_economico_3.csv"));
        $csvReader
            ->openFile()
            ->read();
        
        $this->info('Fim');
    }



        // $arquivo = "base_em_aberto_fechamento_vert_5_companhia_securitizadora_de_creditos_financeiros_20231025_27102023193030.csv";
        // $arquivo = "base-aberto-fechamento-vert-5-9fdec588-343a-4d78-9f22-a269f456e3ce.csv"; // 2023-10-19
        // $arquivo = "base-aberto-fechamento-vert-5-b3444bf2-5575-4d0c-80d6-9b7a22b35aa3.csv"; //2023-10-20


        // $totalAquisicao = 0;
        // $totalValorFace = 0;
        // $totalValorFaceCorrigido = 0;
        // $totalValorFaceAposBaixasParciais = 0;
        // $totalValorNominalPotencial = 0;
        // $totalValorPresente = 0;
        // $startTime = microtime(true);
        
        // $contador = 0;
        // $arquivo = "lista_grupo_economico_3.csv";
        // $handle = fopen(public_path("assets/csv/{$arquivo}"), "r");

        // while ($row = fgetcsv($handle, 1000, ";")) {

        //     if($contador == 0) {
        //         continue;
        //     }
        //     // dump($contador, $row);
        //     dd($row);


        //     // 25 => "Valor de Aquisicao"
        //     // 26 => "Valor Presente"
        //     // 27 => "Valor de Face"
        //     // 28 => "Valor de Face Corrigido"
        //     // 29 => "Valor de Face Apos Baixas Parciais"
        //     // 30 => "Valor nominal potencial"
          
        //     // // dump($row);
        //     // if($contador != 0) {
        //     //     // dump("Linha: {$contador}|{$row[25]}|{$row[26]}|{$row[27]}|{$row[28]}|{$row[29]}|{$row[30]}");
        //     //     // $totalAquisicao += (float) strval(str_replace(',','.',str_replace('.','',$row[25])));
        //     //     // $totalValorFace += (float) strval(str_replace(',','.',str_replace('.','',$row[27])));
        //     //     // $totalValorFaceCorrigido += (float) strval(str_replace(',','.',str_replace('.','',$row[28])));
        //     //     // $totalValorFaceAposBaixasParciais += (float) strval(str_replace(',','.',str_replace('.','',$row[29])));
        //     //     // $totalValorNominalPotencial += (float) strval(str_replace(',','.',str_replace('.','',$row[30])));
        //     //     $totalValorPresente += (float) strval(str_replace(',','.',str_replace('.','',$row[26])));
        //     // }

        //     // if($contador % 500000 == 0) {
        //     //     $this->info("Andamento" , $contador);
        //     // }

        //     // $contador++;

        //     // dd($row);

        // }

        // fclose($handle);

        // totalAquisicao|totalValorPresente|totalValorFace|totalValorFaceCorrigido|totalValorAposBaixaParcial|totalValorNominalPotencial
        // 10933543|738328361|862658026|1540808624|1540808483|1540808483|0
        // 10933543|743731324.09042|868071590.53901|1545341665.2706|1545341524.2206|1545341524.2206|0
        
        // $totalAquisicao = number_format($totalAquisicao,2,',','.');
        // $totalValorPresente = number_format($totalValorPresente, 2,',','.');
        // $totalValorFace = number_format($totalValorFace,2,',','.');
        // $totalValorFaceCorrigido = number_format($totalValorFaceCorrigido,2,',','.');
        // $totalValorFaceAposBaixasParciais = number_format($totalValorFaceAposBaixasParciais,2,',','.');
        // $totalValorNominalPotencial = number_format($totalValorNominalPotencial,2,',','.');

        // $this->info("{$contador}|{$totalAquisicao}|{$totalValorPresente}|{$totalValorFace}|{$totalValorFaceCorrigido}|{$totalValorFaceAposBaixasParciais}|{$totalValorNominalPotencial}");
        // $diferenca = (microtime(true) - $startTime)/60;
        // $this->info("{$contador}|{$totalValorPresente}");
        // $this->info("Tempo: $diferenca");

    public function info($text, $andamento = "")
    {
        if (!empty($andamento)) {
            $text = "[$andamento] " . $text;
        }
        dump("[" . Date("H:i:s") . "] " . $text);
    }
}