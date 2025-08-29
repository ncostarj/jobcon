<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleMenu()">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
<div class="collapse" id="navbarToggleExternalContent">
  <div class="bg-dark p-4">

	@if (Route::has('login'))
		@auth
			<a href="{{ url('/home') }}" class="">Home</a>
			<a href="{{ route('auth.logout') }}">Sair</a>
		@else
			<a href="{{ route('login') }}" class="">Log in</a>

			@if (Route::has('register'))
				<a href="{{ route('register') }}" class="ml-4 ">Register</a>
			@endif
		@endauth
	@endif


	<div class="accordion" id="accordionExample">
		<div class="accordion-item">
		  <h2 class="accordion-header">
			<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
			  Jobs
			</button>
		  </h2>
		  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
			<div class="accordion-body">
			  	<ul class="list-group">
					<li class="list-group-item"><a href="{{ route('jobs.dashboard.index') }}">Dashboard</a></li>
					<li class="list-group-item"><a href="{{ route('jobs.pontos.index') }}">Marcações do ponto</a></li>
					<li class="list-group-item"><a href="{{ route('jobs.contracheques.index') }}">Contracheques</a></li>
					<li class="list-group-item"><a href="{{ route('jobs.ferias.index') }}">Ferias</a></li>
				</ul>
			</div>
		  </div>
		</div>
		<!--
		<div class="accordion-item">
		  <h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			  Accordion Item #2
			</button>
		  </h2>
		  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
			<div class="accordion-body">
			  <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
			</div>
		  </div>
		</div>
		<div class="accordion-item">
		  <h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
			  Accordion Item #3
			</button>
		  </h2>
		  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
			<div class="accordion-body">
			  <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
			</div>
		  </div>
		</div>
		-->
	  </div>
  </div>
</div>
