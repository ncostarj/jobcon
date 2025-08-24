<?php

namespace App\Models\Csv;

class CedentesReader extends CsvReaderBase {

    protected $pathFile;

    public function __construct($pathFile) {
        parent::__construct($pathFile);
    }

    public function read() {
		// $arquivo = fopen(public_path("assets/csv/model_cedentes.csv"), 'w');
		$csv = '';
		$contador = 0;

		$fields = [
			'razao social' => true,
			'inscricao' => true,
			'contato_telefone' => false,
			'contato_email' => false,
			'contato_responsavel' => false,
			'endereco_cep' => true,
			'endereco_logradouro' => false,
			'endereco_complemento' => false,
			'endereco_bairro' => false,
			'endereco_cidade' => false,
			'endereco_estado' => false,
			'classificacao_risco' => false,
			'tipo de pessoa (PF ou PJ)' => true,
			'porte_cliente' => true,
			'tipo_de_controle' => true,
			'inicio_relacionamento_cliente' => true,
			'faturameto_anual' => false,
			'autorizacao_para_consulta' => false,
			'cedente_segmento' => true,
			'cedente_codigo_do_cedente' => true,
			'cedente_nome_completo_titular_banco' => true,
			'cedente_banco' => true,
			'cedente_agencia' => true,
			'cedente_conta' => true,
			'titular_conta_pgtoboleto' => true,
			'layout_identificacao_campo' => true,
			'layout_identificacao_valor' => true,
			'layout_campo_associar_cedente' => true,
			'layout_valor_associar_cedente' => true,
		];

		$header = array_keys($fields);
		while ($row = fgetcsv($this->arquivo, 1000, ";")) {
			if($contador == 0){
				$contador++;
				continue;
            }

			dump($row);
			$contador++;
		}
		dd('teste');


		// dump($contador, $contadorRecompra);
		// fclose($arquivo);

        fclose($this->arquivo);
    }

	public function write() {

	}
}
