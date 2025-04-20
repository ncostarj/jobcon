<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri = 'http://jobcon.localhost';
	private $token;

    // public function __construct(){
    //     $this->clientId = config('spotify.SPOTIFY_CLIENT_ID');
    //     $this->clientSecret = config('spotify.SPOTIFY_CLIENT_SECRET');
    // }

	// public function index() {
	// 	return view('spotify.index');
	// }

	// public function allow()
    // {
	// 	$encodedScopes = urlencode('user-read-private user-read-email user-read-playback-state');
	// 	$encodedRedirectUri = urlencode($this->redirectUri);
	// 	// ($scopes ? '&scope=' . urlencode($scopes) : '') .

	// 	$url = "https://accounts.spotify.com/authorize?response_type=code" .
    //                         "&client_id={$this->clientId}".
	// 						"&scopes={$encodedScopes}" .
    //                         "&redirect_uri={$encodedRedirectUri}";

	// 	return redirect($url);
    // }

	public function getToken(Request $request)
    {
		$url = 'https://accounts.spotify.com/api/token';
		$credentials = 'Basic ' . base64_encode("{$this->clientId}:{$this->clientSecret}");
		$headers = [ 'Authorization' => $credentials ];
		$postData = [ 'code' => trim($request->code), 'grant_type' => 'client_credentials', 'redirect_uri' => $this->redirectUri ];
        $response = Http::withHeaders($headers)->asForm()->post($url, $postData);
		$this->token = $response->json();
    }

	public function getProfile(Request $request) {
		$this->getToken($request);

		$url = 'https://api.spotify.com/v1/me';
		$headers = [ 'Authorization' => "Bearer {$this->token['access_token']}" ];
		$profileResponse = Http::withHeaders($headers)->get($url);

		dump($url, json_encode($headers), $profileResponse->json());

		dd('----parando');

        return view('spotify.profile')->with(['profile' => $profile->json()]);
    }
}
