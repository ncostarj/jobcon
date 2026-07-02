
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

	(new Gateway())
	.get(`{{ route('api.v1.jobs.escalas.listar') }}`)
		.then((response) => {

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

	var appPonto = new Vue({
		el: '#ponto-app',
		data: {
			gateway: new Gateway(),
			pontoForm: {
				categoria: 'home_office',
				usuarioId: '{{ $dados->usuario->id }}',
				observacaoDia: '',
				observacaoHorario: '',
				pedir_ajuste: false,
				mes: '',
				meses: [],
				ordenacao: '',
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
					.get(`{{ route('api.v1.jobs.calendario.exibir') }}`)
					.then((response) => {
						this.calendario = response.data;
						this.calendarioLoading = false;
					})
					.catch(error => {
						console.log(error);
					});
			},
			buscarMeses: function() {
				let hoje = new Date();
				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.buscar_meses') }}?usuario_id=${this.pontoForm.usuarioId}`)
					.then((response) => {
						this.pontoForm.meses = response.data;
					})
					.catch(error => {
						console.log(error);
					});
			},
			marcar: function(rota, tipo) {

				if (!this.pontoForm.categoria) {
					alert('O campo categoria precisa estar preenchido!');
					return false;
				}

				let objDate = new Date();
				let form = {
					"usuario_id": this.pontoForm.usuarioId,
					"dia": objDate.toISOString().replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})(.*)/, '$1-$2-$3'),
					"hora": objDate.toTimeString().replace(/([0-9]{2}):([0-9]{2})(.*)/, '$1:$2'),
					"categoria": this.pontoForm.categoria,
					"observacao": this.pontoForm.observacao,
					"tipo": tipo,
					"pedir_ajuste": this.pontoForm.pedir_ajuste ? 1 : 0,
				};

				this.gateway
					.post(rota, JSON.stringify(form))
					.then((response) => {
						this.pontoForm = {
							categoria: 'home_office',
							usuarioId: '{{ $dados->usuario->id }}',
							observacao: '',
							pedir_ajuste: false,
							mes: objDate.getFullYear() + '-' + (objDate.getMonth() + 1),
						};
						this.buscarMeses();
						this.listar();
						this.obterBancoHoras();
						this.calcularSubtotal();
					})
					.catch(error => {
						console.log(error);
					});
			},
			listar: function() {
				this.pontoLoading = true;
				let hoje = new Date();
				let month = hoje.getMonth() + 1;
				let year = hoje.getFullYear();

				if (this.pontoForm.mes) {
					[year, month] = this.pontoForm.mes.split('-');
				}

				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.listar') }}?mes=${month}&ano=${year}&usuario_id=${this.pontoForm.usuarioId}&ordenacao=${this.pontoForm.ordenacao??''}`)
					.then((response) => {
						this.listaPontos = response.data;
						this.pontoLoading = false;
						this.calcularSubtotal();
					})
					.catch(error => {
						console.log(error);
					});

				this.resumo();
			},
			calcularSubtotal: function() {
				let hoje = new Date();
				let month = hoje.getMonth() + 1;
				let year = hoje.getFullYear();

				if (this.pontoForm.mes) {
					[year, month] = this.pontoForm.mes.split('-');
				}

				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.calcular_subtotal') }}?mes=${month}&ano=${year}&usuario_id=${this.pontoForm.usuarioId}`)
					.then((response) => {
						this.subtotalPontos = response.data;
					})
					.catch(error => {
						console.log(error);
					});
			},
			obterBancoHoras: function() {
				this.gateway
					.get(`{{ route('api.v1.jobs.frequencias.listarUltimoSaldo') }}?usuario_id=${this.pontoForm.usuarioId}`)
					.then((response) => {
						this.bancoHoras = response.data;
					})
					.catch(error => {
						console.log(error);
					});
			},
			resumo: function() {
				let hoje = new Date();
				let month = hoje.getMonth() + 1;
				let year = hoje.getFullYear();

				if (this.pontoForm.mes) {
					[year, month] = this.pontoForm.mes.split('-');
				}

				this.gateway
					.get(`{{ route('api.v1.jobs.pontos.resumo') }}?mes=${month}&ano=${year}&usuario_id=${this.pontoForm.usuarioId}`)
					.then((response) => {
						this.resumos = response.data;
					})
					.catch(error => {
						console.log(error);
					});
			}
		},
		computed: {},
		created: function() {
			let hoje = new Date()
			let mesAtual = hoje.getMonth() + 1;
			let anoAtual = hoje.getFullYear();
			this.pontoForm.mes = `${anoAtual}-${mesAtual}`;
			this.resumo();
			this.buscarMeses();
			this.listar();
			this.obterBancoHoras();
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
			openModal: function(tarefa) {
				this.showModal = true;
				this.tarefa = tarefa;
			},
			listarProjetos: function() {
				this.gateway
					.get(`{{ route('api.v1.jobs.tarefas.projetos') }}`)
					.then((response) => {
						this.tarefaForm.listaProjetos = response.data;

						let proj = this.tarefaForm.listaProjetos.find((p) => p.key == projeto);

						if (proj) {
							this.tarefaForm.projeto = proj.key;
							this.listartStatus(proj.id);
						}

					}).catch(error => {
						console.log(error);
					});
			},
			listarUsuarios: function() {
				// this.gateway
				// 	.get(`{{ route('api.v1.jobs.tarefas.usuarios') }}`)
				// 	.then((response) => {
				// 		this.tarefaForm.listaUsuarios = response.data.sort((a, b) => {
				// 			if (a.nome < b.nome) {
				// 				return -1;
				// 			}
				// 		});

				// 		let user = this.tarefaForm.listaUsuarios.find((u) => {

				// 			if (u.email == usuario.email) {
				// 				return u;
				// 			}

				// 			if (u.email == usuario.email_comercial) {
				// 				return u;
				// 			}

				// 		});

				// 		this.tarefaForm.usuario = user.email;
				// 	}).catch(error => {
				// 		console.log(error);
				// 	});
			},
			listartStatus: function(projetoId) {
				this.gateway
					.get(`{{ route('api.v1.jobs.tarefas.buscar_statuses') }}?projetoId=${projetoId}`)
					.then((response) => {
						this.tarefaForm.listaStatuses = response.data.sort((a, b) => {
							if (a.nome < b.nome) {
								return -1;
							}
						});
					}).catch(error => {
						console.log(error);
					});
			},
			getTime: function(seconds) {
				hours = Math.floor(seconds / 3600);
				minutes = Math.floor((seconds - (hours * 3600)) / 60);

				let timeString = '';

				if (hours > 0) {
					timeString += `${hours.toString()}h `;
				}

				if (minutes > 0) {
					timeString += `${minutes.toString()}m`;
				}

				return timeString == '' ? '0h' : timeString;
			},
			organizarPorSemana: function(tarefas) {
				this.totalEmSegundos = 0;
				this.qtdTarefas = 0;
				let tarefasSemana = [];
				tarefas.forEach((tarefa) => {
					tarefa.worklogs.forEach((worklog) => {
						let worklogDiaIndex = this.worklogsPorSemana.findIndex((worklogPorSemana) => worklogPorSemana.dia == worklog.iniciado_em_formatado && (worklog.autor.email == usuario.email || worklog.autor.email == usuario.email_comercial || worklog.autor.email == this.tarefaForm.usuario || worklog.autor.id == this.tarefaForm.usuario));
						if (worklogDiaIndex != -1) {
							if (!tarefasSemana.find((t) => t.key == worklog.tarefa.key)) {
								this.qtdTarefas++;
								tarefasSemana.push(tarefa);
							}
							this.worklogsPorSemana[worklogDiaIndex].worklogs.push(worklog);
							this.worklogsPorSemana[worklogDiaIndex].totalEmSegundos += worklog.time_spent_seconds;
						}
					});
				});

				this.listaTarefas = tarefasSemana;

				let totalEmSegundos = 0;
				this.worklogsPorSemana.forEach((worklog, i) => {
					this.totalPorSemanaEmSegundos += worklog.totalEmSegundos;
					this.worklogsPorSemana[i].totalEmHoras = this.getTime(worklog.totalEmSegundos);
				});

				this.totalPorSemanaEmHoras = this.getTime(this.totalPorSemanaEmSegundos);
			},
			listarFeriados: function() {
				this.gateway
					.get(`{{ route('api.v1.jobs.calendario.listar_feriados') }}`)
					.then((response) => {
						this.feriados = response.data;
					}).catch(error => {
						console.log(error);
					});
			},
			buscarTarefas: function() {
				this.tarefaLoading = true;
				this.listarSemanaAtual();
				this.gateway
					.get(`{{ route('api.v1.jobs.tarefas.buscar') }}?projects=${this.tarefaForm.projeto}&data_inicio=${this.semana.data_inicio}&data_fim=${this.semana.data_fim}&usuario=${this.tarefaForm.usuario}&status=${this.tarefaForm.status}`)
					.then((response) => {
						this.listaTarefas = response.data;
						this.organizarPorSemana(this.listaTarefas);
						this.tarefaLoading = false;
					}).catch(error => {
						console.log(error);
					});
			},
			getDayOfWeek: function(day) {
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
			},
			listarSemanaAtual: function() {
				this.tarefaForm.periodo = this.tarefaForm.periodo ?? 'this';

				this.gateway
					.get(`{{ route('api.v1.jobs.calendario.listar_semana_atual') }}?periodo=${this.tarefaForm.periodo}`)
					.then((response) => {
						this.semana = response.data;
						let dias = [];
						let contador = 0;
						let data = new Date(response.data.data_inicio);
						let dataAtual = new Date().toISOString()
							.replace(/([0-9]{4}-[0-9]{2}-[0-9]{2})(.*)/, '$1')
							.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, '$3/$2/$1');

						this.worklogsPorSemana = [];
						this.totalPorSemanaEmSegundos = 0;

						do {
							let dmY = data.toISOString()
								.replace(/([0-9]{4}-[0-9]{2}-[0-9]{2})(.*)/, '$1')
								.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2})/, '$3/$2/$1');

							data.setDate(data.getDate() + 1);

							let feriado = this.feriados.find((f) => f.dia == dmY);
							let isFeriado = feriado ? true : false;

							this.worklogsPorSemana.push({
								dia: dmY,
								diaIsToday: dmY == dataAtual,
								diaIsHoliday: isFeriado,
								feriado: isFeriado ? feriado.nome : '',
								diaSemana: this.getDayOfWeek(data.getDay()),
								worklogs: [],
								totalEmSegundos: 0,
								totalEmHoras: '0h',
							});

							contador++;
						} while (contador < 5);

					}).catch(error => {
						console.log(error);
					});
			}
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
			this.listarFeriados();
			this.listarSemanaAtual();
			this.listarProjetos();
			this.listarUsuarios();
		},
	});



	var appContracheques = new Vue({
		el: '#contracheques-app',
		data: {
			gateway: new Gateway(),
			usuarioId: '{{ $dados->usuario->id }}',
			contrachequeForm: {
				ano: '',
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
				this.gateway
					.get(`{{ route('api.v1.jobs.contracheques.listar') }}?usuario_id=${this.usuarioId}&ano=${ano}`)
					.then((response) => {
						this.listaContracheques = response.data;
						this.contrachequeLoading = false;
					}).catch(error => {
						console.log(error);
					});
			},
			listarAnos: function() {
				let objDate = new Date();
				let ano = objDate.getFullYear();
				this.gateway
					.get(`{{ route('api.v1.jobs.contracheques.buscar_anos') }}?usuario_id=${this.usuarioId}&ano=${ano}`)
					.then((response) => {
						this.listaAnos = response.data;
						this.contrachequeForm.ano = ano;
					}).catch(error => {
						console.log(error);
					});
			}
		},
		computed: {},
		created: function() {
			this.listarAnos();
			this.listar();
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
						this.listaFerias = response.data;
						this.feriasLoading = false;
					}).catch(error => {
						console.log(error);
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
			this.verificarFerias();
		},
	});