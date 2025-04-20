@extends('common.layout')
@section('title', 'Jira')
@section('style')
<style type="text/css">
	.coluna-lateral {
		font-size: 18px;
	}

	.tabela-fonte {
		font-size: 16px;
	}
</style>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/jira/jira.js') }}"></script>
<script type="text/javascript">
	class HttpService {
		sendRequest(url, method) {
			return fetch(url, {
				method: "GET"
			}).then(function(response) {
				return response.json();
			})
		}
	}

	class JiraService {

		constructor() {
			this.httpService = new HttpService();
		}

		getProjects() {
			return this.httpService
				.sendRequest(`{{ route('jira.projects') }}`);
		}

		getBoards(projectId) {
			return this.httpService
				.sendRequest(`{{ route('jira.boards') }}?projectId=${projectId}`);
		}

		getSprints(boardId) {
			return this.httpService
				.sendRequest(`{{ route('jira.sprints') }}?boardId=${boardId}`);
		}

		getTasks(sprintId, maxResults) {
			return this.httpService
				.sendRequest(`{{ route('jira.tasks') }}?sprintId=${sprintId}&maxResults=${maxResults??50}`);
		}

		getUsers() {
			return this.httpService
				.sendRequest(`{{ route('jira.users') }}`);
		}

		async loadTaskList(projectId = '10060', boardId = '73', sprintId, limit = 200) {

			const boards = await this.getBoards(projectId)
				.then((data) => data);

			const sprints = await this.getSprints(boardId)
				.then((data) => data);

			const activeBoard = boards.find((board) => board.id == boardId);
			const activeSprint = new Sprint(sprints[0], activeBoard);

			sprintId = !sprintId ? activeSprint.id : sprintId;

			await this.getTasks(sprintId, limit)
				.then((data) => {
					activeSprint.setTarefa(data);
				});

			return {
				boards,
				sprints,
				activeSprint
			};

		}
	}

	var app = new Vue({
		el: '#jiraApp',
		data: {
			jiraService: new JiraService(),
			loading: true,
			jiraForm: {
				project: '',
				board: '',
				sprint: '',
				limit: 200,
				user: '6197a0096d002b006b37e78b',
				status: '',
				search: ''
			},
			project: {
				sprints: [],
				boards: [],
				users: [{
						id: "612501e03ef46a00705a99aa",
						nome: "Neto"
					},
					{
						id: "6189654b86c210006a78e3ba",
						nome: "Diego"
					},
					{
						id: "6197a0096d002b006b37e78b",
						nome: "Newton"
					},
					{
						id: "6310e143d8850343ef58a3d5",
						nome: "Lucas Reis"
					},
					{
						id: "5f68f7933672f3006a21781f",
						nome: "Rita"
					},
					{
						id: "60eda790a211b10069473f3b",
						nome: "Adrielle"
					},
				],
				statuses: [{
						id: "10001",
						nome: "EM DESENVOLVIMENTO"
					},
					{
						id: "10004",
						nome: "Finalizado"
					},
					{
						id: "10005",
						nome: "Para Fazer"
					},
					{
						id: "10007",
						nome: "Em Análise"
					},
					{
						id: "10008",
						nome: "Reaberto"
					},
					{
						id: "10009",
						nome: "Pendente"
					},
					{
						id: "10052",
						nome: "CODE REVIEW"
					},
					{
						id: "10075",
						nome: "Aguardando Release"
					},
					{
						id: "10118",
						nome: "GQ"
					},
				],
				limits: [
					50,
					100,
					200,
					300
				]
			},
			referencia: {
				hPorDia: 8,
				hPorSemana: 8 * 5,
				hPorSprint: 8 * 5 * 2,
				hPorMes: 8 * 5 * 4,
			},
			tarefas: [],
			activeSprint: {
				tarefas: [],
				painel: {},
				totalWorklog: 0,
				contadorTarefas: new ContadorTarefa()
				// name: '',
				// startDate: null,
				// endDate: null,
				// activeBoard: '',
				// tasks: [],
				// weekWorkLog: [],
			},
		},
		methods: {
			carregarBoards: function(event) {
				this.jiraForm.board = '';
				this.project.boards = [];
				this.jiraForm.sprint = '';
				this.project.sprints = [];
				this.jiraForm.user = '';
				this.activeSprint.tarefas = [];
				this.jiraService.getBoards(event.target.value).then((data) => {
					this.project.boards = data;
				});
			},
			carregarSprints: function(event) {
				this.jiraService.getSprints(event.target.value).then((data) => {
					this.project.sprints = data;
				});
			},
			carregarTasks: function() {
				this.loading = true;

				this.jiraForm.project = this.jiraForm.project == '' ? '10060' : this.jiraForm.project;
				this.jiraForm.board = this.jiraForm.board == '' ? '73' : this.jiraForm.board;
				this.jiraForm.sprint = this.jiraForm.sprint == '' ? null : this.jiraForm.sprint;
				this.jiraForm.limit = this.jiraForm.limit == '' ? 200 : this.jiraForm.limit;

				this.jiraService
					.loadTaskList(this.jiraForm.project, this.jiraForm.board, this.jiraForm.sprint, this.jiraForm.limit)
					.then((data) => {
						console.log(data);
						this.loading = false;
						this.activeSprint = data.activeSprint;
						this.jiraForm.sprint = data.activeSprint.id;
						this.project.boards = data.boards;
						this.project.sprints = data.sprints;
						this.project.users = data.activeSprint.responsaveis;
						this.carregarWeekworklog(data.activeSprint, this.jiraForm.user);
						this.carregarContadorTarefas(data.activeSprint.tarefas);
					});
			},
			recarregarTasks: function() {
				this.loading = true;
				this.carregarTasks();
			},
			carregarContadorTarefas: function(tasks) {
				this.activeSprint.contadorTarefas.compute(tasks, this.jiraForm.user, this.project.statuses);
			},
			carregarWeekworklog: function(sprint, userId) {
				if (sprint.dataInicio) {
					const registroTrabalhoSemanal = new RegistroTrabalhoSemanal(sprint.dataInicio, sprint.dataFim);
					registroTrabalhoSemanal.setRegistroTrabalhoTarefa(sprint.tarefas, userId);
					this.activeSprint.registroTrabalhoSprint = registroTrabalhoSemanal.getRegistroTrabalhoSprint();
					this.activeSprint.totalWorklog = registroTrabalhoSemanal.totalWorklog;
				}
			},
			verificarDadosZerados: function(task) {
				let retorno = false;
				if (task.story_points_estimate == 0 ||
					task.story_points == 0 ||
					task.original_estimate == 0 ||
					task.estimate == 0) {
					retorno = true;
				}
				return retorno;
			},
			verificarUsuario() {
				return this.jiraForm.user == '6197a0096d002b006b37e78b';
			}
		},
		computed: {
			filteredItems: function() {
				let itemsFiltered = this.activeSprint.tarefas;

				if (this.jiraForm.user) {
					itemsFiltered = itemsFiltered.filter((task) => task.responsavel && task.responsavel.id == this.jiraForm.user);
				}

				if (this.jiraForm.status) {
					itemsFiltered = itemsFiltered.filter((task) => task.status.id == this.jiraForm.status);
				}

				if (this.jiraForm.search) {
					itemsFiltered = itemsFiltered.filter((task) =>
						task.id.includes(this.jiraForm.search) ||
						task.resumo.includes(this.jiraForm.search) ||
						task.status.nome.includes(this.jiraForm.search)
					);
				}

				this.carregarWeekworklog(this.activeSprint, this.jiraForm.user);
				this.carregarContadorTarefas(this.activeSprint.tarefas);

				return itemsFiltered;
			},
		},
		created: function() {
			this.carregarTasks();
		},
	})
