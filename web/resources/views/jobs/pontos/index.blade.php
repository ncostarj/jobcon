@extends('common.layout')
@section('title', 'Ponto')
@section('content')
<!-- <div> -->
<div class="row">
	<div class="col text-center">
		<h1>Marcações do ponto</h1>
	</div>
</div>
<div class="row">
	<div class="col">
		<table class="table table-responsive">
			<tr>
				<td>Data</td>
				<td>Categoria</td>
				<td>Pedir Ajuste</td>
				<td>Entrada</td>
				<td>Almoço</td>
				<td>Retorno</td>
				<td>Saida</td>
				<td>Observação</td>
				<td>Ações</td>
			</tr>
			@forelse($pontos as $ponto)
			<tr>
				<td>{{ $ponto->dia->format('d/m/Y') }}</td>
				<td><i class="{{ $icons[$ponto->categoria]??'' }}"></i></td>
				<td>{{ $ponto->pedir_ajuste ? 'sim':'não' }}</td>
				<td>{{ $ponto->entrada->hora??'-' }}</td>
				<td>{{ $ponto->almoco_saida->hora??'-' }}</td>
				<td>{{ $ponto->almoco_retorno->hora??'-' }}</td>
				<td>{{ $ponto->saida->hora??'-' }}</td>
				<td>{{ $ponto->observacao ?? '-' }}</td>
				<td>
					<a href="{{ route('jobs.pontos.edit', [ 'ponto' => $ponto->id ]) }}">Editar</a>
					<a href="">Apagar</a>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="5">Nenhum registro encontrado.</td>
			</tr>
			@endforelse
		</table>
	</div>
</div>
<!-- </div> -->
@endsection
