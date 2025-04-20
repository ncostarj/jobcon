<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class VerificarCodigo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:codigo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fazer verificacao de codigo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


	// $queues = [
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_alta-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_atualizarConstantesLayout-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_baixa-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_baixas-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_conciliarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_criterios-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_desfazerImportacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_enviar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_estoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_estoqueLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_importacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_importacaoEstoquePorModelo-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_limparDadosProcessamento-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_media-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_precificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_processar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_processarLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_processarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_reprecificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_reprocessamentoSemFinanceiro-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_reprocessar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_retornos-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_urgente-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_11_validarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_alta-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_atualizarConstantesLayout-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_baixa-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_baixas-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_conciliarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_criterios-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_desfazerImportacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_enviar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_estoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_estoqueLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_importacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_importacaoEstoquePorModelo-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_limparDadosProcessamento-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_media-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_precificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_processar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_processarLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_processarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_reprecificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_reprocessamentoSemFinanceiro-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_reprocessar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_retornos-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_urgente-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_12_validarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_alta-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_atualizarConstantesLayout-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_baixa-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_baixas-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_conciliarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_criterios-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_desfazerImportacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_enviar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_estoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_estoqueLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_importacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_importacaoEstoquePorModelo-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_limparDadosProcessamento-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_media-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_precificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_processar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_processarLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_processarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_reprecificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_reprocessamentoSemFinanceiro-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_reprocessar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_retornos-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_urgente-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_13_validarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_alta-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_atualizarConstantesLayout-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_baixa-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_baixas-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_conciliarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_criterios-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_desfazerImportacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_enviar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_estoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_estoqueLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_importacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_importacaoEstoquePorModelo-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_limparDadosProcessamento-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_media-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_precificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_processar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_processarLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_processarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_reprecificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_reprocessamentoSemFinanceiro-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_reprocessar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_retornos-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_urgente-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_1_validarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_alta-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_atualizarConstantesLayout-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_baixa-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_baixas-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_conciliarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_criterios-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_desfazerImportacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_enviar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_estoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_estoqueLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_importacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_importacaoEstoquePorModelo-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_limparDadosProcessamento-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_media-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_precificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_processar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_processarLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_processarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_reprecificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_reprocessamentoSemFinanceiro-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_reprocessar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_retornos-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_urgente-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_2_validarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_alta-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_atualizarConstantesLayout-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_baixa-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_baixas-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_conciliarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_criterios-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_desfazerImportacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_enviar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_estoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_estoqueLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_importacaoEstoque-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_importacaoEstoquePorModelo-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_limparDadosProcessamento-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_media-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_precificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_processar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_processarLig-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_processarLigB3-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_reprecificar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_reprocessamentoSemFinanceiro-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_reprocessar-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_retornos-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_urgente-homolog',
	// 	'https://sqs.us-east-1.amazonaws.com/883957213232/fila_3_validarLigB3-homolog',
	// ];

	// $servers = [
	// 	'server_1' => 0,
	// 	'server_2' => 0,
	// 	"filas" => [
	// 		"fila_1" => "server_1",
	// 		"fila_2" => "server_1",
	// 		"fila_3" => "server_1",
	// 		"fila_11" => "server_2",
	// 		"fila_12" => "server_2",
	// 		"fila_13" => "server_2"
	// 	]
	// ];

	// $return = [];
	// $filas = [];
	// $filasPrioridades = [];
	// foreach ($queues as $queue) {

	// 	preg_match('/((?<id>fila_[0-9]+)_(?<prioridade>[a-zA-Z0-9]+)-(?<ambiente>[a-zA-Z]+))/', $queue, $matches);

	// 	$filaId = $matches['id']??'';
	// 	$nomeFila = !in_array($matches['prioridade'], ['baixa', 'media', 'alta', 'urgente']) ? $matches['prioridade'] : 'fila_padrao';
	// 	$prioridade = in_array($matches['prioridade'], ['baixa', 'media', 'alta', 'urgente']) ? $matches['prioridade'] : 'baixa';
	// 	$ambiente = $matches['ambiente']??'';

	// 	// if(in_array($prioridade, ['baixa', 'media', 'alta', 'urgente'])) {
	// 	//     $filas['prioridade'][] = compact('filaId', 'prioridade', 'ambiente');
	// 	// } else {
	// 	//     $filas['sem_prioridade'][] = compact('filaId', 'prioridade', 'ambiente');
	// 	// }

	// 	$server = $servers['filas'][$filaId];
	// 	// $server = $servers["filas"][$idFila];
	// 	// dump("{$idFila}|{$idFila2}");

	// 	// dd($server);

	// 	// dump("nomeServer: {$server}|idFila: {$idFila}|idFila2: {$idFila2}");

	// 	// if (!empty($serverName) && $serverName != $server) {
	// 	//     dump('Continuando');
	// 	//     continue;
	// 	// }

	// 	if (empty($return[$server][$prioridade][$filaId])) {
	// 		$return[$server][$prioridade][$filaId] = 0;
	// 	}

	// 	// dd($return);

	// 	// $queue_size = 1;
	// 	// $attributes = [];
	// 	// $queue_size_runner = 0;

	// 	// $servers["servers"][$server] ??= 0;
	// 	// $servers["servers"][$server] += $queue_size + $queue_size_runner;
	// 	// $return[$server][$idFila] += $queue_size;

	// 	// // $return['details'][$server][Str::beforeLast(Str::afterLast($queue, '/'), '-')] ??= ['queue' => 0, 'runner' => 0];
	// 	// $return['details'][$server][Str::beforeLast(Str::afterLast($queue, '/'), '-')]['queue'] ??= 0;
	// 	// $return['details'][$server][Str::beforeLast(Str::afterLast($queue, '/'), '-')]['queue'] += $queue_size;

	// 	// $return['details'][$server][Str::beforeLast(Str::afterLast($queue, '/'), '-')]['runner'] ??= 0;
	// 	// $return['details'][$server][Str::beforeLast(Str::afterLast($queue, '/'), '-')]['runner'] += $queue_size_runner;
	// }
	// // dump($filas['prioridade'], $filas['sem_prioridade']);
	// // dump($filas['prioridade'], $filas['sem_prioridade']);
	// // dump($servers, $return);
	// sort($return);
	// dump($return);

	// dd('----');

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


		$retorno = json_decode('[{"servidor":"server_1","fila":"fila_1","queue":"media","class":"CriteriosQueueJob","qtd":1,"operacoes":"c51fde9b-1dc7-4a18-a041-f7d6021c3732","operacao":"VERT-5 ALAN","link_operacao":"https:\/\/octo.oliveiratrust.com.br\/carteira\/operacao\/configuracao\/c51fde9b-1dc7-4a18-a041-f7d6021c3732"}]');
		$servers = json_decode('{"servers":{"server_1":1,"server_2":1}}');
		$queueDetails = json_decode('["fila_11|fila_padrao|alta|homolog|server_2","fila_11|atualizarConstantesLayout|baixa|homolog|server_2","fila_11|fila_padrao|baixa|homolog|server_2","fila_11|baixas|baixa|homolog|server_2","fila_11|conciliarLigB3|baixa|homolog|server_2","fila_11|criterios|baixa|homolog|server_2","fila_11|desfazerImportacaoEstoque|baixa|homolog|server_2","fila_11|enviar|baixa|homolog|server_2","fila_11|estoque|baixa|homolog|server_2","fila_11|estoqueLig|baixa|homolog|server_2","fila_11|importacaoEstoque|baixa|homolog|server_2","fila_11|importacaoEstoquePorModelo|baixa|homolog|server_2","fila_11|limparDadosProcessamento|baixa|homolog|server_2","fila_11|fila_padrao|media|homolog|server_2","fila_11|precificar|baixa|homolog|server_2","fila_11|processar|baixa|homolog|server_2","fila_11|processarLig|baixa|homolog|server_2","fila_11|processarLigB3|baixa|homolog|server_2","fila_11|reprecificar|baixa|homolog|server_2","fila_11|reprocessamentoSemFinanceiro|baixa|homolog|server_2","fila_11|reprocessar|baixa|homolog|server_2","fila_11|retornos|baixa|homolog|server_2","fila_11|fila_padrao|urgente|homolog|server_2","fila_11|validarLigB3|baixa|homolog|server_2","fila_12|fila_padrao|alta|homolog|server_2","fila_12|atualizarConstantesLayout|baixa|homolog|server_2","fila_12|fila_padrao|baixa|homolog|server_2","fila_12|baixas|baixa|homolog|server_2","fila_12|conciliarLigB3|baixa|homolog|server_2","fila_12|criterios|baixa|homolog|server_2","fila_12|desfazerImportacaoEstoque|baixa|homolog|server_2","fila_12|enviar|baixa|homolog|server_2","fila_12|estoque|baixa|homolog|server_2","fila_12|estoqueLig|baixa|homolog|server_2","fila_12|importacaoEstoque|baixa|homolog|server_2","fila_12|importacaoEstoquePorModelo|baixa|homolog|server_2","fila_12|limparDadosProcessamento|baixa|homolog|server_2","fila_12|fila_padrao|media|homolog|server_2","fila_12|precificar|baixa|homolog|server_2","fila_12|processar|baixa|homolog|server_2","fila_12|processarLig|baixa|homolog|server_2","fila_12|processarLigB3|baixa|homolog|server_2","fila_12|reprecificar|baixa|homolog|server_2","fila_12|reprocessamentoSemFinanceiro|baixa|homolog|server_2","fila_12|reprocessar|baixa|homolog|server_2","fila_12|retornos|baixa|homolog|server_2","fila_12|fila_padrao|urgente|homolog|server_2","fila_12|validarLigB3|baixa|homolog|server_2","fila_13|fila_padrao|alta|homolog|server_2","fila_13|atualizarConstantesLayout|baixa|homolog|server_2","fila_13|fila_padrao|baixa|homolog|server_2","fila_13|baixas|baixa|homolog|server_2","fila_13|conciliarLigB3|baixa|homolog|server_2","fila_13|criterios|baixa|homolog|server_2","fila_13|desfazerImportacaoEstoque|baixa|homolog|server_2","fila_13|enviar|baixa|homolog|server_2","fila_13|estoque|baixa|homolog|server_2","fila_13|estoqueLig|baixa|homolog|server_2","fila_13|importacaoEstoque|baixa|homolog|server_2","fila_13|importacaoEstoquePorModelo|baixa|homolog|server_2","fila_13|limparDadosProcessamento|baixa|homolog|server_2","fila_13|fila_padrao|media|homolog|server_2","fila_13|precificar|baixa|homolog|server_2","fila_13|processar|baixa|homolog|server_2","fila_13|processarLig|baixa|homolog|server_2","fila_13|processarLigB3|baixa|homolog|server_2","fila_13|reprecificar|baixa|homolog|server_2","fila_13|reprocessamentoSemFinanceiro|baixa|homolog|server_2","fila_13|reprocessar|baixa|homolog|server_2","fila_13|retornos|baixa|homolog|server_2","fila_13|fila_padrao|urgente|homolog|server_2","fila_13|validarLigB3|baixa|homolog|server_2","fila_1|fila_padrao|alta|homolog|server_1","fila_1|atualizarConstantesLayout|baixa|homolog|server_1","fila_1|fila_padrao|baixa|homolog|server_1","fila_1|baixas|baixa|homolog|server_1","fila_1|conciliarLigB3|baixa|homolog|server_1","fila_1|criterios|baixa|homolog|server_1","fila_1|desfazerImportacaoEstoque|baixa|homolog|server_1","fila_1|enviar|baixa|homolog|server_1","fila_1|estoque|baixa|homolog|server_1","fila_1|estoqueLig|baixa|homolog|server_1","fila_1|importacaoEstoque|baixa|homolog|server_1","fila_1|importacaoEstoquePorModelo|baixa|homolog|server_1","fila_1|limparDadosProcessamento|baixa|homolog|server_1","fila_1|fila_padrao|media|homolog|server_1","fila_1|precificar|baixa|homolog|server_1","fila_1|processar|baixa|homolog|server_1","fila_1|processarLig|baixa|homolog|server_1","fila_1|processarLigB3|baixa|homolog|server_1","fila_1|reprecificar|baixa|homolog|server_1","fila_1|reprocessamentoSemFinanceiro|baixa|homolog|server_1","fila_1|reprocessar|baixa|homolog|server_1","fila_1|retornos|baixa|homolog|server_1","fila_1|fila_padrao|urgente|homolog|server_1","fila_1|validarLigB3|baixa|homolog|server_1","fila_2|fila_padrao|alta|homolog|server_1","fila_2|atualizarConstantesLayout|baixa|homolog|server_1","fila_2|fila_padrao|baixa|homolog|server_1","fila_2|baixas|baixa|homolog|server_1","fila_2|conciliarLigB3|baixa|homolog|server_1","fila_2|criterios|baixa|homolog|server_1","fila_2|desfazerImportacaoEstoque|baixa|homolog|server_1","fila_2|enviar|baixa|homolog|server_1","fila_2|estoque|baixa|homolog|server_1","fila_2|estoqueLig|baixa|homolog|server_1","fila_2|importacaoEstoque|baixa|homolog|server_1","fila_2|importacaoEstoquePorModelo|baixa|homolog|server_1","fila_2|limparDadosProcessamento|baixa|homolog|server_1","fila_2|fila_padrao|media|homolog|server_1","fila_2|precificar|baixa|homolog|server_1","fila_2|processar|baixa|homolog|server_1","fila_2|processarLig|baixa|homolog|server_1","fila_2|processarLigB3|baixa|homolog|server_1","fila_2|reprecificar|baixa|homolog|server_1","fila_2|reprocessamentoSemFinanceiro|baixa|homolog|server_1","fila_2|reprocessar|baixa|homolog|server_1","fila_2|retornos|baixa|homolog|server_1","fila_2|fila_padrao|urgente|homolog|server_1","fila_2|validarLigB3|baixa|homolog|server_1","fila_3|fila_padrao|alta|homolog|server_1","fila_3|atualizarConstantesLayout|baixa|homolog|server_1","fila_3|fila_padrao|baixa|homolog|server_1","fila_3|baixas|baixa|homolog|server_1","fila_3|conciliarLigB3|baixa|homolog|server_1","fila_3|criterios|baixa|homolog|server_1","fila_3|desfazerImportacaoEstoque|baixa|homolog|server_1","fila_3|enviar|baixa|homolog|server_1","fila_3|estoque|baixa|homolog|server_1","fila_3|estoqueLig|baixa|homolog|server_1","fila_3|importacaoEstoque|baixa|homolog|server_1","fila_3|importacaoEstoquePorModelo|baixa|homolog|server_1","fila_3|limparDadosProcessamento|baixa|homolog|server_1","fila_3|fila_padrao|media|homolog|server_1","fila_3|precificar|baixa|homolog|server_1","fila_3|processar|baixa|homolog|server_1","fila_3|processarLig|baixa|homolog|server_1","fila_3|processarLigB3|baixa|homolog|server_1","fila_3|reprecificar|baixa|homolog|server_1","fila_3|reprocessamentoSemFinanceiro|baixa|homolog|server_1","fila_3|reprocessar|baixa|homolog|server_1","fila_3|retornos|baixa|homolog|server_1","fila_3|fila_padrao|urgente|homolog|server_1","fila_3|validarLigB3|baixa|homolog|server_1"]');
		$return = json_decode('{"server_2":{"homolog":{"alta":{"fila_11":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_12":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_13":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0}},"baixa":{"fila_11":{"name":"validarLigB3","size":0,"queue_size":0,"queue_size_runner":0},"fila_12":{"name":"validarLigB3","size":0,"queue_size":0,"queue_size_runner":0},"fila_13":{"name":"validarLigB3","size":0,"queue_size":0,"queue_size_runner":0}},"media":{"fila_11":{"name":"fila_padrao","size":1,"queue_size":1,"queue_size_runner":0},"fila_12":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_13":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0}},"urgente":{"fila_11":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_12":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_13":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0}}}},"server_1":{"homolog":{"alta":{"fila_1":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_2":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_3":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0}},"baixa":{"fila_1":{"name":"validarLigB3","size":0,"queue_size":0,"queue_size_runner":0},"fila_2":{"name":"validarLigB3","size":0,"queue_size":0,"queue_size_runner":0},"fila_3":{"name":"validarLigB3","size":0,"queue_size":0,"queue_size_runner":0}},"media":{"fila_1":{"name":"fila_padrao","size":1,"queue_size":1,"queue_size_runner":0},"fila_2":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_3":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0}},"urgente":{"fila_1":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_2":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0},"fila_3":{"name":"fila_padrao","size":0,"queue_size":0,"queue_size_runner":0}}}}}');

		// dd($retorno, $servers, $queueDetails, $return);
		$organizadorFila = collect();
		foreach($queueDetails as $queue) {
			[$id, $nome, $prioridade, $ambiente, $servidor] = explode("|", $queue);
			// $organizadorFila[$nome][]= compact('id', 'nome', 'prioridade', 'ambiente', 'servidor');
			$organizadorFila->add(compact('id', 'nome', 'prioridade', 'ambiente', 'servidor'));
		}

		dump($organizadorFila->where('nome','fila_padrao')->count(),
		$organizadorFila->where('nome',"!=",'fila_padrao')->count());

		dump($organizadorFila->where('nome',"!=",'fila_padrao')->values());
		dd('-----');
        return 0;
    }
}
