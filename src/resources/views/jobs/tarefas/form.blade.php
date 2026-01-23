@extends('common.layout')
@section('title', '- Tarefa Incluir')
@section('script')
<script type="text/javascript">
	var app = new Vue({
		el: '#tarefaApp',
		data: {
			horarioForm: {
				dia: '',
				hora: ''
			}
		},
		methods: {
			setDate: function(date) {
				let data = new Date();
				this.horarioForm.dia = data.toISOString().replace(/(T[0-9]+\:[0-9]+\:[0-9]+\.[0-9]+Z)/,'');
				this.horarioForm.hora = data.toLocaleTimeString();
			}
		},
		computed: {},
		created: function() {
			this.setDate(new Date());

		},
	})
</script>
@endsection
@section('content')

<div class="row">
	<div class="col">
		<h1 class="text-center">Incluir Tarefa</h1>
	</div>
</div>
<div class="row" id="tarefaApp">
	<div class="col-12">
		<form action="{{ route('jobs.pontos.update', [ 'ponto' => $ponto->id ]) }}" method="post">
			@method('PUT')
			@csrf
			<div class="row">
				<div class="col">
					<div class="form-floating mb-3">
						<input id="dia" name="dia" class="form-control" type="date" value="{{ $ponto->diaFormatted }}">
						<label>Dia: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="entrada" name="entrada" class="form-control" type="time" value="{{ $ponto->entrada->hora??'' }}">
						<label>Entrada: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="almoco_saida" name="almoco_saida" class="form-control" type="time" value="{{ $ponto->almoco_saida->hora??'' }}">
						<label>Almoço: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="almoco_retorno" name="almoco_retorno" class="form-control" type="time" value="{{ $ponto->almoco_retorno->hora??'' }}">
						<label>Retorno: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="saida" name="saida" class="form-control" type="time" value="{{ $ponto->saida->hora??'' }}">
						<label>Saída: </label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="form-floating mb-3">
						<select name="categoria" class="form-select">
							<option value="">Selecione</option>
							<option @if($ponto->categoria == 'home_office') selected="selected" @endif value="home_office">Home Office</option>
							<option @if($ponto->categoria == 'presencial') selected="selected" @endif value="presencial">Presencial</option>
						</select>
						<label>Categoria: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<select name="pedir_ajuste" class="form-select">
							<option value="">Selecione</option>
							<option @if($ponto->pedir_ajuste == '1') selected="selected" @endif value="1">Sim</option>
							<option @if($ponto->pedir_ajuste == '0') selected="selected" @endif value="0">Não</option>
						</select>
						<label>Pedir Ajuste: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<input id="observacao" name="observacao" class="form-control" type="text" value="{{ $ponto->observacao }}">
						<label for="observacao">Observação: </label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col text-right">
					<button class="btn btn-success mb-3">Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
