<nav class="navbar navbar-dark bg-dark">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleMenu()">
			<span class="navbar-toggler-icon"></span>
		</button>
	</div>
</nav>
<div class="collapse" id="navbarToggleExternalContent">
	<div class="bg-dark p-4">

		<div>
			{{ auth()->user()->name }}<br />

			@foreach(auth()->user()->roles as $role)

			{{ $role->nome }}<br />

			@foreach($role->actions as $key => $action)

			@if($action->subactions->isEmpty())
				<a href="{{ route($action->route_name) }}">{{ $action->texto }}</a>
			@endif

			<br/>

			@if(!$action->subactions->isEmpty())
			<div class="accordion" id="accordionExample">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
							{{ $action->texto }}
						</button>
					</h2>
					<div id="collapse{{$key}}" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
						<div class="accordion-body">


							<ul class="list-group">
								@foreach($action->subactions as $subaction)
								<li class="list-group-item"><a href="{{ route($subaction->route_name) }}">{{ $subaction->texto }}</a></li>
								@endforeach
							</ul>


						</div>
					</div>
				</div>

			</div>
			@endif
			@endforeach
			@endforeach

		</div>

	</div>
</div>