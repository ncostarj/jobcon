<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FrequenciaSeeder extends Seeder
{

	private function getTime(float $float)
	{
		$hour = (int) floor($float);
		$decimal = fmod($float, 1);
		$minutesFull = $decimal * 60;
		$minutes = (int) floor($minutesFull);
		// $seconds = (int) (round(fmod($minutesFull, 1), 1) * 60);:{$seconds}
		return date('H:i', strtotime("{$hour}:{$minutes}"));
	}

	private function calculateFrequencia(float $saldo_banco_anterior,
		float $credito_banco,
		float $debito_banco,
		float $saldo_banco_atual) {
		// // $saldo_banco_anterior = 13.87; // ainda não ajustou o dia 24/10
		// $saldo_banco_anterior = 11.87;
		// // $credito_banco = 0.22;
		// $credito_banco = 0;
		// // $debito_banco = 3.52;
		// // $debito_banco = 1.47;
		// $debito_banco = 10.18;
		// // $saldo_banco_atual = 10.57;
		// // $saldo_banco_atual = 12.62;
		// $saldo_banco_atual = 1.68;

		$saldo_banco_anterior_horas = $this->getTime($saldo_banco_anterior);
		$credito_banco_horas = $this->getTime($credito_banco);
		$debito_banco_horas = $this->getTime($debito_banco);
		$saldo_banco_atual_horas = $this->getTime($saldo_banco_atual);

		return compact('saldo_banco_anterior_horas', 'credito_banco_horas', 'debito_banco_horas', 'debito_banco_horas', 'saldo_banco_atual_horas');
	}

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// $saldo = $this->calculateFrequencia(13.87, 0.22, 1.47, 12.62);
		// $saldo = $this->calculateFrequencia(11.87, 0, 10.18, 1.68);
		// extract($this->calculateFrequencia(11.68, 0, 0.92, 10.77));
		extract($this->calculateFrequencia(14.75,0,2.10,12.65));

		DB::table('frequencias')->insert([
			[
				'id' => Str::uuid()->toString(),
				'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
				'data' => date('Y-m-d'),
				'saldo_anterior' => $saldo_banco_anterior_horas,
				'saldo_atual' => $saldo_banco_atual_horas,
				'credito' => $credito_banco_horas,
				'debito' => $debito_banco_horas
			],
			// [
			// 	'id' => Str::uuid()->toString(),
			// 	'user_id' => User::where('email', 'ncosta.rj@gmail.com')->first()->id,
			// 	'inicio' => '2024-11-01',
			// 	'fim' => '2024-11-30',
			// 	'saldo_anterior' => $saldo_banco_anterior_horas,
			// 	'saldo_atual' => $saldo_banco_atual_horas,
			// 	'credito' => $credito_banco_horas,
			// 	'debito' => $debito_banco_horas
			// ],
		]);
	}
}
