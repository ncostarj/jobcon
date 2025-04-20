<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Csv\CedentesReader;

class GerarModeloImportacaoCedente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cedentes:gerar_modelo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera o modelo de cedentes em csv com o export de producao';

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
		$this->info("Início");

		$cedentesReader = new CedentesReader(public_path("assets/csv/cedentes-da-operacao.csv"));
		$cedentesReader
			->openFile()
			->read();

		$this->info("Fim");
    }
}
