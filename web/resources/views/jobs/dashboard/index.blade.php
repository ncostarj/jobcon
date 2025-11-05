@extends('common.layout')
@section('title','- Home')

@section('style')

<style>
	.openable {
		cursor: pointer;
	}

	.suave {
		font-size: 0.9vw;
	}

	.dia {
		font-size: 8vw;
	}

	.hora {
		font-size: 5vw;
	}

	.modal-mask {
		position: fixed;
		z-index: 9998;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(0, 0, 0, 0.5);
		display: table;
		transition: opacity 0.3s ease;
	}

	.destaque {
		font-weight: bolder;
	}

	.modal-wrapper {
		display: table-cell;
		vertical-align: middle;
	}

	.modal-container {
		width: 70vw;
		margin: 0px auto;
		padding: 20px 30px;
		background-color: #fff;
		border-radius: 2px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
		transition: all 0.3s ease;
		font-family: Helvetica, Arial, sans-serif;
	}

	.modal-header h3 {
		margin-top: 0;
		color: #000;
	}

	.modal-body {
		margin: 20px 0;
		color: #000;
	}

	.modal-default-button {
		float: right;
	}

	/*
	* The following styles are auto-applied to elements with
	* transition="modal" when their visibility is toggled
	* by Vue.js.
	*
	* You can easily play with the modal transition by editing
	* these styles.
	*/

	.modal-enter {
		opacity: 0;
	}

	.modal-leave-active {
		opacity: 0;
	}

	.modal-enter .modal-container,
	.modal-leave-active .modal-container {
		-webkit-transform: scale(1.1);
		transform: scale(1.1);
	}
</style>

@endsection

@section('script')

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.17/locales-all.global.min.js'></script>

