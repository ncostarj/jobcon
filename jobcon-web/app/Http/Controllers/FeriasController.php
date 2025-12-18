<?php

namespace App\Http\Controllers;

use App\Domain\Jobs\Services\FeriasService;
use App\Models\Ferias;
use App\Models\User;
use Illuminate\Http\Request;

class FeriasController extends Controller
{
	public function index(FeriasService $feriasService, Request $request)
	{
		$request->merge([ 'usuario_id' => auth()->user()->id ]);
		$ferias = $feriasService->search($request);
		return view('jobs.ferias.index', compact('ferias'));
	}

	public function create()
	{
		$usuario_id = auth()->user()->id;
		$action = route('jobs.ferias.store');
		return view('jobs.ferias.form', compact('action', 'usuario_id'));
	}

	public function store(FeriasService $feriasService, Request $request)
	{
		$feriasService->create($request);
		return redirect()->route('jobs.ferias.index');
	}

	public function edit(FeriasService $feriasService, string $id)
	{
		$usuario_id = auth()->user()->id;
		$ferias = $feriasService->find($id);
		$action = route('jobs.ferias.update', ['ferias' => $ferias->id]);
		return view('jobs.ferias.form', compact('ferias', 'action', 'usuario_id'));
	}

	public function update(FeriasService $feriasService, string $id, Request $request)
	{
		$feriasService->update($id, $request);
		return redirect()->route('jobs.ferias.index');
	}

	public function destroy(FeriasService $feriasService, string $id)
	{
		$feriasService->delete($id);
	}
}
