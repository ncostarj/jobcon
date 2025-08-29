<?php

namespace App\Console\Commands;

use App\Models\CnabReader;
use Illuminate\Console\Command;

class LeitorCnab extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnab:ler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Faz a leitura de um cnab por linha com regexp.';

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
        $this->info("Iniciando a leitura do cnab");
        
        $cnab = (new CnabReader())
                    ->setCaminhoArquivo(public_path('assets/cnabs/marfrig_cnab_20240202.rem'))
                    ->read();

        dump($cnab);

        $this->info("Leitura do cnab completa");

        // return 0;
    }
}