<script type="text/javascript">
	let usuario = JSON.parse('@json($dados->usuario)');
	let projeto = JSON.parse('@json($dados->projeto)');

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

	class CalendarioHelper {
		static getDayOfWeek(day) {
			let dayOfWeek = '';
			switch (day) {
				case 0:
					dayOfWeek = 'Domingo';
					break;
				case 1:
					dayOfWeek = 'Segunda';
					break;
				case 2:
					dayOfWeek = 'Terça';
					break;
				case 3:
					dayOfWeek = 'Quarta';
					break;
				case 4:
					dayOfWeek = 'Quinta';
					break;
				case 5:
					dayOfWeek = 'Sexta';
					break;
				case 6:
					dayOfWeek = 'Sabado';
					break;
			}
			return dayOfWeek;
		}
	}

	// (new Gateway())
	// .get(`{{ route('api.v1.jobs.escalas.listar') }}`)
	// 	.then((response) => {

	// 		let eventos = [];

	// 		response.data.forEach((escala) => {

	// 			escala.escalacao.forEach((escalado) => {

	// 				let evento = {
	// 					start: escala.dia,
	// 					title: escalado,
	// 				};

	// 				if (usuario.name.includes(escalado)) {
	// 					evento.backgroundColor = '#f00'; // cor vermelha
	// 					evento.borderColor = '#f00'; // cor vermelha;
	// 					eventos.push(evento)
	// 				}

	// 			});

	// 		});

	// 		eventos.push({
	// 			title: 'Férias Newton',
	// 			start: '2025-07-28',
	// 			end: '2025-08-12',
	// 			backgroundColor: '#FF8C00'
	// 		});

	// 		var calendarEl = document.getElementById('calendar');
	// 		var calendar = (new FullCalendar.Calendar(calendarEl, {
	// 			initialView: 'dayGridMonth',
	// 			locale: 'pt-BR',
	// 			events: eventos,
	// 			weekends: false,
	// 			dayMaxEventRows: true,
	// 			views: {
	// 				dayGridMonth: {
	// 					dayMaxEventRows: 6
	// 				}
	// 			}
	// 		})).render();
	// 	}).catch(error => {
	// 		console.error(error);
	// 	});

	var appPonto = new Vue({
		el: '#ponto-app',
		data: {
			gateway: new Gateway(),
			pontoAssignForm: {
				usuarioId: '{{ $dados->usuario->id }}',
				categoria: 'home_office',
				pedir_ajuste: false,
				observacao_dia: '',
				observacao_horario: ''
			},
			pontoSearchForm: {
				usuarioId: '{{ $dados->usuario->id }}',
				mes: '',
				meses: [],
				ordenacao: ''
			},
			listaPontos: [],
			bancoHoras: {},
			resumos: [],
			subtotalPontos: {
				debito: '00:00',
				credito: '00:00'
			},
			pontoLoading: true,
			calendario: {
				hoje: {
					dia: '',
					mes: '',
					ano: ''
				},
				qtdDiasMes: 0,
				qtdDiasAteFimMes: 0,
				qtdDiasAtePagamento: 0,
			},
			relogio: {
				hora: 1,
				minuto: 2,
				segundo: 3,
			},
		},
		methods: {
			carregarCalendario: function() {
				this.calendarioLoading = true;
				this.gateway
					.get(`{{ route('api.v1.jobs.calendario.index') }}`)
					.then((response) => {
						this.calendario = response.data;
						this.calendarioLoading = false;
					})
					.catch(error => {
						console.error(error);
					});
			},
			buscarMeses: function() {
				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.listar_meses') }}?usuario_id=${this.pontoAssignForm.usuarioId}`)
					.then((response) => {
						this.pontoSearchForm.meses = response.data;
					})
					.catch(error => {
						console.error(error);
					});
			},
			marcar: function(rota, tipo) {

				if (!this.pontoAssignForm.categoria) {
					alert('O campo categoria precisa estar preenchido!');
					return false;
				}

				let objDate = new Date();
				let dia = objDate.toISOString().replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})(.*)/, '$1-$2-$3');
				let hora = objDate.toTimeString().replace(/([0-9]{2}):([0-9]{2})(.*)/, '$1:$2');

				let form = {
					"usuario_id": this.pontoAssignForm.usuarioId,
					"dia": dia,
					"categoria": this.pontoAssignForm.categoria,
					"pedir_ajuste": this.pontoAssignForm.pedir_ajuste ? 1 : 0,
					"observacao": this.pontoAssignForm.observacao_dia,
					"horarios": [{
						"tipo": tipo,
						"hora": hora,
						"observacao": this.pontoAssignForm.observacao_horario,
					}]
				};

				this.gateway
					.post(rota, JSON.stringify(form))
					.then((response) => {
						this.pontoForm = {
							categoria: 'home_office',
							usuarioId: '{{ $dados->usuario->id }}',
							pedir_ajuste: false,
							observacao_dia: '',
							observacao_horario: '',
						};
						this.listar();
					})
					.catch(error => {
						console.error(error);
					});
			},
			listar: function() {
				this.pontoLoading = true;
				let hoje = new Date();
				let month = hoje.getMonth() + 1;
				let year = hoje.getFullYear();

				if (this.pontoSearchForm.mes) {
					[year, month] = this.pontoSearchForm.mes.split('-');
				}

				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.listar') }}?mes=${month}&ano=${year}&usuario_id=${this.pontoAssignForm.usuarioId}&ordenacao=${this.pontoSearchForm.ordenacao??''}`)
					.then((response) => {
						this.pontoLoading = false;
						this.listaPontos = response.data;
						this.calcularSubtotal();
					})
					.catch(error => {
						console.error(error);
					});

				// this.resumo();
			},
			calcularSubtotal: function() {
				let hoje = new Date();
				let month = hoje.getMonth() + 1;
				let year = hoje.getFullYear();

				if (this.pontoSearchForm.mes) {
					[year, month] = this.pontoSearchForm.mes.split('-');
				}

				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.calcular_subtotal_horas') }}?mes=${month}&ano=${year}&usuario_id=${this.pontoAssignForm.usuarioId}`)
					.then((response) => {
						this.subtotalPontos = response.data;
					})
					.catch(error => {
						console.error(error);
					});
			},
			obterBancoHoras: function() {
				this.gateway
					.get(`{{ route('api.v1.jobs.frequencias.listarUltimoSaldo') }}?usuario_id=${this.pontoAssignForm.usuarioId}`)
					.then((response) => {
						this.bancoHoras = response.data;
					})
					.catch(error => {
						console.error(error);
					});
			},
			resumo: function() {
				let hoje = new Date();
				let month = hoje.getMonth() + 1;
				let year = hoje.getFullYear();

				if (this.pontoSearchForm.mes) {
					[year, month] = this.pontoSearchForm.mes.split('-');
				}

				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.resumo') }}?mes=${month}&ano=${year}&usuario_id=${this.pontoAssignForm.usuarioId}`)
					.then((response) => {
						console.log(response);
						this.resumos = response.data;
					})
					.catch(error => {
						console.error(error);
					});
			}
		},
		computed: {},
		created: function() {
			let hoje = new Date()
			let mesAtual = hoje.getMonth() + 1;
			let anoAtual = hoje.getFullYear();
			this.pontoSearchForm.mes = `${anoAtual}-${mesAtual}`;
			this.pontoSearchForm.ordenacao = 'desc';
			// this.obterBancoHoras();
			this.buscarMeses();
			this.listar();
			this.resumo();
			this.carregarCalendario();
		},
		mounted: function() {
			setInterval(() => {
				let date = new Date();
				let hora = date.getHours();
				let minuto = date.getMinutes();
				let segundo = date.getSeconds();
				this.relogio.hora = hora < 10 ? `0${hora}` : hora;
				this.relogio.minuto = minuto < 10 ? `0${minuto}` : minuto;
				this.relogio.segundo = segundo < 10 ? `0${segundo}` : segundo;
			}, 1000);
		}
	});

	var appTarefas = new Vue({
		el: '#tarefas-app',
		data: {
			gateway: new Gateway(),
			showModal: false,
			usuarioId: usuario.id,
			tarefaForm: {
				search: '',
				projeto: '',
				usuario: '',
				status: '',
				periodo: '',
				listaProjetos: [],
				listaUsuarios: [],
				listaStatuses: [],
			},
			qtdTarefas: 0,
			tarefa: null,
			semana: {},
			feriados: [],
			listaTeste: [],
			listaSprints: [],
			sprintAtiva: {},
			listaTarefas: [],
			listaUsuarios: [],
			worklogsPorSemana: [],
			totalEmSegundos: 0,
			totalPorSemanaEmSegundos: 0,
			totalPorSemanaEmHoras: '0h',
			tarefaLoading: false
		},
		methods: {
			// openModal: function(tarefa) {
			// 	this.showModal = true;
			// 	this.tarefa = tarefa;
			// },
			// listarProjetos: function() {
			// 	this.gateway
			// 		.get(`{{ route('api.v1.jobs.tarefas.projetos') }}`)
			// 		.then((response) => {
			// 			this.tarefaForm.listaProjetos = response.data;

			// 			let proj = this.tarefaForm.listaProjetos.find((p) => p.key == projeto);

			// 			if (proj) {
			// 				this.tarefaForm.projeto = proj.key;
			// 				this.listartStatus(proj.id);
			// 			}

			// 		}).catch(error => {
			// 			console.error(error);
			// 		});
			// },
			// listarUsuarios: function() {
			// 	// this.gateway
			// 	// 	.get(`{{ route('api.v1.jobs.tarefas.usuarios') }}`)
			// 	// 	.then((response) => {
			// 	// 		this.tarefaForm.listaUsuarios = response.data.sort((a, b) => {
			// 	// 			if (a.nome < b.nome) {
			// 	// 				return -1;
			// 	// 			}
			// 	// 		});

			// 	// 		let user = this.tarefaForm.listaUsuarios.find((u) => {

			// 	// 			if (u.email == usuario.email) {
			// 	// 				return u;
			// 	// 			}

			// 	// 			if (u.email == usuario.email_comercial) {
			// 	// 				return u;
			// 	// 			}

			// 	// 		});

			// 	// 		this.tarefaForm.usuario = user.email;
			// 	// 	}).catch(error => {
			// 	// 		console.error(error);
			// 	// 	});
			// },
			// listartStatus: function(projetoId) {
			// 	this.gateway
			// 		.get(`{{ route('api.v1.jobs.tarefas.buscar_statuses') }}?projetoId=${projetoId}`)
			// 		.then((response) => {
			// 			this.tarefaForm.listaStatuses = response.data.sort((a, b) => {
			// 				if (a.nome < b.nome) {
			// 					return -1;
			// 				}
			// 			});
			// 		}).catch(error => {
			// 			console.error(error);
			// 		});
			// },
			// getTime: function(seconds) {
			// 	hours = Math.floor(seconds / 3600);
			// 	minutes = Math.floor((seconds - (hours * 3600)) / 60);

			// 	let timeString = '';

			// 	if (hours > 0) {
			// 		timeString += `${hours.toString()}h `;
			// 	}

			// 	if (minutes > 0) {
			// 		timeString += `${minutes.toString()}m`;
			// 	}

			// 	return timeString == '' ? '0h' : timeString;
			// },
			// organizarPorSemana: function(tarefas) {
			// 	this.totalEmSegundos = 0;
			// 	this.qtdTarefas = 0;
			// 	let tarefasSemana = [];
			// 	tarefas.forEach((tarefa) => {
			// 		tarefa.worklogs.forEach((worklog) => {
			// 			let worklogDiaIndex = this.worklogsPorSemana.findIndex((worklogPorSemana) => worklogPorSemana.dia == worklog.iniciado_em_formatado && (worklog.autor.email == usuario.email || worklog.autor.email == usuario.email_comercial || worklog.autor.email == this.tarefaForm.usuario || worklog.autor.id == this.tarefaForm.usuario));
			// 			if (worklogDiaIndex != -1) {
			// 				if (!tarefasSemana.find((t) => t.key == worklog.tarefa.key)) {
			// 					this.qtdTarefas++;
			// 					tarefasSemana.push(tarefa);
			// 				}
			// 				this.worklogsPorSemana[worklogDiaIndex].worklogs.push(worklog);
			// 				this.worklogsPorSemana[worklogDiaIndex].totalEmSegundos += worklog.time_spent_seconds;
			// 			}
			// 		});
			// 	});

			// 	this.listaTarefas = tarefasSemana;

			// 	let totalEmSegundos = 0;
			// 	this.worklogsPorSemana.forEach((worklog, i) => {
			// 		this.totalPorSemanaEmSegundos += worklog.totalEmSegundos;
			// 		this.worklogsPorSemana[i].totalEmHoras = this.getTime(worklog.totalEmSegundos);
			// 	});

			// 	this.totalPorSemanaEmHoras = this.getTime(this.totalPorSemanaEmSegundos);
			// },
			// listarFeriados: function() {
			// 	this.gateway
			// 		.get(`{{ route('api.v1.jobs.calendario.listar_feriados') }}`)
			// 		.then((response) => {
			// 			this.feriados = response.data;
			// 		}).catch(error => {
			// 			console.error(error);
			// 		});
			// },
			// buscarTarefas: function() {
			// 	this.tarefaLoading = true;
			// 	this.listarSemanaAtual();
			// 	this.gateway
			// 		.get(`{{ route('api.v1.jobs.tarefas.buscar') }}?projects=${this.tarefaForm.projeto}&data_inicio=${this.semana.data_inicio}&data_fim=${this.semana.data_fim}&usuario=${this.tarefaForm.usuario}&status=${this.tarefaForm.status}`)
			// 		.then((response) => {
			// 			this.listaTarefas = response.data;
			// 			this.organizarPorSemana(this.listaTarefas);
			// 			this.tarefaLoading = false;
			// 		}).catch(error => {
			// 			console.error(error);
			// 		});
			// },
			// getDayOfWeek: function(day) {
			// 	let dayOfWeek = '';
			// 	switch (day) {
			// 		case 0:
			// 			dayOfWeek = 'Domingo';
			// 			break;
			// 		case 1:
			// 			dayOfWeek = 'Segunda';
			// 			break;
			// 		case 2:
			// 			dayOfWeek = 'Terça';
			// 			break;
			// 		case 3:
			// 			dayOfWeek = 'Quarta';
			// 			break;
			// 		case 4:
			// 			dayOfWeek = 'Quinta';
			// 			break;
			// 		case 5:
			// 			dayOfWeek = 'Sexta';
			// 			break;
			// 		case 6:
			// 			dayOfWeek = 'Sabado';
			// 			break;
			// 	}
			// 	return dayOfWeek;
			// },
			// listarSemanaAtual: function() {
			// 	this.tarefaForm.periodo = this.tarefaForm.periodo ?? 'this';

			// 	this.gateway
			// 		.get(`{{ route('api.v1.jobs.calendario.listar_semana_atual') }}?periodo=${this.tarefaForm.periodo}`)
			// 		.then((response) => {
			// 			this.semana = response.data;
			// 			let dias = [];
			// 			let contador = 0;
			// 			let data = new Date(response.data.data_inicio);
			// 			let dataAtual = new Date().toISOString()
			// 				.replace(/([0-9]{4}-[0-9]{2}-[0-9]{2})(.*)/, '$1')
			// 				.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, '$3/$2/$1');

			// 			this.worklogsPorSemana = [];
			// 			this.totalPorSemanaEmSegundos = 0;

			// 			do {
			// 				let dmY = data.toISOString()
			// 					.replace(/([0-9]{4}-[0-9]{2}-[0-9]{2})(.*)/, '$1')
			// 					.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, '$3/$2/$1');

			// 				data.setDate(data.getDate() + 1);

			// 				let feriado = this.feriados.find((f) => f.dia == dmY);
			// 				let isFeriado = feriado ? true : false;

			// 				this.worklogsPorSemana.push({
			// 					dia: dmY,
			// 					diaIsToday: dmY == dataAtual,
			// 					diaIsHoliday: isFeriado,
			// 					feriado: isFeriado ? feriado.nome : '',
			// 					diaSemana: this.getDayOfWeek(data.getDay()),
			// 					worklogs: [],
			// 					totalEmSegundos: 0,
			// 					totalEmHoras: '0h',
			// 				});

			// 				contador++;
			// 			} while (contador < 5);

			// 		}).catch(error => {
			// 			console.error(error);
			// 		});
			// }
		},
		computed: {
			filteredItems() {

				let tarefas = this.listaTarefas;

				if (this.tarefaForm.status) {
					tarefas = this.listaTarefas.filter(tarefa => {
						return tarefa.status.id == this.tarefaForm.status;
					});
				}

				if (this.tarefaForm.search) {
					tarefas = this.listaTarefas.filter(tarefa => {
						return tarefa.key.includes(this.tarefaForm.search) ||
							tarefa.resumo.includes(this.tarefaForm.search) ||
							tarefa.responsavel.nome.includes(this.tarefaForm.search) ||
							tarefa.status.nome.includes(this.tarefaForm.search);
					});
				}

				// console.log(this.worklogsPorSemana);

				// tarefas.forEach(tarefa => {

				// 	tarefa.worklogs.forEach((wl)=> {
				// 		console.log(wl);
				// 	});

				// 	// if (tarefa.status.nome != 'Concluído' && tarefa.status.nome != 'Finalizado') {
				// 	// 	this.qtdTarefas++;
				// 	// }
				// });

				return tarefas;
			}
		},
		created: function() {
			// this.listarFeriados();
			// this.listarSemanaAtual();
			// this.listarProjetos();
			// this.listarUsuarios();
		},
	});

	var appContracheques = new Vue({
		el: '#contracheques-app',
		data: {
			gateway: new Gateway(),
			usuarioId: '{{ $dados->usuario->id }}',
			contrachequeForm: {
				ano: '',
				ordem: '',
			},
			listaContracheques: [],
			listaAnos: [],
			isEsconder: true,
			contrachequeLoading: true
		},
		methods: {
			toggleVisualizacao: function() {
				this.isEsconder = !this.isEsconder;
			},
			listar: function() {
				this.contrachequeLoading = true;
				let objDate = new Date();
				let ano = this.contrachequeForm.ano ? this.contrachequeForm.ano : objDate.getFullYear();
				let ordem = this.contrachequeForm.ordem ? this.contrachequeForm.ordem : 'desc';
				let params = `usuario_id=${this.usuarioId}&ano=${ano}&ordem=${ordem}`;

				this.gateway
					.get(`{{ route('api.v1.jobs.contracheques.listar') }}?${params}`)
					.then((response) => {
						this.listaContracheques = response.data;
						this.contrachequeLoading = false;
					}).catch(error => {
						console.error(error);
					});
			},
			listarAnos: function() {
				let params = `usuario_id=${this.usuarioId}`;
				let ano = new Date().getFullYear();

				this.gateway
					.get(`{{ route('api.v1.jobs.contracheques.listar_anos') }}?${params}`)
					.then((response) => {
						this.listaAnos = response.data;
						this.contrachequeForm.ano = ano;
					}).catch(error => {
						console.error(error);
					});
			}
		},
		computed: {},
		created: function() {
			this.listar();
			this.listarAnos();
		},
	});

	var appFerias = new Vue({
		el: '#ferias-app',
		data: {
			gateway: new Gateway(),
			usuarioId: usuario.id,
			ultimasFeriasAgendadas: null,
			listaFerias: [],
			feriasLoading: true,
			contrachequeForm: {
				ano: '',
			}
		},
		methods: {
			listar: function() {
				this.feriasLoading = true;
				this.gateway
					.get(`{{ route('api.v1.jobs.ferias.listar') }}?usuario_id=${this.usuarioId}&limite=3`)
					.then((response) => {
						console.log(response)
						this.listaFerias = response.data;
						this.feriasLoading = false;
					}).catch(error => {
						console.error(error);
					});
			},
			verificarFerias: function() {
				this.feriasLoading = true;
				this.gateway
					.get(`{{ route('api.v1.jobs.ferias.verificar') }}?usuario_id=${this.usuarioId}`)
					.then((response) => {
						this.ultimasFeriasAgendadas = response.data;
					});
			}
		},
		computed: {},
		created: function() {
			this.listar();
			// this.verificarFerias();
		},
	});
