@extends('common.layout')
@section('title', 'Ferias')
@section('script')
<script type="text/javascript">
	let links = document.querySelector('.excluir');

	// links.forEach(function(link) {
	// 	link.addEventListener('click', function(event) {
	// 		event.preventDefault(); // optional: prevents the default link behavior
	// 		const url = event.currentTarget.dataset.route;
	// 		console.log(url);
	// 		// fetch(url, {
	// 		// 		method: 'DELETE',
	// 		// 		headers: {
	// 		// 			'Content-Type': 'application/json',
	// 		// 			'X-CSRF-TOKEN': '{{ csrf_token() }}', // Send CSRF token
	// 		// 			// 'Authorization': 'Bearer seu_token_aqui' // se necessário
	// 		// 		}
	// 		// 	})
	// 		// 	.then(response => {
	// 		// 		if (response.ok) {
	// 		// 			console.log('Recurso deletado com sucesso.');
	// 		// 		} else {
	// 		// 			console.error('Erro ao deletar:', response.status);
	// 		// 		}
	// 		// 		window.location.reload();
	// 		// 	})
	// 		// 	.catch(error => {
	// 		// 		console.error(error);
	// 		// 	});
	// 	});
	// });



	// function excluir(el) {
	// 	console.log(el.attributes['data-route']);
	// }
</script>
@endsection
@section('content')
<!-- <div> -->
<div class="row">
	<div class="col text-center d-flex justify-content-between align-items-start">
		<h1>Agendamento de Férias</h1>
		<div>
			<a href="{{ route('jobs.ferias.create') }}" title="adicionar"><i class="bi bi-plus-square fs-4"></i></a>
			<a href="{{ route('jobs.dashboard.index') }}" title="Dashboard"><i class="bi bi-speedometer2 fs-4"></i></a>
		</div>
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
			@forelse($ferias as $feria)
			<tr>
				<td>{{ $feria->inicio->format('d/m/Y') }}</td>
				<td>{{ $feria->fim->format('d/m/Y') }}</td>
				<td>{{ $feria->qtd_dias }}</td>
				<td>{{ $feria->ativo }}</td>
				<td>{{ $feria->observacao }}</td>
				<td>
					<a href="{{ route('jobs.ferias.edit', [ 'id' => $feria->id ]) }}">Editar</a><!-- onclick="javascript:excluir(this)" -->
					<a class="excluir" href="#" data-route="{{ route('jobs.ferias.destroy', [ 'id' => $feria->id ]) }}">Excluir</a>
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
