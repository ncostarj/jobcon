<?php

namespace App\Http\Controllers;

use App\Domain\Jobs\Models\Empresa;
use App\Domain\Jobs\Services\ContrachequeService;
use App\Http\Requests\ContrachequeRequest;
use Illuminate\Http\Request;

class ContrachequeController extends Controller
//  implements ControllerInterface
{
	public function index(ContrachequeService $contrachequeService, Request $request)
	{
		return view('jobs.contracheques.index', ['contracheques' => $contrachequeService->search($request->all() + ['usuario_id' => auth()->user()->id])]);
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

	public function edit(string $id, ContrachequeService $contrachequeService, Request $request)
	{
		return view('jobs.contracheques.form', [
			'usuario' => auth()->user(),
			'empresas' => Empresa::all(),
			'contracheque' => $contrachequeService->find($id),
			'action' => route('jobs.contracheques.update', ['contracheque' => $id]),
		]);
	}

	public function store(ContrachequeService $contrachequeService, ContrachequeRequest $request)
	{
		$contrachequeService->create($request->validated());
		return redirect()->route('jobs.contracheques.index');
	}

	public function update(string $id, ContrachequeService $contrachequeService, ContrachequeRequest $request)
	{
		$contrachequeService->update($id, $request->validated());
		return redirect()->route('jobs.contracheques.index');
	}

	public function destroy(string $id, ContrachequeService $contrachequeService)
	{
		$contrachequeService->delete($id);
		// return redirect()->route('jobs.contracheques.index');
	}



	// public function index(Request $request)
	// {

	// 	$contracheques = Contracheque::orderBy('competencia', 'desc')->get();

	// 	return view('jobs.contracheques.index', compact('contracheques'));
	// }

	// public function create() {
	//     $usuario_id = User::where([ ['email_comercial', '=', 'newton.costa@oliveiratrust.com.br'] ])->first()->id;
	// 	$empresas = Empresa::where('user_id', $usuario_id)->get();
	// 	$action = route('jobs.contracheques.store');
	//     return view('jobs.contracheques.form', compact('action', 'empresas','usuario_id'));
	// }

	// public function edit(Contracheque $contracheque, Request $request) {
	// 	$usuario_id = User::where([ ['email_comercial', '=', 'newton.costa@oliveiratrust.com.br'] ])->first()->id;
	// 	$empresas = Empresa::where('user_id', $usuario_id)->get();
	// 	$action = route('jobs.contracheques.update', [ 'contracheque' => $contracheque->id ]);
	// 	return view('jobs.contracheques.form', compact('action', 'empresas', 'contracheque','usuario_id'));
	// }

	// public function store(Request $request) {
	// 	$contracheque = new Contracheque;
	// 	$contracheque->fill($request->only('competencia', 'tipo', 'salario_base','salario_liquido', 'total_vencimentos','total_descontos'));
	// 	$contracheque->usuario()->associate(User::where('id', $request->usuario_id)->first());
	// 	$contracheque->empresa()->associate(Empresa::where('id', $request->empresa_id)->first());
	// 	$contracheque->save();
	// 	return redirect()->route('jobs.contracheques.index');
	// }

	// public function update(Contracheque $contracheque, Request $request) {
	//     $contracheque->fill($request->only('competencia','tipo','salario_base','salario_liquido', 'total_vencimentos', 'total_descontos'));
	// 	$contracheque->usuario()->associate(User::where('id', $request->usuario_id)->first());
	// 	$contracheque->empresa()->associate(Empresa::where('id', $request->empresa_id)->first());
	// 	$contracheque->save();
	// 	return redirect()->route('jobs.contracheques.index');
	// }

	// public function destroy(Contracheque $contracheque) {
	// 	$contracheque->delete();
	// 	return redirect()->route('jobs.contracheques.index');
	// }

}
