@extends('common.layout')
@section('title', '&raquo; Contracheque &raquo; Listar')
@section('script')
<script type="text/javascript">
	document
		.querySelector('.excluir')
		.addEventListener('click', function(event) {
			event.preventDefault(); // optional: prevents the default link behavior
			const url = event.currentTarget.dataset.route;
			console.log(url);
			// fetch(url, {
			// 		method: 'DELETE',
			// 		headers: {
			// 			'Content-Type': 'application/json',
			// 			'X-CSRF-TOKEN': '{{ csrf_token() }}', // Send CSRF token
			// 			// 'Authorization': 'Bearer seu_token_aqui' // se necessário
			// 		}
			// 	})
			// 	.then(response => {
			// 		if (response.ok) {
			// 			console.log('Recurso deletado com sucesso.');
			// 		} else {
			// 			console.error('Erro ao deletar:', response.status);
			// 		}
			// 		window.location.reload();
			// 	})
			// 	.catch(error => {
			// 		console.error(error);
			// 	});
		});
</script>
@endsection
@section('content')
<div class="row mt-2">
	<div class="col text-center d-flex justify-content-between align-items-start">
		<h1>Lista de Contracheques</h1>
		<div>
			<a href="{{ route('jobs.contracheques.create') }}" title="Incluir Contracheques"><i class="bi bi-plus-square fs-4"></i></a>
			<a href="{{ route('jobs.dashboard.index') }}" title="Dashboard">
				<!-- <i class="bi bi-plus-square fs-4"></i> -->
				Dash
			</a>
		</div>
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
					<th>Vencimentos</th>
					<th>Descontos</th>
					<th>Liquido</th>
					<th>Total Liquido</th>
					<th>Ações</th>
				</tr>
				@forelse($contracheques as $contracheque)
				<tr>
					<td>{{ $contracheque->competencia->format('m/Y') }}</td>
					<td>{{ $contracheque->tipo }}</td>
					<td>{{ $contracheque->salario_base_formatted }}</td>
					<td>{{ $contracheque->total_vencimentos_formatted }}</td>
					<td>{{ $contracheque->total_descontos_formatted }}</td>
					<td>{{ $contracheque->salario_liquido_formatted }}</td>
					<td>{{ $contracheque->total_liquido_formatted }}</td>
					<td>
						<a href="{{ route('jobs.contracheques.edit', [ 'contracheque' => $contracheque->id ]) }}"><span class="bi bi-pencil"></span></a>
						<a class="excluir" href="#" data-route="{{ route('jobs.contracheques.destroy', [ 'contracheque' => $contracheque->id ]) }}"><span class="bi bi-trash"></span></a>
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
