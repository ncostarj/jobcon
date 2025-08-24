<?php

namespace App\Models\Csv;

class LiquidadosReader extends CsvReaderBase {

    protected $pathFile;

    public function __construct($pathFile) {
        $this->pathFile = $pathFile;
    }

    public function read() {
        $contador = 0;
        $header = [
			// "Nome do Arquivo",
			"Data Referencia",
			"Data de Entrada",
			"Data Movimento",
			// "Feito em",
			// "Feito por",
			// "Data da Baixa",
			// "Data da Liquidacao",
			// "Data de Vencimento",
			// "Dias ate o Vencimento",
			// "CNPJ da Operacao",
			"Nome do Fundo",
			"Nome do Cedente",
			// "Codigo do Cedente",
			// "Nome do Sacado",
			// "Inscricao do Sacado",
			// "Idade Sacado",
			// "Codigo do Contrato",
			// "Codigo da CCB",
			"Seu Numero",
			// "Numero Da Parcela Liquidada",
			// "Valor Presente",
			// "Valor Pago",
			// "Valor de Face",
			// "Valor de Face Corrigido",
			// "Valor de aquisicao",
			// "Valor nominal potencial",
			// "Diferenca",
			"Tipo de Baixa",
			"Detalhe da Ocorrencia",
			// "Codigo do Produto",
			// "Codigo da Loja",
			// "Codigo do Setor do Sacado",
			// "Codigo do Tipo de Financiamento",
			// "Numero da Nota Fiscal",
			// "Codigo do Indice",
			// "Codigo do beneficio",
			// "Sexo Sacado",

		];

		$periodo = [
			'inicio' => '2023-11-01',
			'fim' => '2023-11-30'
		];

		$contador=0;
		$contadorRecompra = 0;
		$titulosRecompra = [];

		$arquivo = fopen(public_path("assets/csv/baixa_recompra_bv_novembro23.csv"), 'w');
		$csv = '';

		$header = [
			"Codigo do Contrato",
			"Codigo da CCB",
			"Seu Numero",
			"Valor de Face",
			"Valor de aquisicao",
			"Valor Pago",
			"Nome do Sacado",
			"Inscricao do Sacado",
			"Seu Numero",
			"Data de Entrada",
			"Data de Vencimento",
			// "Contatounico_uid",
			"Numero Da Parcela Liquidada",
			"Numero da Nota Fiscal",
			// "valor da nota fiscal",
			// "codigo da bandeira",
			"Codigo do Produto",
			// "data_aquisicao",
		];

        // dd($this->arquivo);
        while ($row = fgetcsv($this->arquivo, 1000, ";")) {
            if($contador == 0){
                $header = $row;
				fputcsv($arquivo, $header,";");
                $contador++;
                continue;
            }


            $linha = array_combine($header, $row);
			$linha['Data Referencia'] = preg_replace("/([0-9]+)\/([0-9]+)\/([0-9]+)/", '$3-$2-$1',$linha['Data Referencia']);

			if($linha['Data Referencia'] >= $periodo['inicio']
				&& $linha['Data Referencia'] <= $periodo['fim']
				&& trim($linha['Tipo de Baixa']) == "Baixa Por Recompra") {
				fputcsv($arquivo, $row,";");
				$contadorRecompra++;
			}

			if($contador%100000 == 0){
				dump("Parcial {$contador} - {$contadorRecompra}");
			}



			$contador++;
			// dump(implode(",", $linha));
            // $qtdCharInscricao = strlen($linha['inscricao_sacado']);
            // if($qtdCharInscricao < 14) {
            //     dump(implode(';',$linha));
            // }
        }

		dump($contador, $contadorRecompra);
		fclose($arquivo);

        fclose($this->arquivo);
    }
}