</script>

@endsection

@section('content')

<div class="row d-none">
	<div class="col">
		<div class="card">
			<div class="card-body">
				<h1 class="card-title"><i class="bi bi-spotify"></i> Spotify</h1>
				<a href="{{ route('spotify.index') }}">Authorize</a>
				<a href="{{ route('spotify.profile', [ 'code' => $dados->filtro['code']??'' ]) }}">Perfil</a>
				<h4><small>Ouvindo agora</small></h4>
				<!-- <a href="#" title="Anterior"><i class="bi bi-skip-backward-fill fs-2"></i></a>
									<a href="#" title="Tocar"><i class="bi bi-play-fill fs-2"></i></i></a>
									<a href="#" title="Pausar"><i class="bi bi-pause-fill fs-2"></i></a>
									<a href="#" title="Próxima"><i class="bi bi-skip-forward-fill fs-2"></i></a> -->
			</div>
		</div>
	</div>
</div>
<div class="row mt-2">
	<div class="col">
		<div class="card d-none">
			<div class="card-body">
				<h1 class="card-title">
					<i class="bi bi-calendar3"></i> Agenda
				</h1>
				<a href="{{ route('jobs.dashboard.agenda_index') }}">Agenda Completa</a>
				<div id="calendar"></div>
			</div>
		</div>
	</div>
