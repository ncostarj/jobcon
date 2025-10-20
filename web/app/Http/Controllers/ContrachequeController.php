<?php

namespace App\Http\Controllers;

use App\Domain\Jobs\Contracts\ControllerInterface;
use App\Domain\Jobs\Models\Empresa;
use App\Domain\Jobs\Services\ContrachequeService;
use App\Http\Requests\ContrachequeRequest;
use Illuminate\Http\Request;

class ContrachequeController extends Controller implements ControllerInterface
{
	protected $contrachequeService;

	public function __construct(ContrachequeService $contrachequeService) {
		$this->contrachequeService = $contrachequeService;
	}

	public function index(Request $request)
	{
		return view('jobs.contracheques.index', ['contracheques' => $this->contrachequeService->search($request->all() + ['usuario_id' => auth()->user()->id])]);
	}

	// TODO buscar empresas pelo EmpresaService
	public function create()
	{
		return view('jobs.contracheques.form', [
			'usuario' => auth()->user(),
			'empresas' => Empresa::where('user_id', auth()->user()->id)->get(),
			'action' => route('jobs.contracheques.store')
		]);
	}

	public function edit(string $id, Request $request)
	{
		return view('jobs.contracheques.form', [
			'usuario' => auth()->user(),
			'empresas' => Empresa::all(),
			'contracheque' => $this->contrachequeService->find($id),
			'action' => route('jobs.contracheques.update', ['contracheque' => $id]),
		]);
	}

	public function store(Request $request)
	{
		$this->contrachequeService->create($request->validated());
		return redirect()->route('jobs.contracheques.index');
	}

	public function update(string $id, Request $request)
	{
		$this->contrachequeService->update($id, $request->validated());
		return redirect()->route('jobs.contracheques.index');
	}

	public function destroy(string $id)
	{
		$this->contrachequeService->delete($id);
		// return redirect()->route('jobs.contracheques.index');
	}

}
