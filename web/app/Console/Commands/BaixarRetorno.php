<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BaixarRetorno extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retorno:baixar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            "https://octo.oliveiratrust.com.br/carteira/operacao/d0c70511-0b70-4b80-a447-62c790942580/nome/processamento_v2_77dc70a95deab3c674c6ed9069b58641_13-09-2023-08-37-42_8258.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/902eb47e-67b3-42a4-9a89-9c1359596868/nome/processamento_v2_c1dd58d3ef2b8c1785040558a440083b_13-09-2023-08-37-50_6226.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/62cd2193-ba74-45fd-8c69-fa458ec04807/nome/processamento_v2_0973b03680dec89570bd58fca409df1a_13-09-2023-08-38-02_5144.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/9f7c57a0-cf3f-4509-91d5-a8dd7c0348cd/nome/processamento_v2_07006644515c89af3915213e6da2f888_13-09-2023-08-38-13_9637.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/6512cabe-bea5-4d56-a07d-5c47c8873d52/nome/processamento_v2_534a033ba05faed8967889f2f29da588_13-09-2023-08-38-25_3761.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/1112dbf2-b53f-4d05-a2a7-bb96d8e5fbe2/nome/processamento_v2_b9ce3780c40967ec23c8f78e76ef471f_13-09-2023-08-38-47_9339.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/cf94893b-b340-437b-8b62-72df6e134220/nome/processamento_v2_dcc9d2ed84e2df325efc9e264e39db97_13-09-2023-08-38-48_9.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/93eadd45-ccff-4f8c-b103-c46f06beba8a/nome/processamento_v2_d8b12f383f21200e93e5d69510bc1b2b_13-09-2023-08-39-00_718.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/e8f4ea88-a0ea-4fc5-a1e1-c6a71c8967a7/nome/processamento_v2_9ec62bb17bdec0ea32624726ab6ac899_13-09-2023-08-39-13_7556.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/090ad1a1-9616-4ef8-94db-0a3aa772bfa8/nome/processamento_v2_640e91ce56a265888f811655be069e73_13-09-2023-08-39-22_230.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/a4e71f23-cd02-4f42-b238-4cacda38a3b7/nome/processamento_v2_cf58fb5b6f728694055994b765d2d708_13-09-2023-08-39-39_2462.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/5a41db12-b304-4980-9174-1fb29d484b98/nome/processamento_v2_cb83403cd88ea515fb8273041b4c3aa9_13-09-2023-08-39-46_4926.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/51402304-0ca7-4764-a77f-ee20d8f9d14b/nome/processamento_v2_53174c14e3fd5a273f446ca3bef20922_13-09-2023-08-39-57_3351.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/a6fef7c1-b12a-4922-b607-eb46f6ac2cc3/nome/processamento_v2_39b7158c30083ef836432b5562b7a4a8_13-09-2023-08-40-08_1332.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/22e980da-8691-4c4f-b4f1-3b1de2dc7be3/nome/processamento_v2_29c515465102f7ae59f1d791cc9f1669_13-09-2023-08-40-20_401.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/c06d48b3-2784-46f9-911d-b55885df14dd/nome/processamento_v2_f28060d378101063ef7bd103a0b23e54_13-09-2023-08-40-41_2062.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/627f1b66-5d00-4151-9f5b-c875c1925efc/nome/processamento_v2_4ae496957e5f46c57649c145ea7a0d4a_13-09-2023-08-40-42_2530.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/cd2a4a41-a3bb-4c78-bcbb-0aa700eacb67/nome/processamento_v2_b16f28fdaf3bfe82501bf1ce645b01ac_13-09-2023-08-40-53_5506.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/d6b5323c-8f5a-45ed-ba07-08122ce7cd28/nome/processamento_v2_e535e662d15c8e45e977d8bfa8f53d28_13-09-2023-08-41-17_509.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/fe01af8c-5f8b-4feb-a8c8-7139c5ac5b57/nome/processamento_v2_086e7833ddb067f2697a908333851ab2_13-09-2023-08-41-29_3236.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/029a84d9-c93e-43d9-adf4-73a5fbdce095/nome/processamento_v2_26a6b69feece7b087fb627c1d3fc0014_13-09-2023-08-41-34_543.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/11920f91-f050-4ad8-bf9a-42d9f61ae512/nome/processamento_v2_1da0f85317336e7e6db327a2d4fc2954_13-09-2023-08-41-47_5139.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/770a13c5-5264-43c5-9390-416224178480/nome/processamento_v2_6fa69bbe3aa4ae2106a503537a2539e7_13-09-2023-08-41-52_163.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/6ee71637-eb5c-4767-94c6-925bf0b83425/nome/processamento_v2_9b1b9e08056dc8a2ac59133364f6e112_13-09-2023-08-42-07_8150.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/41e5f0af-ff1f-4153-82ef-cbf54b0d8128/nome/processamento_v2_533a2f7c0a046c5eb2002f8de00c1334_13-09-2023-08-42-16_86.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/7834d3e9-f0f6-4d95-a2c0-9bdc096e2844/nome/processamento_v2_2875ee770609ee98751c61fcbeb1716d_13-09-2023-08-42-27_5806.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/6bef212a-723d-4cee-a7a4-19fd62f1a09f/nome/processamento_v2_ba78e280a1566c048b6444941f661ad4_13-09-2023-08-42-41_5925.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/340b0b4d-edeb-4289-89ed-eb0c1b10f442/nome/processamento_v2_0111581714ab1dbf918ebc610e205321_13-09-2023-08-42-55_8580.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/44ee93ef-87fc-4a9a-810f-b8db61414abc/nome/processamento_v2_1baee1944cce82427221a0729bbbcab7_13-09-2023-08-43-02_4437.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/89e7194d-40e8-45e1-b7a8-1314a781ba3d/nome/processamento_v2_1b85ec97dfa58c5cc189f78fcc4a3b8e_13-09-2023-08-43-44_6719.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/523d5b72-7e26-4d53-983d-dd516cd7be68/nome/processamento_v2_3f1645baf7b55caff04de6069b6cf89b_13-09-2023-08-43-35_9554.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/3491413d-09fa-44f8-94bd-82f46907afb2/nome/processamento_v2_a2f21e897aec3e1842d105a5c7993fe5_13-09-2023-08-43-38_4651.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/a5a11972-4016-4f4a-a96e-83a4fcae3f56/nome/processamento_v2_59f851fbd743a137ea6c0bb18b089261_13-09-2023-08-43-51_6564.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/906934c9-8e30-4a17-9909-b8eef73f2145/nome/processamento_v2_a64cbe5e0fc672b3678dcfee2b944141_13-09-2023-08-44-01_7362.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/8d5e2dad-4a9f-4d3a-8469-18ae58224a70/nome/processamento_v2_397ccc1aabc17e3491a39267095a5d29_13-09-2023-08-44-15_7942.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/b228e780-bb98-4533-aed4-8aed05a0f888/nome/processamento_v2_e92106b53fa72573da1983eb46f23fb3_13-09-2023-08-44-30_2312.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/b89c05ed-58a5-43ab-aae6-121bbf6b4a2e/nome/processamento_v2_e8287c5648182cd937328834bc52b8b4_13-09-2023-08-44-40_5990.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/162fc5de-9674-4ccc-9c12-6bfa76230f46/nome/processamento_v2_3f0e537eb9499cfed791d2b2acadc7c0_13-09-2023-08-44-58_8343.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/c33ea98e-0fb0-4a48-82eb-1226f0bf0f73/nome/processamento_v2_a001eb80810575b90d511289d6908f8d_13-09-2023-08-45-01_2206.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/501b5099-b2e7-470a-a10f-1f9b807d5162/nome/processamento_v2_d3fe8bf03c3d832ca173f46c23162f57_13-09-2023-08-45-13_1858.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/ba67b1cd-4822-434b-ac6b-148caa2fabd5/nome/processamento_v2_9148b373bb1d5334a2eb5e5e279b5077_13-09-2023-08-45-26_4248.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/a365f465-20c4-48e0-b037-2a9dadb5055f/nome/processamento_v2_0349b74a31df16f5f4fa2671b06b30dd_13-09-2023-08-45-34_6504.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/7b7677cb-e106-4a76-bb5d-7ad726f85002/nome/processamento_v2_ad7ffd934d064fb9b79ab35dc5d074e9_13-09-2023-08-45-47_6438.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/c20efde2-794f-4250-a09a-6f942ab43a28/nome/processamento_v2_3fcea0cb167a0cf228e9a0bc72f41d87_13-09-2023-08-45-59_1057.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/c50cc073-64cb-4f40-9bca-868f82b6cee0/nome/processamento_v2_41a6606c4a800a1cd1ecb840c07e65a4_13-09-2023-08-46-08_1317.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/8c220b57-ad3d-4a7f-86bd-47a6d2597d6d/nome/processamento_v2_361a8f3c23ed22886a8a8e80af41bdcc_13-09-2023-08-46-31_6871.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/500e42ce-da6d-4789-ba8c-ee3a2f2bf2c8/nome/processamento_v2_1fd84932aa207f2fefe683ddf099adff_13-09-2023-08-46-43_6289.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/075a15ee-f6ff-4156-ba29-8e0aef8676da/nome/processamento_v2_6c58f61d7e33f4fd575da17f6fbc17ab_13-09-2023-08-46-47_5166.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/47bcd87f-b7a7-4898-835f-4fd04cd28535/nome/processamento_v2_eb889b82327603700a15c74b6753715b_13-09-2023-08-46-54_185.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/6d90c0a7-2d83-4618-b727-6b108f75fc00/nome/processamento_v2_92d44369eb22cee5c67687a234e374b7_13-09-2023-08-47-07_3344.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/329adb1a-3039-40de-aa70-97c54329b227/nome/processamento_v2_5010efcc0f9caa9f6fcd8a48d5068db1_13-09-2023-08-47-22_6376.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/55cc0804-8a90-4b3b-9bae-a50fe32a6590/nome/processamento_v2_85dd9ea8458d627aa4bdd61841fdbc13_13-09-2023-08-47-39_9989.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/8357a56f-f6cc-497b-aa8e-181974c8df9e/nome/processamento_v2_8b8b8eb45a83b2252fe1de5eb80af082_13-09-2023-08-47-40_327.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/c56fd48b-eab0-4ee7-88f1-e175be2c1cfb/nome/processamento_v2_1ffb9e24c9c64c035dd18eea836de979_13-09-2023-08-47-55_8730.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/7e320df3-5463-4207-9a34-d5c764198488/nome/processamento_v2_7913a395c63f7ea1919697a571de1b8b_13-09-2023-08-48-09_4154.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/d4d13a17-9348-4b32-a682-890fb3ce4f04/nome/processamento_v2_df467295b1dbdcb09ccf53226d25cbbe_13-09-2023-08-48-19_1086.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/49b06c22-c4c2-45fb-a88e-c7feb0f1d205/nome/processamento_v2_0da3a87759832b986cdb19dac10b78e4_13-09-2023-08-48-29_7383.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/9b253547-5d12-4059-9d79-ad48029001b7/nome/processamento_v2_a41b488b33fffaf0f4375047e908189c_13-09-2023-08-48-42_6070.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/7c7c6b5b-867a-4eb4-8e55-39d6891faf67/nome/processamento_v2_2285429376a09022d48cc93c42586676_13-09-2023-08-49-05_8107.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/1f1809b9-a332-4534-849d-6bf3f4328e6d/nome/processamento_v2_c6c008ec3411a6287d5fe4f7db593622_13-09-2023-08-49-04_660.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/e14bd232-8ecd-4068-9e88-fcff00ba90d5/nome/processamento_v2_2787840e0c2c9884b3738f3bd8d15676_13-09-2023-08-49-16_2031.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/cb7f3b4f-5161-4a7d-8274-dc6f816fedf5/nome/processamento_v2_6ac451e57df0cc5c18740f62a83ffe45_13-09-2023-08-49-27_375.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/804902fd-fa15-4c99-aea1-c8ed66f078f4/nome/processamento_v2_bcf8a52b56185ad9badabd79c99ecee3_13-09-2023-08-49-39_9809.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/1cf95c82-dd91-4195-9b91-75b58b898c94/nome/processamento_v2_9381854c462267ede80a84926cedc438_13-09-2023-08-49-50_6185.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/4857c069-7e1e-4ae9-85b6-b77669668162/nome/processamento_v2_99ba556e10679b64d1bb082b61111b77_13-09-2023-08-50-03_5094.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/1fe83d6f-17ea-462c-ad1a-e0dd04f706b6/nome/processamento_v2_cc21651795c73bc7ca79d17b2ad14c83_13-09-2023-08-50-20_6484.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/e3757010-9da2-4248-84d3-7f17622490e6/nome/processamento_v2_67befdf70a9610caef61883200c3d2ee_13-09-2023-08-50-24_371.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/3bc112cf-6ec8-420c-9c7c-15ca89a06310/nome/processamento_v2_d96519ca7eadaa60e94493f0d4dc9cbb_13-09-2023-08-50-39_4647.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/efb24ec1-88cd-4a4c-b4fb-f96ecf754bb2/nome/processamento_v2_e78a916d956b2328d401da0e9fe367a4_13-09-2023-08-50-47_2902.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/6077514e-42b3-46c8-82ec-2288712a7a68/nome/processamento_v2_334431b2efd5a8884bc2dc1c7a22ba2f_13-09-2023-08-51-04_9874.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/19e96f41-b987-44b0-86d4-19f5658b11b8/nome/processamento_v2_822290ff046ddac54f801ff97b9228f4_13-09-2023-08-51-15_3783.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/ba089818-25c7-47cf-bff2-d9126b41ea45/nome/processamento_v2_1e2ff63ddd7a99d021631ffeac90047f_13-09-2023-08-51-24_3612.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/493c4e03-22ba-4812-9886-cc8b57a1b7a7/nome/processamento_v2_13d771523ccd7f27f9504dd5eb84b96d_13-09-2023-08-51-34_631.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/6673fd15-a585-4c7b-ae3c-faf2d7d69640/nome/processamento_v2_58b147d5c619209c7019250a7e4a3fbf_13-09-2023-08-51-46_2279.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/04564efd-02c1-4150-937f-58eef347a901/nome/processamento_v2_9097b1e77e9fe43569e59c50ea5a57bb_13-09-2023-08-51-58_4197.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/19a29762-aad6-428c-b318-256aafc72cf3/nome/processamento_v2_d06a1bcb60e1379639d504f79b481ac4_13-09-2023-08-52-12_6751.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/23ad844b-1b49-421a-91cd-2499f6571209/nome/processamento_v2_7a504a703bf2938fd2ac3682e07b0313_13-09-2023-08-52-26_5659.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/1a36acde-9453-4fbe-ae26-b242e50bd766/nome/processamento_v2_abdb0985dfb3521f6db30cfb9ec4bfa5_13-09-2023-08-52-33_4151.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/77f652f9-8d64-4d06-bced-7718b0d51ae8/nome/processamento_v2_7292f61c1f0bb017a4d525268c0b422b_13-09-2023-08-52-44_7203.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/8420be46-5106-4faf-bfff-d027fbfa55fa/nome/processamento_v2_d35f3d5271dcd78f83ada4e02e76f3be_13-09-2023-08-52-57_7871.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/ccce7775-a69e-40ce-8ab6-72be8e790e8d/nome/processamento_v2_50641bcbbe90a3fc7e5efc519e795f2e_13-09-2023-08-53-09_3692.ret/retorno/download",
            "https://octo.oliveiratrust.com.br/carteira/operacao/effa92a9-4b78-4244-a20e-5a40562ea113/nome/processamento_v2_9c4412f5bed989e2b48e70590f8fc1c0_13-09-2023-08-53-23_4648.ret/retorno/download",
        ];

        $this->info('inicio baixar retorno');

        foreach($links as $link) {
            $this->info("Baixando retorno do link: {$link}");
            $response = Http::get($link);
            $this->info($response->status());
            break;
        }

        $this->info('Fim baixar retorno');

        return 0;
    }
}
