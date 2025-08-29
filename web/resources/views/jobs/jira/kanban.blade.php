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
@endsection
@section('content')
	<div class="col">
		<div class="col-2 coluna-lateral">
			<div class="row row-cols-1 g-4">
				<div class="col">
					<div class="card">
						<p class="card-header">Horas (Referência 1 pessoa)</p>
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Por dia<span class="badge bg-primary rounded-pill">@{{ referencia.hPorDia }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Por semana<span class="badge bg-primary rounded-pill">@{{ referencia.hPorSemana }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Por sprint<span class="badge bg-primary rounded-pill">@{{ referencia.hPorSprint }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Por mês<span class="badge bg-primary rounded-pill">@{{ referencia.hPorMes }}</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="col">
					<div class="card">
						<p class="card-header">Tarefas</p>
						<p v-if="loading" class="list-group-item text-center mt-3"><span class="spinner-border" role="status"></span></p>
						<ul v-if="!loading" class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Qtd Tarefas<span class="badge bg-primary rounded-pill">@{{ activeSprint.contadorTarefas.qtdTarefas }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								SPE Zerados<span v-bind:class="[ activeSprint.contadorTarefas.storyPointsEstimateZerado > 0 ? 'bg-danger':'bg-primary']" class="badge rounded-pill">@{{ activeSprint.contadorTarefas.storyPointsEstimateZerado }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								SP Zerados<span v-bind:class="[ activeSprint.contadorTarefas.storyPointsZerado > 0 ? 'bg-danger':'bg-primary']" class="badge rounded-pill">@{{ activeSprint.contadorTarefas.storyPointsZerado }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Estimativa Zerada<span v-bind:class="[ activeSprint.contadorTarefas.estimateZerado > 0 ? 'bg-danger':'bg-primary']" class="badge rounded-pill">@{{ activeSprint.contadorTarefas.estimateZerado }}</span>
							</li>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								Est. Org Zerada<span v-bind:class="[ activeSprint.contadorTarefas.estimateOriginalZerado > 0 ? 'bg-danger':'bg-primary']" class="badge rounded-pill">@{{ activeSprint.contadorTarefas.estimateOriginalZerado }}</span>
							</li>
							<!--  -->
							<li v-for="status in activeSprint.contadorTarefas.statuses" class="list-group-item d-flex justify-content-between align-items-center">
								@{{ status.nome }}<span class="badge bg-primary rounded-pill">@{{ status.qtd }}</span>
							</li>
						</ul>
					</div>
				</div>
				<div class="col">
					<div class="card">
						<p class="card-header d-flex justify-content-between align-items-center text-center">
							Sprint
							<small>2 Semanas</small>
							<span class="badge bg-success rounded-pill">(@{{ referencia.hPorSprint }}h)</span>
							<span v-bind:class="[ activeSprint.totalWorklog < referencia.hPorSprint ? 'bg-danger':'bg-primary']" class="badge rounded-pill">@{{ activeSprint.totalWorklog }}h</span>
						</p>
						<p v-if="loading" class="list-group-item text-center mt-3"><span class="spinner-border" role="status"></span></p>
						<ul v-if="!loading" class="list-group list-group-flush">
							<li v-for="log in activeSprint.registroTrabalhoSprint" class="list-group-item d-flex justify-content-between align-items-center text-center">
								@{{ log.dia }} @{{ log.data.replace(/([0-9]+)-([0-9]+)-([0-9]+)/,'$3/$2/$1') }}<span v-bind:class="[ log.horas < 8 ? 'bg-danger':'bg-primary']" class="badge rounded-pill">@{{ log.horas.toFixed(1) }}h</span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
