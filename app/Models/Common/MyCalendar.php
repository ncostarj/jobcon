<?php

namespace App\Models\Common;

use Illuminate\Support\Facades\Log;

class MyCalendar
{

	protected $hoje;
	protected $primeiroDiaMes;
	protected $primeiroDiaMesSeguinte;
	protected $diaPagamento;
	protected $diaPagamentoPrevisto;
	protected $ultimoDiaMes;
	protected $diferenca;
	protected $diferencaPagamento;
	protected $feriados;

	public function __construct()
	{
		$this->hoje = strtotime('now');
		$this->primeiroDiaMes = strtotime('first day of this month');
		$this->primeiroDiaMesSeguinte = strtotime('first day of next month');
		$this->diaPagamento = strtotime('+ 24 days', $this->primeiroDiaMes);
		$this->ultimoDiaMes = strtotime('last day of this month');
		$this->feriados = [
			[
				"dia" =>"01/01/2025",
				"nome" => "Ano Novo"
			],
			[
				"dia" =>"03/03/2025",
				"nome" => "Segunda de Carnaval"
			],
			[
				"dia" =>"04/03/2025",
				"nome" => "Terça de Carnaval"
			],
			[
				"dia" =>"05/03/2025",
				"nome" => "Quarta-feira de Cinzas (ponto facultativo até as 14h)"
			],
			[
				"dia" =>"18/04/2025",
				"nome" => "Sexta-feira Santa"
			],
			[
				"dia" =>"21/04/2025",
				"nome" => "Tiradentes"
			],
			[
				"dia" =>"01/04/2025",
				"nome" => "do Trabalhador"
			],
			[
				"dia" =>"19/06/2025",
				"nome" => "Corpus Christi"
			],
			[
				"dia" =>"07/09/2025",
				"nome" => "Independência do Brasil"
			],
			[
				"dia" =>"12/10/2025",
				"nome" => "Nossa Senhora Aparecida"
			],
			[
				"dia" =>"02/11/2025",
				"nome" => "Finados"
			],
			[
				"dia" =>"15/11/2025",
				"nome" => "Proclamação da República"
			],
			[
				"dia" =>"20/11/2025",
				"nome" => "da Consciência Negra"
			],
			[
				"dia" =>"24/12/2025",
				"nome" => "Véspera de Natal (ponto facultativo após as 14h)"
			],
			[
				"dia" =>"25/12/2025",
				"nome" => "Natal"
			],
			[
				"dia" =>"31/12/2025",
				"nome" => "Véspera de Ano Novo (ponto facultativo após as 14h)"
			],
		];
	}

	private function getDiasUteisAteFimMes($qtdDias, $data)
	{
		$qtdDiasUteis = 0;

		$hoje = strtotime('now');

		if($data == $hoje) {
			$data = strtotime('+ 1 day', $data);
		}

		for ($i = 0; $i <= $qtdDias; $i++) {
			$diaSemana = date('N', $data);
			if(!in_array($diaSemana,[ 6, 7 ]) && !in_array(date('d/m/Y', $data), array_keys($this->feriados))) {
				$qtdDiasUteis++;
			}
			$data = strtotime('+ 1 day', $data);
		}
		return $qtdDiasUteis;
	}

	private function getDiasAteFimMes($data, $hoje)
	{
		$this->diferenca = ($data - $hoje) / 86400;
		return $this->diferenca;
	}

	private function getDiasAtePagamento()
	{

		$diaSemana = date('N', $this->diaPagamento);
		$diaPagamento = $this->diaPagamento;

		if(in_array($diaSemana, [6, 7])) {
			$diaPagamento = strtotime('+ 2 day', $diaPagamento);
		}

		$this->diaPagamentoPrevisto = date('d/m/Y', $diaPagamento);

		$diferenca = $diaPagamento - $this->hoje;

		if ($diferenca <= 0) {
			$diaPagamento = strtotime('+ 24 days', $this->primeiroDiaMesSeguinte);
			$diferenca = $diaPagamento - $this->hoje;
		}

		$this->diferencaPagamento = $diferenca;

		return $this->diferencaPagamento > 0 ? ($this->diferencaPagamento / 86400) : 0;
	}

	public function getMes($mes)
	{
		switch ($mes) {
			case '01':
				$mesExtenso = "Janeiro";
				break;
			case '02':
				$mesExtenso = "Fevereiro";
				break;
			case '03':
				$mesExtenso = "Março";
				break;
			case '04':
				$mesExtenso = "Abril";
				break;
			case '05':
				$mesExtenso = "Maio";
				break;
			case '06':
				$mesExtenso = "Junho";
				break;
			case '07':
				$mesExtenso = "Julho";
				break;
			case '08':
				$mesExtenso = "Agosto";
				break;
			case '09':
				$mesExtenso = "Setembro";
				break;
			case '10':
				$mesExtenso = "Outubro";
				break;
			case '11':
				$mesExtenso = "Novembro";
				break;
			case '12':
				$mesExtenso = "Dezembro";
				break;
			default:
				$mesExtenso = "-";
				break;
		}
		return $mesExtenso;
	}

	public function getDiaSemana($dia)
	{
		switch ($dia) {
			case 1:
				$diaExtenso = "Segunda";
				break;
			case 2:
				$diaExtenso = "Terça";
				break;
			case 3:
				$diaExtenso = "Quarta";
				break;
			case 4:
				$diaExtenso = "Quinta";
				break;
			case 5:
				$diaExtenso = "Sexta";
				break;
			case 6:
				$diaExtenso = "Sábado";
				break;
			case 7:
				$diaExtenso = "Domingo";
				break;
			default:
				$diaExtenso = "Segunda";
				break;
		}
		return $diaExtenso;
	}

	public function getData()
	{
		$diaDaSemanaNumerico = date('N', $this->hoje);
		$hoje = date('Y-m-d', $this->hoje);
		[$ano, $mes, $dia] = explode('-', $hoje);
		$diasAteFimMes = $this->getDiasAteFimMes($this->ultimoDiaMes, $this->hoje);
		$qtdDiasMes = date('d', $this->ultimoDiaMes);

		return	(object) [
			'hoje' => (object) [
				'diaDaSemana' => $this->getDiaSemana($diaDaSemanaNumerico),
				'dia' => $dia,
				'mes' => $this->getMes($mes),
				'ano' => $ano,
			],
			'diferenca' => $this->diferencaPagamento,
			'qtdDiasMes' => $qtdDiasMes,
			'qtdDiaUteisMes' => $this->getDiasUteisAteFimMes($qtdDiasMes, $this->primeiroDiaMes),
			'qtdDiasAteFimMes' => $diasAteFimMes,
			'qtdDiasUteisAteFimMes' => $this->getDiasUteisAteFimMes($diasAteFimMes, $this->hoje),
			'qtdDiasAtePagamento' => $this->getDiasAtePagamento(),
			'diaPagamento' => date('Y-m-d', $this->diaPagamento),
			'diaPagamentoPrevisto' => $this->diaPagamentoPrevisto,
		];
	}

	public function getCurWeekInterval($dados)
	{
		$periodo = empty($dados['periodo']) ? 'this' : $dados['periodo'];
		$data_inicio = date('Y-m-d', strtotime("monday {$periodo} week"));
		$data_fim = date('Y-m-d', strtotime("friday {$periodo} week"));
		return compact('data_inicio', 'data_fim');
	}

	public function getFeriados() {
		return $this->feriados;
	}
}
