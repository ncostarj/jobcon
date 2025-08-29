@extends('common.layout')
@section('title', 'Ponto')
@section('content')
<!-- <div> -->
<div class="row">
	<div class="col text-center">
		<h1>Marcações do ponto</h1>
		<!-- <a href="{{-- route('jobs.horarios.create') --}}"><i class="bi bi-calendar-plus"></i> Incluir horário</a> -->
	</div>
</div>
<div class="row">
	<div class="col">
		<table class="table table-responsive suave">
			<tbody>
				<tr>
					<th>Competência</th>
					<th>Tipo</th>
					<th>Base</th>
					<th>Liquido</th>
					<th>Vencimentos</th>
					<th>Descontos</th>
					<th>Total Liquido</th>
					<th>Ações</th>
				</tr>
				@forelse($contracheques as $contracheque)
				<tr>
					<td>{{ $contracheque->competencia->format('m/Y') }}</td>
					<td>{{ $contracheque->tipo }}</td>
					<td>{{ $contracheque->salario_base_formatted }}</td>
					<td>{{ $contracheque->salario_liquido_formatted }}</td>
					<td>{{ $contracheque->total_vencimentos_formatted }}</td>
					<td>{{ $contracheque->total_descontos_formatted }}</td>
					<td>{{ $contracheque->total_liquido_formatted }}</td>
					<td>
						<a href="{{ route('jobs.contracheques.edit', [ 'contracheque' => $contracheque->id ]) }}">Editar</a>
						<a href="{{ route('jobs.contracheques.destroy', [ 'contracheque' => $contracheque->id ]) }}">Excluir</a>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="5">Nenhum registro encontrado.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
<!-- </div> -->
@endsection
