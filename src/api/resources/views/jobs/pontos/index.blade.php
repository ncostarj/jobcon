@extends('common.layout')
@section('title', 'Ponto')
@section('content')
<!-- <div> -->
<div class="row">
	<div class="col text-center d-flex justify-content-between align-items-start">
		<h1>Marcações do ponto</h1>
		<div>
			<a href="{{ route('jobs.pontos.create') }}">Marcar</a>
			<a href="{{ route('jobs.dashboard.index') }}" title="Dashboard">
				<!-- <i class="bi bi-plus-square fs-4"></i> -->
				Dash
			</a>
		</div>
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
				<td>{{ $ponto->entrada->horaFormatted??'-' }}</td>
				<td>{{ $ponto->almoco_saida->horaFormatted??'-' }}</td>
				<td>{{ $ponto->almoco_retorno->horaFormatted??'-' }}</td>
				<td>{{ $ponto->saida->hora->horaFormatted??'-' }}</td>
				<td>{{ $ponto->observacao ?? '-' }}</td>
				<td>
					<a href="{{ route('jobs.pontos.edit', [ 'id' => $ponto->id ]) }}">Editar</a>
					<a href="">Apagar</a>
				</td>
			</tr>

			@empty
			<tr>
				<td colspan="5">Nenhum registro encontrado.</td>
			</tr>			
			@endforelse
			<tr>
				<td colspan="5">{{ $pontos->links('pagination::bootstrap-5') }}</td>
			</tr>			
		</table>
	</div>
</div>
<!-- </div> -->
@endsection
