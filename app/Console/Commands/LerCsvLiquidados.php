<?php

namespace App\Console\Commands;

use App\Models\Csv\LiquidadosReader;
use Illuminate\Console\Command;

class LerCsvLiquidados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:ler_liquidados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command que lê os relatorios de liquidados em formato csv.';

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

        $csvReader = new LiquidadosReader(public_path("assets/csv/liquidado_analitico_fidc_bv_credito_de_veiculos_20231001_20240701_01072024182752.csv"));
        $csvReader
            ->openFile()
            ->read();

        $this->info('Fim');
    }
}
