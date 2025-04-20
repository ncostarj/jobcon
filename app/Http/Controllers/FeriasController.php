<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\User;
use Illuminate\Http\Request;

class FeriasController extends Controller
{
    //

	public function index(Request $request) {
		$lista_ferias = Ferias::orderBy('inicio','desc')->get();
		// dd($lista_ferias);
        return view('jobs.ferias.index', compact('lista_ferias'));
    }

	public function create() {
		$usuario_id = User::where([ ['email_comercial', '=', 'newton.costa@oliveiratrust.com.br'] ])->first()->id;
		$action = route('jobs.ferias.store');
        return view('jobs.ferias.form', compact('action', 'usuario_id'));
    }

	public function store(Request $request) {
        // Salvar dados na base de dados
		$ferias = new Ferias;
		$ferias->fill($request->only('inicio', 'fim', 'qtd_dias','observacao'));
		$ferias->usuario()->associate(User::where('id', $request->usuario_id)->first());
		$ferias->save();
		return redirect()->route('jobs.ferias.index');
    }

	public function edit(Ferias $ferias) {
		$usuario_id = User::where([ ['email_comercial', '=', 'newton.costa@oliveiratrust.com.br'] ])->first()->id;
		$action = route('jobs.ferias.update', [ 'ferias' => $ferias->id ]);
        return view('jobs.ferias.form', compact('ferias','action', 'usuario_id'));
    }

	public function update(Request $request, Ferias $ferias) {
        $ferias->fill($request->only('inicio', 'fim', 'qtd_dias','observacao'));
		$ferias->usuario()->associate(User::where('id', $request->usuario_id)->first());
		$ferias->save();
		return redirect()->route('jobs.ferias.index');
    }

	public function destroy($ferias) {
        // Apagar dados da base de dados
    }
}
