<?php

namespace App\Models\Csv;

use App\Models\User;
use App\Models\Ponto;
use App\Models\Horario;
use Illuminate\Support\Facades\DB;

class PontoCsv extends CsvBase
{

	protected $pathFile;

	public function __construct($pathFile)
	{
		$this->pathFile = $pathFile;
	}

	public function read()
	{
		$contador = 0;
		$header = [];
		$pontos = [];

		while ($row = fgetcsv($this->arquivo, 1000, ",")) {

			if ($contador == 0) {
				$header = $row;
				$contador++;
				continue;
			}

			$data = array_combine($header, $row);

			$observacao = $data['observacao'] != '0' ? $data['observacao'] : '';

			$dia = date('Ymd', strtotime($data['dia']));
			$hora = date('His', strtotime($data['hora']));

			if (!isset($pontos[$dia])) {
				$pontos[$dia] = [
					'dia' => $data['dia'],
					'categoria' => $data['categoria'],
					'pedir_ajuste' => $data['pedir_ajuste'],
					'observacao' => $observacao,
					'horarios' => [
						$hora => [
							'hora' => $data['hora'],
							'tipo' => $data['tipo'],
							'observacao' => $observacao
						]
					]
				];
			}

			if (isset($pontos[$dia]) && !isset($pontos[$dia]['horarios'][$hora])) {
				$pontos[$dia]['horarios'][$hora] = [
					'categoria' => $data['categoria'],
					'hora' => $data['hora'],
					'tipo' => $data['tipo'],
					'observacao' => $observacao
				];
			}

			$contador++;
		}

		$contadorPonto = 0;
		$contadorHorarios = 0;
		foreach ($pontos as $ponto) {

			$pontoObj = new Ponto;
			$pontoObj->fill([
				'dia' => $ponto['dia'],
				'categoria' => $ponto['categoria'],
				'pedir_ajuste' => $ponto['pedir_ajuste'],
				'observacao' => $ponto['observacao']
			]);

			$pontoObj->usuario()->associate(User::where('name', 'Newton Gonzaga Costa')->first());

			$pontoObj->save();

			$contadorPonto++;

			foreach ($ponto['horarios'] as $horario) {
				$horarioObj = new Horario;
				$horarioObj->fill([
					"hora" => $horario['hora'],
					"tipo" => $horario['tipo'],
					"observacao" => $horario['observacao']
				]);

				$pontoObj->horarios()->save($horarioObj);

				$contadorHorarios++;
			}
		}

		fclose($this->arquivo);

		return "{$contadorPonto} pontos e {$contadorHorarios} horarios importados com sucesso!";
	}

	public function write()
	{
		$csv="id,dia,categoria,hora,tipo,pedir_ajuste,observacao,created_at,updated_at,deleted_at\n";
		$horarios = Horario::with('ponto')->orderBy('hora','asc')->get();
		$contador = 0;
		foreach($horarios as $horario) {
			$ponto = $horario->ponto;
			$observacao = str_replace(',',';',$ponto->observacao);
			$csv .= "{$ponto->id},{$ponto->dia->format('Y-m-d')},{$ponto->categoria},{$horario->hora},{$horario->tipo},{$ponto->pedir_ajuste},{$observacao},{$horario->created_at},{$horario->updated_at},{$horario->deleted_at}\n";
			$contador++;
		}

		fwrite($this->arquivo, $csv);
		fclose($this->arquivo);

		return "{$contador} registros exportados com sucesso!";
	}
}
