<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LerFatura extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobcon:ler-fatura';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $file = fopen(storage_path('financeiro/itau/faturas/fatura-20251109.csv'), 'r');
        $contador = 0;
        $header = [];
        // dd($this->arquivo);
        $total = 0;

        $parcelas = [];
        $encerrando = [];
        while ($row = fgetcsv($file, 1000, ",")) {
            if ($contador == 0) {
                $header = array_map(fn($item) => Str::slug($item, '_'), $row);
                $contador++;
                continue;
            }
            [$data, $lancamento, $valor] = array_values(array_combine($header, $row));

            if ($lancamento == 'PAGAMENTO EFETUADO') {
                continue;
            }

            preg_match("/(?<numero_parcela>[0-9]{2})\/(?<qtd_parcelas>[0-9]{2})/", $lancamento, $matches);

            if (!empty($matches)) {

                if ($matches['numero_parcela'] != $matches['qtd_parcelas']) {
                    $faltam = (int) $matches['qtd_parcelas'] - (int) $matches['numero_parcela'];
                    $parcelas[] = [
                        'data' => $data,
                        'lancamento' => $lancamento,
                        'valor' => $valor,
                        'numero_parcela' => $matches['numero_parcela'],
                        'qtd_parcelas' => $matches['qtd_parcelas'],
                        'faltam' => $faltam,
                        'valor_restante' => $valor * $faltam
                    ];
                }

                if ($matches['numero_parcela'] == $matches['qtd_parcelas']) {
                    $encerrando[] = [
                        'data' => $data,
                        'lancamento' => $lancamento,
                        'numero_parcela' => $matches['numero_parcela'],
                        'qtd_parcelas' => $matches['qtd_parcelas']
                    ];
                }
            }

            $total += $valor;
            dump("{$data}|{$lancamento}|{$valor}");
        }

        dump($total);

        dump($parcelas);
        dump($encerrando);
        fclose($file);
    }
}
