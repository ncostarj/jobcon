@extends('common.layout')
@section('title','- Calendario')

@section('style')
@endsection

@section('script')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.17/locales-all.global.min.js'></script>
<script>
	let usuario = JSON.parse('@json($usuario)');

	class Gateway {

		send(url, params) {
			return fetch(url, params)
				.then(response => {
					if (!response.ok) {
						throw new Error(response.status);
					}

					return response.json();
				});
		}

		get(rota, data = null) {

			let params = {
				method: 'get',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
			};

			return this.send(rota, params);
		}

		post(rota, data) {
			let params = {
				method: 'post',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				body: data
			};

			return this.send(rota, params);
		}
	}

	(new Gateway())
	.get(`{{ route('api.v1.jobs.escalas.listar') }}`)
		.then((response) => {

			console.log(response);

			let eventos = [];

			response.data.forEach((escala) => {

				escala.escalacao.forEach((escalado) => {

					let evento = {
						start: escala.dia,
						title: escalado,
					};

					if (usuario.name.includes(escalado)) {
						evento.backgroundColor = '#f00'; // cor vermelha
						evento.borderColor = '#f00'; // cor vermelha;
						eventos.push(evento)
					}

				});
				
			});

			eventos.push({
				title: 'Férias Newton',
				start: '2025-07-28',
				end: '2025-08-12',
				backgroundColor: '#FF8C00'
			});

			var calendarEl = document.getElementById('calendar');
			var calendar = (new FullCalendar.Calendar(calendarEl, {
				initialView: 'dayGridMonth',
				locale: 'pt-BR',
				events: eventos,
				weekends: false,
				dayMaxEventRows: true,
				views: {
					dayGridMonth: {
						dayMaxEventRows: 6
					}
				}
			})).render();
		}).catch(error => {
			console.log(error);
		});
</script>
@endsection

@section('content')

<a href="{{ route('jobs.dashboard.index') }}">Voltar</a>

<div class="row">
	<div class="col">
		<div id="calendar"></div>
	</div>
</div>

@endsection