</div>
<div class="row mt-4">
	<div class="col">
		<div class="card" id="ponto-app">
			<div class="card-body">
				<div class="row">
					<div class="col d-flex justify-content-between align-items-start">
						<h1 class="card-title"><i class="bi bi-alarm"></i>Ponto</h1>
						<h1>
							@{{ calendario.hoje.diaDaSemana }}
							@{{ calendario.hoje.dia }} de @{{ calendario.hoje.mes }} de @{{ calendario.hoje.ano }}
							<span>@{{ relogio.hora }}:@{{ relogio.minuto }}:@{{ relogio.segundo }}</span>
						</h1>
					</div>
				</div>
				<div class="row mt-4 mb-4">
					<div class="col-2">
						<a href="#" title="Marcar Entrada" v-on:click.prevent="marcar('{{ route('api.v1.jobs.pontos.marcar') }}', 'entrada')"><i class="bi bi-arrow-right-circle-fill fs-2"></i></a>
						<a href="#" title="Marcar Almoço" v-on:click.prevent="marcar('{{ route('api.v1.jobs.pontos.marcar') }}', 'almoco_saida')"><i class="bi bi-pause-circle-fill fs-2"></i></a>
						<a href="#" title="Marcar Retorno" v-on:click.prevent="marcar('{{ route('api.v1.jobs.pontos.marcar') }}', 'almoco_retorno')"><i class="bi bi-play-circle-fill fs-2"></i></a>
						<a href="#" title="Marcar Saída" v-on:click.prevent="marcar('{{ route('api.v1.jobs.pontos.marcar') }}', 'saida')"><i class="bi bi-arrow-right-circle-fill fs-2"></i></i></a>
					</div>
					<div class="col-2">
						<input type="radio" class="btn-check" name="categoria" id="presencial" value="presencial" v-model="pontoAssignForm.categoria" autocomplete="off">
						<label class="btn btn-primary" for="presencial"><i class="bi bi-building"></i></label>

						<input type="radio" class="btn-check" name="categoria" id="ho" value="home_office" v-model="pontoAssignForm.categoria" autocomplete="off">
						<label class="btn btn-primary" for="ho"><i class="bi bi-house"></i></label>
					</div>
					<div class="col-2">
						<input type="checkbox" class="" name="pedir_ajuste" id="pedir_ajuste" value="pedir_ajuste" v-model="pontoAssignForm.pedir_ajuste" autocomplete="off">
						<label class="" for="pedir_ajuste">Pedir Ajuste?</label>
					</div>
					<div class="col-2">
						<input class="form-control" type="text" name="obsersavacao_dia" placeholder="Observação dia" v-model="pontoAssignForm.observacao_dia" />
					</div>
					<div class="col-2">
						<input class="form-control" type="text" name="observacao_horario" placeholder="Observação horário" v-model="pontoAssignForm.observacao_horario" />
					</div>
				</div>
				<div class="row mt-4 mb-4">
					<div class="col-2">
						<select class="form-select" v-model="pontoSearchForm.mes">
							<option value="">Mês</option>
							<option v-for="mes in pontoSearchForm.meses" :value="mes.mes_ano">@{{ mes.nome }}/@{{ mes.ano }}</option>
						</select>
					</div>
					<div class="col-2">
						<select class="form-select" v-model="pontoSearchForm.ordenacao">
							<option value="">Ordem</option>
							<option value="desc">Decrescente</option>
							<option value="asc">Crescente</option>
						</select>
					</div>
					<div class="col-8">
						<a href="#" title="pesquisar" v-on:click.prevent="listar"><i class="bi bi-search fs-4"></i></a>
					</div>
				</div>

				<div class="row">

					<div class="col-3">
						<h1>@{{ calendario.hoje.mes }} tem</h1>
						<ul class="list-group mb-5">
							<li class="list-group-item">@{{ calendario.qtdDiasMes }} dias</li>
							<li class="list-group-item">@{{ calendario.qtdDiaUteisMes }} dias úteis</li>
							<li class="list-group-item">@{{ calendario.qtdDiasAteFimMes }} dias até o fim do mês</li>
							<li class="list-group-item">@{{ calendario.qtdDiasUteisAteFimMes }} dias uteis até o fim do mês</li>
							<li class="list-group-item">@{{ calendario.qtdDiasAtePagamento }} dias até o pagamento</li>
							<!-- <li class="list-group-item">a<span v-if="calendario.diferenca > 0">@{{ calendario.diaPagamentoPrevisto }}</span></li> -->
						</ul>

						<ul class="list-group mb-5">
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="ms-2 me-auto">
									<div class="fw-bold">Total: </div>
								</div>
								<span class="badge rounded-pill bg-primary">@{{ resumos.total??0 }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="ms-2 me-auto">
									<div class="fw-bold">Presencial: </div>
								</div>
								<span class="badge rounded-pill bg-primary">@{{ resumos.presencial??0 }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="ms-2 me-auto">
									<div class="fw-bold">Home Office: </div>
								</div>
								<span class="badge rounded-pill bg-primary">@{{ resumos.home_office??0 }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="ms-2 me-auto">
									<div class="fw-bold">Ajustes: </div>
								</div>
								<span class="badge rounded-pill bg-primary">@{{ resumos.ajustes??0 }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-start">
								<div class="ms-2 me-auto">
									<div class="fw-bold">Observações: </div>
								</div>
								<span class="badge rounded-pill bg-primary">@{{ resumos.observacoes??0 }}</span>
							</li>
						</ul>
					</div>

					<div class="col-9">
						<table class="table table-responsive suave">

							<tr class="sticky-top">
								<th></th>
								<th></th>
								<th>Data</th>
								<th>Entrada</th>
								<th>Almoço</th>
								<th>Retorno</th>
								<th>Saída</th>
								<!-- <th>Crédito</th>
								<th>Débito</th> -->
							</tr>
							<tr v-if="pontoLoading">
								<td colspan="8" class="text-center">
									<div class="spinner-border" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</td>
							</tr>
							<tr v-if="!pontoLoading" v-for="ponto in listaPontos">
								<td><a :href="ponto.link_ajuste"><i class="bi bi-pencil-fill" title="Ajustar"></i></a></td>
								<td>
									<i v-if="ponto.categoria == 'Presencial'" class="bi bi-building" title="Presencial"></i>
									<i v-if="ponto.categoria != 'Presencial'" class="bi bi-house" title="Home Office"></i>
									<span v-if="ponto.pedir_ajuste" class="badge rounded-pill" :class="[ ponto.ajuste_finalizado == true ? 'bg-success' : 'bg-danger' ]">A</span>
									<span v-if="ponto.observacao && ponto.observacao.length > 0" class="badge rounded-pill bg-primary" :title="ponto.observacao">Obs</span>
								</td>
								<td>@{{ ponto.dia }} (<small>@{{ ponto.diaSemana }}</small>)</td>
								<td>@{{ ponto.entrada }}</td>
								<td>
									<span v-if="ponto.almoco_saida != '-'">@{{ ponto.almoco_saida }}</span>
									<span v-if="ponto.almoco_saida == '-'">@{{ ponto.horario_almoco_saida }} (prev)</span>
								</td>
								<td>
									<span v-if="ponto.almoco_retorno != '-'">@{{ ponto.almoco_retorno }}</span>
									<span v-if="ponto.intervalo_total != '00:00'" :class="[ponto.intervalo_total != '00:00' ? 'text-warning' : '']">(@{{ ponto.intervalo_total }})</span>
									<span v-if="ponto.almoco_retorno == '-' ">@{{ ponto.horario_almoco_retorno }} (prev)</span>
								</td>
								<td>
									<span v-if="ponto.saida != '-'">@{{ ponto.saida }}</span>
									<span v-if="ponto.saida == '-'">@{{ ponto.horario_saida }} (prev)</span>
									<span v-if="ponto.jornada_total != '00:00'" :class="[ponto.jornada_total!='00:00' ? 'text-warning' : '']">(@{{ ponto.jornada_total }})</span>
								</td>
								<!--
								<td>@{{ ponto.credito }}</td>
								<td>@{{ ponto.debito }}</td>
								-->
							</tr>
							<tr v-if="!pontoLoading && listaPontos.length == 0">
								<td colspan="12">Nenhum ponto cadastrado até o momento.</td>
							</tr>
							<tr>
								<td class="text-end" colspan="6">Subtotal:</td>
								<td ><span class="text-success"><strong>@{{ subtotalPontos.credito }}</strong></span> <span class="text-danger"><strong>@{{ subtotalPontos.debito }}</strong></span></td>
							</tr>
							<tr>
								<td class="text-end" colspan="6">PortalRH</td>
								<td><span class="text-success">@{{ bancoHoras.credito }}</span> <span class="text-danger">@{{ bancoHoras.debito }}</span></td>
							</tr>
						</table>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>
<div class="row mt-4">
	<div class="col">
		<div class="card" id="tarefas-app">
			<modal v-if="showModal" @close="showModal = false" :tarefa="tarefa">
				<h3 slot="header">
					@{{ tarefa.resumo }}<br />

				</h3>
				<div slot="body">
					<p>
						<img width="32" height="32" :src="tarefa.prioridade.icone" :title="'Prioridade: '+tarefa.prioridade.nome" />
						<img width="32" height="32" :src="tarefa.tipo.icone" :title="tarefa.tipo.nome" />
						<img width="32" height="32" :src="tarefa.responsavel?.icone['32x32']" :title="tarefa.responsavel?.nome" />
					</p>
					<!-- @{{ tarefa.tipo.icone }}1<br/> -->
					@{{ tarefa.resumo }}2<br />
					@{{ tarefa.descricao??'-' }}<br />
				</div>
			</modal>
			<div class="card-body">
				<div class="row">
					<div class="col-2">
						<h1 class="card-title"><i class="bi bi-list-task"></i> Tarefas</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-2">
						<select class="form-select" v-model="tarefaForm.projeto"><!-- @change="buscarTarefas()"-->
							<option value="">Projetos</option>
							<option v-for="projeto in tarefaForm.listaProjetos" :value="projeto.key">@{{ projeto.nome }}</option>
						</select>
					</div>
					<div class="col-2">
						<select class="form-select" v-model="tarefaForm.usuario">
							<option value="">Usuários</option>
							<option v-for="usuario in tarefaForm.listaUsuarios" :value="usuario.email || usuario.id">@{{ usuario.nome }}</option>
						</select>
					</div>
					<div class="col-2">
						<select class="form-select" v-model="tarefaForm.status">
							<option value="">Status</option>
							<option v-for="status in tarefaForm.listaStatuses" :value="status.id">@{{ status.nome }}</option>
						</select>
					</div>
					<div class="col-2">
						<select class="form-select" v-model="tarefaForm.periodo">
							<option value="">Período</option>
							<option value="this">Esta semana</option>
							<option value="last">Semana passada</option>
						</select>
					</div>
					<div class="col-2">
						<input type="text" class="form-control" v-model="tarefaForm.search" placeholder="Busca" />
					</div>
					<div class="col">
						<a href="#" title="pesquisar" v-on:click.prevent="buscarTarefas()"><i class="bi bi-search fs-4"></i></a>&nbsp;&nbsp;
						<a href="#" title="Incluir Tarefa"><i class="bi bi-plus-square fs-4"></i></a>
					</div>
				</div>
				<div class="row mt-5">

					<div class="col" v-for="worklogPorSemana in worklogsPorSemana">
						<div class=" d-flex justify-content-between align-items-center">
							<span class="fs-6" :class="[ worklogPorSemana.diaIsToday ? 'destaque text-warning' : '' ]">@{{ worklogPorSemana.dia }} @{{ worklogPorSemana.diaSemana }}</span>&nbsp;
							<span class="badge rounded-pill bg-secondary" v-if="worklogPorSemana.diaIsHoliday" :title="worklogPorSemana.feriado">Feriado</span>
							<span v-if="!worklogPorSemana.diaIsHoliday" class="badge rounded-pill" :class="[ worklogPorSemana.totalEmSegundos < 28800 || worklogPorSemana.totalEmSegundos > 28800 ? 'bg-danger' : 'bg-success' ]">@{{ worklogPorSemana.totalEmHoras }}</span>
						</div>
						<ul class="list-group mt-3">
							<li class="list-group-item d-flex justify-content-between align-items-center" v-for="worklog in worklogPorSemana.worklogs">
								<div>
									<span class="badge rounded-pill bg-secondary" v-if="worklog.comentario.length > 0" :title="worklog.comentario">...</span>
									<a :href="'https://otjira.atlassian.net/browse/'+worklog.tarefa.key" target="_blank">@{{ worklog.tarefa.key }}</a>
								</div>
								<span class="badge rounded-pill bg-primary">@{{ worklog.time_spent_hours }}</span>
							</li>
						</ul>
					</div>
				</div>

			</div>

			<div class="row mt-5">
				<div class="col-3">
					<ul class="list-group mb-5">
						<li class="list-group-item d-flex justify-content-between align-items-start">
							<div class="ms-2 me-auto">
								<div class="fw-bold">Horas na semana: </div>
							</div>
							<span class="badge rounded-pill" :class="[ totalPorSemanaEmSegundos < 144000 || totalPorSemanaEmSegundos > 144000 ? 'bg-danger' : 'bg-success' ]">@{{ totalPorSemanaEmHoras }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-start">
							<div class="ms-2 me-auto">
								<div class="fw-bold">Tarefas na semana</div>
							</div>
							<span class="badge text-bg-primary rounded-pill">@{{ qtdTarefas }}</span>
						</li>
					</ul>
				</div>
				<div class="col-9">
					<table class="table table-responsive table-striped suave">
						<tr>
							<th class="col-2">ID</th>
							<th class="col-2"></th>
							<th class="col-2">Status</th>
							<th class="col-2">Resumo</th>
							<th class="col-2">Estimativa de Pontos</th>
							<th class="col-2">Pontos</th>
							<th class="col-2">Ações</th>
						</tr>
						<tr v-if="tarefaLoading">
							<td colspan="8" class="text-center">
								<div class="spinner-border" role="status">
									<span class="visually-hidden">Loading...</span>
								</div>
							</td>
						</tr>
						<tr v-if="!tarefaLoading" v-for="tarefa in filteredItems">
							<td>
								<a :href="'https://otjira.atlassian.net/browse/'+tarefa.key" target="_blank">@{{ tarefa.key }}</a>
							</td>
							<td>
								<img v-if="tarefa.prioridade" :src="tarefa.prioridade.icone" width="32" height="32" :title="'Prioridade: '+tarefa.prioridade.nome" />
								<img v-if="tarefa.tipo" :src="tarefa.tipo.icone" width="32" height="32" :title="'Tipo: '+tarefa.tipo.nome" />
								<img v-if="tarefa.responsavel" :src="tarefa.responsavel.icone['32x32']" width="32" height="32" :title="'Responsável: '+tarefa.responsavel.nome" />
							</td>
							<td>@{{ tarefa.status.nome }}</td>
							<td>@{{ tarefa.resumo }}</td>
							<td :class="[ tarefa.sp_estimativa == 0 ? 'bg-danger' : '' ]">@{{ tarefa.sp_estimativa }}</td>
							<td :class="[ tarefa.sp == 0 ? 'bg-danger' : '' ]">@{{ tarefa.sp }}</td>
							<td>
								<a href="#" v-on:click.prevent="openModal(tarefa)">Detalhes</a>
							</td>
						</tr>
						<tr v-if="listaTarefas.length == 0">
							<td colspan="6">Nenhum tarefa encontrada</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row mt-4">

	<div class="col">
		<div class="card" id="contracheques-app">
			<div class="card-body">
				<h1 class="card-title"><i class="bi bi-receipt"></i> Contracheques</h1>

				<div class="row mt-4 mb-4">
					<div class="col-3">
						<select class="form-select" v-model="contrachequeForm.ano">
							<option value="">Ano</option>
							<option v-for="competenciaAno in listaAnos" :value="competenciaAno.ano">@{{ competenciaAno.ano }}</option>
						</select>
					</div>
					<div class="col-3">
						<select class="form-select" v-model="contrachequeForm.ordem">
							<option value="">Ordem</option>
							<option value="desc">Decrescente</option>
							<option value="asc">Crescente</option>
						</select>
					</div>
					<div class="col-3">
						<a href="#" title="pesquisar" v-on:click.prevent="listar"><i class="bi bi-search fs-4"></i></a>&nbsp;
						<a href="{{ route('jobs.contracheques.create') }}" title="adicionar"><i class="bi bi-plus-square fs-4"></i></a>
					</div>
				</div>

				<table class="table table-responsive suave">
					<tr>
						<th><i class=" fs-5" :class="[ isEsconder ? 'bi bi-eye-slash' : 'bi bi-eye' ]" @click="toggleVisualizacao()"></i></th>
						<th>Competência</th>
						<th>Base</th>
						<th>Liquido</th>
						<th>Vencimentos</th>
						<th>Descontos</th>
						<th>Total Liquido</th>
					</tr>
					<tr v-if="contrachequeLoading">
						<td colspan="6" class="text-center">
							<div class="spinner-border" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
						</td>
					</tr>
					<tr v-if="!contrachequeLoading" v-for="contracheque in listaContracheques">
						<td><a :href="contracheque.link"><i class="bi bi-pencil-fill" title="Ajustar"></i></a></td>
						<td>
							<span>@{{ contracheque.competencia_extenso }}</span>
						</td>
						<td>
							<span v-if="isEsconder">***</span>
							<span v-if="!isEsconder">@{{ contracheque.salario_base_formatado }}</span>
						</td>
						<td>
							<span v-if="isEsconder">***</span>
							<span v-if="!isEsconder">@{{ contracheque.salario_liquido_formatado }}</span>
						</td>
						<td>
							<span v-if="isEsconder">***</span>
							<span v-if="!isEsconder">@{{ contracheque.total_vencimentos_formatado }}</span>
						</td>
						<td>
							<span v-if="isEsconder">***</span>
							<span v-if="!isEsconder">@{{ contracheque.total_descontos_formatado }}</span>
						</td>
						<td>
							<span v-if="isEsconder">***</span>
							<span v-if="!isEsconder">@{{ contracheque.total_liquido_formatado }}</span> <span v-if="contracheque.salario_liquido == contracheque.total_liquido">OK</span>
						</td>
					</tr>

					<tr v-if="listaContracheques.length == 0">
						<td colspan="8">Nenhum contracheque cadastrado até o momento.</td>
					</tr>
				</table>
			</div>
		</div>


	</div>

	<div class="col">

		<div class="card" id="ferias-app">
			<div class="card-body">

				<div class="row">
					<div class="col">
						<h1 class="card-title"><i class="bi bi-airplane"></i> Férias</h1>

						<h4 v-if="ultimasFeriasAgendadas && !ultimasFeriasAgendadas.ativo">
							Faltam @{{ ultimasFeriasAgendadas.diasAteFerias }} dias para o início das suas férias.
						</h4>

						<h4 v-if="ultimasFeriasAgendadas && ultimasFeriasAgendadas.ativo">
							Estamos de férias. Faltam @{{ ultimasFeriasAgendadas.diasAteRetorno }} dias para o retorno.
						</h4>

					</div>
				</div>

				<div class="row mt-4 mb-4">
					<div class="col">
						<a href="{{ route('jobs.ferias.create') }}" title="adicionar"><i class="bi bi-plus-square fs-4"></i></a>
					</div>
				</div>

				<div class="row">

					<div class="col">
						<!-- <h4 class="text-center mt-2">Últimas Férias Agendadas</h4> -->
						<table class="table table-responsive suave">
							<tr>
								<th></th>
								<th>Inicio</th>
								<th>Fim</th>
								<th>Dias</th>
								<th>Ativo</th>
								<th>Observação</th>
							</tr>
							<tr v-if="feriasLoading">
								<td colspan="6" class="text-center">
									<div class="spinner-border" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</td>
							</tr>
							<tr v-if="!feriasLoading" v-for="ferias in listaFerias">
								<td><a :href="ferias.link"><i class="bi bi-pencil-fill" title="Ajustar"></i></a></td>
								<td>@{{ ferias.inicio }}</td>
								<td>@{{ ferias.fim }}</td>
								<td>@{{ ferias.qtd_dias }}</td>
								<td>@{{ ferias.ativo }}</td>
								<td>@{{ ferias.observacao }}</td>
							</tr>

							<tr v-if="listaFerias.length == 0">
								<td colspan="6">Nenhumas férias agendadas até o momento.</td>
							</tr>
						</table>

					</div>

				</div>
			</div>
		</div>

	</div>

	@endsection