</script>
@endsection
@section('content')
<div class="col-12" id="jiraApp">
	<h1 class="text-center">@{{ activeSprint.nome }}</h1>
	<h5 class="text-center">@{{ activeSprint.painel.nome }}</h5>
	<h6 class="text-center">@{{ activeSprint.objetivo }}</h6>

	<form>
		<div class="row">
			<div class="col-2">
				<div class="form-floating">
					<select class="form-select" id="projetos" name="projetos" v-model="jiraForm.project" @change="carregarBoards($event)" aria-label="Floating label select example">
						<option value="" selected="selected">Projetos</option>
						@foreach($projetos as $projeto)
						<option value="{{ $projeto->id }}">{{ $projeto->key . ' ' . $projeto->name }}</option>
						@endforeach
					</select>
					<label for="projetos">Projetos</label>
				</div>
			</div>

			<div class="col-3 form-floating">
				<select class="form-select" id="boards" name="paineis" v-model='jiraForm.board' @change="carregarSprints($event)">
					<option value="" selected="selected">Boards</option>
					<option v-for="board in project.boards" :value="board.id">@{{ board.name }}</option>
				</select>
				<label for="paineis">Boards</label>
			</div>

			<div class="col-3 form-floating">
				<select class="form-select" id="sprints" name="sprints" v-model='jiraForm.sprint' @change="carregarTasks($event)">
					<option value="" selected="selected">Sprints</option>
					<option v-for="sprint in project.sprints" :value="sprint.id">@{{ sprint.name }}</option>
				</select>
				<label for="sprints">Sprints</label>
			</div>

			<div class="col-2 form-floating">
				<select class="form-select" id="users" name="users" v-model='jiraForm.user'>
					<option value="" selected="selected">Responsáveis</option>
					<option v-for="user in project.users" :value="user.id">@{{ user.nome }}</option>
				</select>
				<label for="users">Responsáveis</label>
			</div>

			<div class="col-2 form-floating">
				<select class="form-select" id="limite" name="limite" v-model='jiraForm.limit'>
					<option value="">Qtd Registros</option>
					<option v-for="limit in project.limits" :value="limit">@{{ limit }}</option>
				</select>
				<label for="limite">Qtd Registros</label>
			</div>

		</div>

		<div class="row mt-3">

			<div class="col-2 form-floating"><!-- multiple="true" -->
				<select class="form-select" id="status" name="status" v-model='jiraForm.status'>
					<option value="" selected="selected">Status</option>
					<option v-for="status in project.statuses" :value="status.id">@{{ status.nome }}</option>
				</select>
				<label for="status">Status</label>
			</div>

			<div class="col-10 form-floating">
				<input class="form-control" id="busca" name="busca" v-model="jiraForm.search" placeholder="Busca">
				<label for="resumo">Busca</label>
			</div>
		</div>

		<div class="row mt-3">
			<div class="col-12">
				<a class="link-primary" v-if="verificarUsuario()" href="{{ @route('home') }}"><i class="bi bi-arrow-left"></i></a>
				<a class="link-primary" href="#" @click="recarregarTasks()"><i class="bi bi-arrow-counterclockwise"></i></a>
			</div>
		</div>

	</form>

	<table id="tarefas" class="table table-responsive table-striped table-hover tabela-fonte">
		<thead style="top:0; position: sticky">
			<tr>
				<th style="width: 5%">ID</th>
				<th style="width: 5%"></th>
				<th>Responsável</th>
				<th>Resumo</th>
				<th>Status</th>
				<th>SP Estimate</th>
				<th>Story Points</th>
				<th>Estimativa Original</th>
				<th>Estimativa</th>
				<!-- <th class="col-1">Ações</th> -->
			</tr>
		</thead>
		<tbody>

			<tr v-if="loading" class="text-center">
				<td colspan="10"><span class="spinner-border" role="status"></span></td>
			</tr>
			<tr v-if="!loading && !this.filteredItems.length">
				<td colspan="9">Sem tarefas</td>
			</tr>
			<tr v-if="!loading" v-for="tarefa in filteredItems" :key="tarefa.id" v-bind:class="[ verificarDadosZerados(tarefa) ? 'table-danger':'']">
				<td><a :href="tarefa.url" target="_blank">@{{ tarefa.id }}</a></td>
				<td>
					<img :src="tarefa.tipo.icone" style="max-width:30px;" :alt="tarefa.tipo.nome" :title="'Tipo: ' + tarefa.tipo.nome" />
					<img :src="tarefa.prioridade.icone" style="max-width:30px;" :alt="tarefa.prioridade.nome" :title="'Prioridade: '+tarefa.prioridade.nome" />
					<i v-if="tarefa.isAtendimento" class="bi bi-person-fill" title="Atendimento"></i>
				</td>
				<td>
					<img class="rounded-circle" style="max-width:48px;" v-if="tarefa.responsavel" :src="tarefa.responsavel.avatar" :alt="'Responsável: ' + tarefa.responsavel.nome" :title="'Responsável: ' + tarefa.responsavel.nome" />
					<span v-if="!tarefa.responsavel">Não atribuído</span>
				</td>
				<td>@{{ tarefa.resumo }}</td>
				<td>@{{ tarefa.status.nome }}</td>
				<td>@{{ tarefa.story_points_estimate }}</td>
				<td>@{{ tarefa.story_points }}</td>
				<td>@{{ tarefa.original_estimate }}h</td>
				<td>@{{ tarefa.estimate }}h</td>
				<!-- <td><a href="#">@click="recarregarTasks()"</a></td> -->
			</tr>
		</tbody>
		<tfoot>
			<td colspan='4'></td>
			<td id="totalSPE"></td>
			<td id="totalSP"></td>
			<td id="totalOriginalEstimate"></td>
			<td id="totalEstimate"></td>
		</tfoot>
	</table>
</div>
@endsection
