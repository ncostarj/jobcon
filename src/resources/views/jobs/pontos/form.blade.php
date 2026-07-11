@extends('common.layout')
@section('title', '- Ponto - Incluir')
@section('script')
<script type="text/javascript">
	var app = new Vue({
		el: '#horarioApp',
		data: {
			horarioForm: {
				dia: '',
				hora: ''
			}
		},
		methods: {
			setDate: function(date) {
				let data = new Date();
				this.horarioForm.dia = data.toISOString().replace(/(T[0-9]+\:[0-9]+\:[0-9]+\.[0-9]+Z)/, '');
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
	<div class="col text-center d-flex justify-content-between align-items-start">
		<h1 class="text-center">Alteração de ponto</h1>
		<a href="{{ route('jobs.pontos.index') }}" title="Voltar"><i class="bi bi-arrow-left fs-4"></i></a>
	</div>
</div>
<div class="row" id="horarioApp">
	<div class="col-12">
		@if(isset($ponto))
		<form action="{{ route('jobs.pontos.update', [ 'id' => $ponto->id ]) }}" method="post">
		@else
		<form action="{{ route('jobs.pontos.create') }}" method="post">
		@endif
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
						<select id="categoria" name="categoria" class="form-select">
							<option value="">Selecione</option>
							<option @if($ponto->categoria == 'home_office') selected="selected" @endif value="home_office">Home Office</option>
							<option @if($ponto->categoria == 'presencial') selected="selected" @endif value="presencial">Presencial</option>
						</select>
						<label>Categoria: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<select id="pedir_ajuste" name="pedir_ajuste" class="form-select">
							<option value="">Selecione</option>
							<option @if($ponto->pedir_ajuste == '1') selected="selected" @endif value="1">Sim</option>
							<option @if($ponto->pedir_ajuste == '0') selected="selected" @endif value="0">Não</option>
						</select>
						<label>Pedir Ajuste: </label>
					</div>
				</div>
				<div class="col">
					<div class="form-floating mb-3">
						<select id="ajuste_finalizado" name="ajuste_finalizado" class="form-select">
							<option value="">Selecione</option>
							<option @if($ponto->ajuste_finalizado == '1') selected="selected" @endif value="1">Sim</option>
							<option @if($ponto->ajuste_finalizado == '0') selected="selected" @endif value="0">Não</option>
						</select>
						<label>Ajuste Finalizado: </label>
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
				<div class="col">
					<label>Entrada: </label>
					<div class="input-group mb-3">
						<input type="hidden" name="horarios[0][tipo]" value="entrada">
						<input type="time" name="horarios[0][hora]" class="form-control" placeholder="Hora" aria-label="Hora" value="{{ $ponto->entrada->hora_formatted??'' }}">
						<span class="input-group-text">-</span>
						<input type="text" name="horarios[0][observacao]" class="form-control" placeholder="Observação" aria-label="Observação" value="{{ $ponto->entrada->observacao??'' }}">
					</div>
				</div>
				<div class="col">
					<label>Almoço: </label>
					<div class="input-group mb-3">
						<input type="hidden" name="horarios[1][tipo]" value="almoco_saida">
						<input type="time" name="horarios[1][hora]" class="form-control" placeholder="Hora" aria-label="Hora" value="{{ $ponto->almoco_saida->hora_formatted??'' }}">
						<span class="input-group-text">-</span>
						<input type="text" name="horarios[1][observacao]" class="form-control" placeholder="Observação" aria-label="Observação" value="{{ $ponto->almoco_saida->observacao??'' }}">
					</div>
				</div>
				<div class="col">
					<label>Almoço Retorno: </label>
					<div class="input-group mb-3">
						<input type="hidden" name="horarios[2][tipo]" value="almoco_retorno">
						<input type="time" name="horarios[2][hora]" class="form-control" placeholder="Hora" aria-label="Hora" value="{{ $ponto->almoco_retorno->hora_formatted??'' }}">
						<span class="input-group-text">-</span>
						<input type="text" name="horarios[2][observacao]" class="form-control" placeholder="Observação" aria-label="Observação" value="{{ $ponto->almoco_retorno->observacao??'' }}">
					</div>
				</div>
				<div class="col">
					<label>Saída: </label>
					<div class="input-group mb-3">
						<input type="hidden" name="horarios[3][tipo]" value="saida">
						<input type="time" name="horarios[3][hora]" class="form-control" placeholder="Hora" aria-label="Hora" value="{{ $ponto->saida->hora_formatted??'' }}">
						<span class="input-group-text">-</span>
						<input type="text" name="horarios[3][observacao]" class="form-control" placeholder="Observação" aria-label="Observação" value="{{ $ponto->saida->observacao??'' }}">
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
