@extends('common.layout')
@section('title', 'Ferias')
@section('content')
<!-- <div> -->
<div class="row">
	<div class="col text-center">
		<h1>Agendamento de Férias</h1>
		<a href="{{ route('jobs.ferias.create') }}" title="adicionar"><i class="bi bi-plus-square fs-4"></i></a>
	</div>
</div>
<div class="row">
	<div class="col text-end">
		<a></a>
	</div>
</div>
<div class="row">
	<div class="col">
		<table class="table table-responsive">
			<tr>
				<th>Inicio</th>
				<th>Fim</th>
				<th>Dias</th>
				<th>Ativo</th>
				<th>Observação</th>
				<th>Ações</th>
			</tr>
			@forelse($lista_ferias as $ferias)
			<tr>
				<td>{{ $ferias->inicio->format('d/m/Y') }}</td>
				<td>{{ $ferias->fim->format('d/m/Y') }}</td>
				<td>{{ $ferias->qtd_dias }}</td>
				<td>{{ $ferias->ativo }}</td>
				<td>{{ $ferias->observacao }}</td>
				<td>
					<a href="{{ route('jobs.ferias.edit', [ 'ferias' => $ferias->id ]) }}">Editar</a>
					<a href="{{ route('jobs.ferias.destroy', [ 'ferias' => $ferias->id ]) }}">Excluir</a>
				</td>
			</tr>
			@empty
			<tr>
				<td colspan="4">Nenhumas férias agendadas até o momento.</td>
			</tr>
			@endforelse
		</table>
	</div>
</div>
<!-- </div> -->
@endsection
