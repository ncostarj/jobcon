<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerificarArray extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verificar:array';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checa dois arrays';

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
		$criteriosOperacoesCriteriosUids = [
			'7cf41e1e-671a-4fed-b8aa-45d865e687a1',
			'4ae119dd-a622-43b2-99ab-3e499a9fc9e4',
			'daf5a558-06e2-48c0-9217-33320333734b',
			'9334ec4c-8a7d-42c7-8429-887ae926c24b',
			'89bf5f52-cd17-4110-8deb-602b5097a514',
			'75612f84-724e-4f92-8ec0-a361c4d883dc',
			'5c6a086f-b540-4749-aa18-44dba7ad0e83',
			'55f41fe2-0e4b-4342-ba5b-5da73a5ea68a',
			'86cc37e2-5af9-4d19-9956-626be3809d3b',
			'7bd72b7a-9c05-4d0d-9be2-689680e30a82',
			'd6760983-5c15-4017-bae1-3be0d1c8bfb4',
			'b5bcd17b-2019-4d0d-a4ad-49416dd07d10',
			'd9c7dbb1-4ebc-4e42-a33e-f56fd0262cd8',
			'bf5dd446-aac3-4a75-b4f4-e370838343cd',
			'95245402-5ee7-4583-8539-55b02b91fd5a',
			'6ffa070e-a391-4abd-9c85-2179d08e0045',
			'46f77c1a-f1c2-4b3a-933e-48cfb7fafb5c',
			'e86b5758-058b-4687-826f-ce7104bf2fa5',
			'05dbc3bd-6280-446f-97cc-f4de5286eb8c',
			'ec2ba450-b9bb-4c26-8f69-82ddaf328d2c',
			'223932b8-d09d-478a-9be4-ebff04c186d7',
			'0e02248f-1a10-42b3-b4b3-644d80d6388b',
			'f0c9e9e3-b50c-4fad-ac23-beee848bad7b',
			'3d703c79-1c34-42a8-9d0b-fa8817e8d9c4',
			'0451bb0c-ecaf-4974-a272-ed9a1fb07faa',
			'47fab403-935c-40f2-a1cb-78d0344ecda4',
			'ee14e598-253c-454c-b7bb-1f9965ab534b',
			'036261ca-58fe-4c34-ad1e-1c66d25d65ad',
			'02feba00-0635-4a78-846a-b0f24cae0b79',
			'fc0a9885-3b14-4048-932d-96525d6f3f1b',
			'27827fc7-25d7-4aab-9985-b55288a64b85',
			'db568f29-245c-40f4-9053-153245dc8d65',
			'3dad63e0-ab8a-4b92-bba5-1bcff970ef9c',
			'87b2eeaf-fd77-4c51-bcf8-b9a518fd336c',
			'41b9067f-d94d-44b2-9b9d-6993583adc8f',
			'f81b4a05-0e18-4ce2-97d2-7c4e8a95d88e',
		];

		$criteriosUids = [
			'02feba00-0635-4a78-846a-b0f24cae0b79',
			'036261ca-58fe-4c34-ad1e-1c66d25d65ad',
			'0451bb0c-ecaf-4974-a272-ed9a1fb07faa',
			'05dbc3bd-6280-446f-97cc-f4de5286eb8c',
			'0e02248f-1a10-42b3-b4b3-644d80d6388b',
			'0fc6a612-5b0d-42ca-9b12-77aa4896f963',
			'17ff01c4-59f0-48df-8caf-06a45b84fa2d',
			'1cb113d7-e9d0-4391-8e4f-39e852012513',
			'223932b8-d09d-478a-9be4-ebff04c186d7',
			'2569bf67-9f9e-450f-a9b7-aae13b42637a',
			'27827fc7-25d7-4aab-9985-b55288a64b85',
			'2a6509a4-d4f3-4735-a4fd-6d051e74c5a5',
			'3a9f25d6-0681-4324-aa47-b9db9256a268',
			'3d703c79-1c34-42a8-9d0b-fa8817e8d9c4',
			'3dad63e0-ab8a-4b92-bba5-1bcff970ef9c',
			'41b9067f-d94d-44b2-9b9d-6993583adc8f',
			'46f77c1a-f1c2-4b3a-933e-48cfb7fafb5c',
			'47fab403-935c-40f2-a1cb-78d0344ecda4',
			'4ae119dd-a622-43b2-99ab-3e499a9fc9e4',
			'55f41fe2-0e4b-4342-ba5b-5da73a5ea68a',
			'5c6a086f-b540-4749-aa18-44dba7ad0e83',
			'6ffa070e-a391-4abd-9c85-2179d08e0045',
			'75612f84-724e-4f92-8ec0-a361c4d883dc',
			'7bd72b7a-9c05-4d0d-9be2-689680e30a82',
			'7cf41e1e-671a-4fed-b8aa-45d865e687a1',
			'82c6ae9d-2e2c-4730-80f7-bdb6f61fde18',
			'86cc37e2-5af9-4d19-9956-626be3809d3b',
			'87b2eeaf-fd77-4c51-bcf8-b9a518fd336c',
			'89bf5f52-cd17-4110-8deb-602b5097a514',
			'9334ec4c-8a7d-42c7-8429-887ae926c24b',
			'95245402-5ee7-4583-8539-55b02b91fd5a',
			'a7e6a602-61b9-4439-a76e-30519bce1678',
			'b1b7d4d2-65ab-40ed-bb1f-063863e8dc27',
			'b5bcd17b-2019-4d0d-a4ad-49416dd07d10',
			'bf5dd446-aac3-4a75-b4f4-e370838343cd',
			'd6067e16-671d-4fd0-b074-a72a11666921',
			'd6760983-5c15-4017-bae1-3be0d1c8bfb4',
			'd9c7dbb1-4ebc-4e42-a33e-f56fd0262cd8',
			'daf5a558-06e2-48c0-9217-33320333734b',
			'db568f29-245c-40f4-9053-153245dc8d65',
			'e47516ba-7319-4656-9e7f-fdc8c2464b43',
			'e86b5758-058b-4687-826f-ce7104bf2fa5',
			'ec2ba450-b9bb-4c26-8f69-82ddaf328d2c',
			'ee14e598-253c-454c-b7bb-1f9965ab534b',
			'f0c9e9e3-b50c-4fad-ac23-beee848bad7b',
			'f81b4a05-0e18-4ce2-97d2-7c4e8a95d88e',
			'fc0a9885-3b14-4048-932d-96525d6f3f1b',
		];

		$uidsNovos = [];

		foreach($criteriosUids as $uid) {
			if(!in_array($uid, $criteriosOperacoesCriteriosUids)) {
				$uidsNovos[] = $uid;
			}
		}

		// # normativos
		// 'identificacao_do_boleto_nao_encontrada'
		// 'codigo_ocorrencia_invalido'
		// 'cedente_nao_encontrado'
		// 'data_de_emissao_superior_a_data_movimento_da_operacao'
		// 'valor_presente_superior_ou_igual_ao_valor_do_titulo'
		// 'valor_presente_zerado'
		// 'potencial_cessao'
		// 'padronizacao'
		// # normativos duplicados
		// 'titulo_duplicado_no_processamento'
        // 'titulo_duplicado_outro_processamento'
        // 'contrato_duplicado_no_processamento'
        // 'contrato_duplicado_outro_processamento'
        // 'numero_ccb_duplicado_no_processamento'
        // 'numero_ccb_duplicado_outro_processamento'
		// # objetivos
		// 'comparacao_texto'
		// 'comparacao_data_sem_estoque'
		// 'comparacao_valor'
		// 'comparacao_por_tempo'
		// 'comparacao_periodo_ou'

        return 0;
    }
}
