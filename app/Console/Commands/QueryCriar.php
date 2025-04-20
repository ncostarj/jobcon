<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class QueryCriar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:criar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'criar queries mongo para atualizacao de seu numero';

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
        $links = [
            // [ 'link'=>'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648320c63679a886e10a4a52/ativo', 'titulo_uid' => '010006897242011012 00001' ],
            // [ 'link' =>'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648321ea3679a886e111bc7a/ativo', 'titulo_uid' => '010006908846012012 00001' ]
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/6483227e3679a886e1157846/ativo', 'titulo_uid' => '010006980644012012 00001' ],
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/ffcf0335-d80f-4d5e-a709-50968ba74719/estoque/65144ca6c2ef5a68630d6214/ativo', 'titulo_uid' => '000039195551008012 00002' ],
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/ffcf0335-d80f-4d5e-a709-50968ba74719/estoque/65144ca6c2ef5a68630d6215/ativo', 'titulo_uid' => '000039195553009012 00002' ],
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/ffcf0335-d80f-4d5e-a709-50968ba74719/estoque/65144ca6c2ef5a68630d6216/ativo', 'titulo_uid' => '000039195555010012 00002' ],
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/ffcf0335-d80f-4d5e-a709-50968ba74719/estoque/65144ca6c2ef5a68630d6217/ativo', 'titulo_uid' => '000039195557011012 00002' ],
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/ffcf0335-d80f-4d5e-a709-50968ba74719/estoque/65144ca6c2ef5a68630d6218/ativo', 'titulo_uid' => '000039195559012012 00002' ]
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648320b93679a886e109f68c/ativo', 'titulo_uid' => '010006927854011012 00001' ]
            // [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648320d73679a886e10ab8e9/ativo', 'titulo_uid' => '010006927854012012 00001' ]

            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/64bfe5bfdc39f3b2780a2b1e/ativo' , 'titulo_uid' => '020007255442001006 00001', 'novo'=>'020007255442001006P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648321de3679a886e1116fb6/ativo' , 'titulo_uid' => '030006531535012012 00001', 'novo'=>'030006531535012012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648320dc3679a886e10ad2a4/ativo' , 'titulo_uid' => '010006897242010012 00001', 'novo'=>'010006897242010012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648320c63679a886e10a4a52/ativo' , 'titulo_uid' => '010006897242011012 00001', 'novo'=>'010006897242011012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/64bfe5bfdc39f3b2780a28b4/ativo' , 'titulo_uid' => '010007302500001012 00001', 'novo'=>'010007302500001012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/64bfe5bfdc39f3b2780a289c/ativo' , 'titulo_uid' => '040007093775001010 00001', 'novo'=>'040007093775001010P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648321e43679a886e11195b8/ativo' , 'titulo_uid' => '010006936580011012 00001', 'novo'=>'010006936580011012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648321c63679a886e110d47f/ativo' , 'titulo_uid' => '010006936580012012 00001', 'novo'=>'010006936580012012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648321e23679a886e11188c3/ativo' , 'titulo_uid' => '010006908846011012 00001', 'novo'=>'010006908846011012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648321ea3679a886e111bc7a/ativo' , 'titulo_uid' => '010006908846012012 00001', 'novo'=>'010006908846012012P00001' ],
            [ 'link' => 'https://octo.oliveiratrust.com.br/carteira/operacao/configuracao/06330ac9-30eb-4fa4-987a-85ea8dbdf06b/estoque/648323c33679a886e11da160/ativo' , 'titulo_uid' => '030006658765010012 00001', 'novo'=>'030006658765010012P00001' ],
        ];

        // _id: {
        //     $in: [
        //         ObjectId('65144ca6c2ef5a68630d6214'),
        //         ObjectId('65144ca6c2ef5a68630d6215'),
        //         ObjectId('65144ca6c2ef5a68630d6216'),
        //         ObjectId('65144ca6c2ef5a68630d6217'),
        //         ObjectId('65144ca6c2ef5a68630d6218'),
        //     ]
        // }
        
        foreach($links as $link) {
            $linkExploded = explode('/', $link['link']);

            // 'constantes.titulo_uid':'123', 
            
            Log::info("db.getCollection('operacao_{$linkExploded[6]}')
                .updateOne({ _id: ObjectId('{$linkExploded[8]}') }, 
                { 
                    \$set: { 
                        'constantes.titulo_uid': '{$link['novo']}',
                        'constantes.seu_numero': '{$link['novo']}'
                    }  
                } 
                })");
        }

        return 0;
    }
}
