<?php

namespace App\Http\Controllers\Api;

use App\Domain\Jobs\Resources\FeriasResource;
use Illuminate\Http\Request;
use App\Domain\Jobs\Services\FeriasService;

class FeriasController extends BaseApiController
{
	public function index(FeriasService $feriasService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), FeriasResource::toArray($feriasService->search($request)));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
	}

	public function create()
	{
		try {
			$response = $this->response(200, trans('api.200'), FeriasResource::toArray($feriasService->search($request)));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
		// $usuario_id = User::where([['email_comercial', '=', 'newton.costa@oliveiratrust.com.br']])->first()->id;
		// $action = route('jobs.ferias.store');
		// return view('jobs.ferias.form', compact('action', 'usuario_id'));
	}

	public function store(Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), FeriasResource::toArray($feriasService->search($request)));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
		// Salvar dados na base de dados
		// $ferias = new Ferias;
		// $ferias->fill($request->only('inicio', 'fim', 'qtd_dias', 'observacao'));
		// $ferias->usuario()->associate(User::where('id', $request->usuario_id)->first());
		// $ferias->save();
		// return redirect()->route('jobs.ferias.index');
	}

	public function update(string $id, Request $requests)
	{
		try {
			$response = $this->response(200, trans('api.200'), FeriasResource::toArray($feriasService->search($request)));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
		// $ferias->fill($request->only('inicio', 'fim', 'qtd_dias', 'observacao'));
		// $ferias->usuario()->associate(User::where('id', $request->usuario_id)->first());
		// $ferias->save();
		// return redirect()->route('jobs.ferias.index');
	}

	public function destroy($ferias)
	{
		// Apagar dados da base de dados
		try {
			$response = $this->response(200, trans('api.200'), FeriasResource::toArray($feriasService->search($request)));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
	}

	public function verifyDiasAteFerias(FeriasService $feriasService, Request $request)
	{
		try {
			$response = $this->response(200, trans('api.200'), $feriasService->verifyDiasAteFerias($request->all()));
		} catch (\Throwable $th) {
			$this->log($th);
			$response = $this->response(500, trans('api.500'));
		}
		return $response;
	}
}
