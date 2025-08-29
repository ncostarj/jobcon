<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LerTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'txt:ler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $arquivoCsv = public_path("assets/txt/124.csv");

        $handleCsv = fopen($arquivoCsv, "r");
        
        $titulos_uid = [];
        while ($row = fgetcsv($handleCsv, 1000, ";")) {
            $titulos_uid[] = $row[0];
        }

        fclose($handleCsv);

        $arquivoTxt = public_path("assets/txt/retorno_2024-01-17_010001002334f240117074020-1.ret");

        $handleTxt = fopen($arquivoTxt, "r");
        $linhas = [];
        $contador = 0;
        while ($row = fgets($handleTxt)) {
            foreach($titulos_uid as $titulo_uid) {
                if(str_contains($row, $titulo_uid)) {
                    // dump("{$contador}|{$row}");
                    $linhas[] = $row; 
                    $contador++;
                }
            }            
        }
        fclose($handleTxt);

        $arquivoTxt2 = public_path("assets/txt/retorno_122_titulos.ret");
        $handleTxt2 = fopen($arquivoTxt2, "w");
        foreach($linhas as $key => $linha) {
            $seq = str_pad(($key+1),6, '0', STR_PAD_LEFT);
            // dump($linha);
            $linha_sequencial = preg_replace("/\s+([0-9]{6})\r\n/", $seq, $linha);
            dump($linha_sequencial);
            // dump($seq);
            fwrite($handleTxt2, $linha);
        }
        fclose($handleTxt2);

        
        $this->info('Fim');        
        return 0;
    }
}
