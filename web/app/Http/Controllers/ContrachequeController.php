<?php

namespace App\Http\Controllers;

use App\Models\Contracheque;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Http\Request;

class ContrachequeController extends Controller
{



	public function index(Request $request) {

		$contracheques = Contracheque::orderBy('competencia','desc')->get();

		return view('jobs.contracheques.index', compact('contracheques'));
	}

	public function create() {
        $usuario_id = User::where([ ['email_comercial', '=', 'newton.costa@oliveiratrust.com.br'] ])->first()->id;
		$empresas = Empresa::where('user_id', $usuario_id)->get();
		$action = route('jobs.contracheques.store');
        return view('jobs.contracheques.form', compact('action', 'empresas','usuario_id'));
    }

	public function edit(Contracheque $contracheque, Request $request) {
		$usuario_id = User::where([ ['email_comercial', '=', 'newton.costa@oliveiratrust.com.br'] ])->first()->id;
		$empresas = Empresa::where('user_id', $usuario_id)->get();
		$action = route('jobs.contracheques.update', [ 'contracheque' => $contracheque->id ]);
		return view('jobs.contracheques.form', compact('action', 'empresas', 'contracheque','usuario_id'));
	}

	public function store(Request $request) {
		$contracheque = new Contracheque;
		$contracheque->fill($request->only('competencia', 'tipo', 'salario_base','salario_liquido', 'total_vencimentos','total_descontos'));
		$contracheque->usuario()->associate(User::where('id', $request->usuario_id)->first());
		$contracheque->empresa()->associate(Empresa::where('id', $request->empresa_id)->first());
		$contracheque->save();
		return redirect()->route('jobs.contracheques.index');
	}

	public function update(Contracheque $contracheque, Request $request) {
        $contracheque->fill($request->only('competencia','tipo','salario_base','salario_liquido', 'total_vencimentos', 'total_descontos'));
		$contracheque->usuario()->associate(User::where('id', $request->usuario_id)->first());
		$contracheque->empresa()->associate(Empresa::where('id', $request->empresa_id)->first());
		$contracheque->save();
		return redirect()->route('jobs.contracheques.index');
	}

	public function destroy(Contracheque $contracheque) {
		$contracheque->delete();
		return redirect()->route('jobs.contracheques.index');
	}

}
