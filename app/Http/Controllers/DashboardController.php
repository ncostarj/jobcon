<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Jira\JiraService;
use App\Models\Common\MyCalendar;
use App\Models\Spotify\SpotifyService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
	//

	public function index(Request $request,  SpotifyService $spotifyService)
	{
		// $spotifyResponse = $spotifyService->test($request->code);

		// if(!empty($spotifyResponse['url'])) {
		// 	return Redirect::to($spotifyResponse['url']);
		// }

		$usuario = session()->get('usuario');

		$dados = (object) [
			'projeto' => env('JIRA_PROJECT_ID'),
			'usuario' => $usuario,
			'icons' => (object) [
				'entrada' => 'bi bi-arrow-right-circle-fill',
				'almoco_saida' => 'bi bi-pause-circle-fill',
				'almoco_retorno' => 'bi bi-play-circle-fill',
				'saida' => 'bi bi-arrow-left-circle-fill'
			],
			'filtro' => $request->all()
		];

		return view('jobs.dashboard.index', compact('dados'));
	}
}
