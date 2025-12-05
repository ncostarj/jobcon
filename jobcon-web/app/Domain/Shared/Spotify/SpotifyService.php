<?php

namespace App\Models\Spotify;

use App\Models\Common\BaseGateway;
use Illuminate\Support\Facades\Http;

class SpotifyService extends BaseGateway
{

	protected $client_id;
	protected $client_secret;
	protected $token;
	protected $verifier;

	public function __construct()
	{
		$this->client_id = config('spotify.SPOTIFY_CLIENT_ID');
		$this->client_secret =  config('spotify.SPOTIFY_CLIENT_SECRET');
	}

	public function generateCodeVerifier(int $length)
	{
		$text = '';
		$possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		for ($i = 0; $i < $length; $i++) {
			$text .= substr($possible, floor(rand() * strlen($possible)));
		}
		return $text;
	}

	public function generateCodeChallenge(string $codeVerifier)
	{
		$data = mb_convert_encoding($codeVerifier, 'utf8');
		$digest = hash('sha256', $data);
		$base64encoded = base64_encode(mb_convert_encoding($digest, 'utf8'));
		$base64encoded = preg_replace("/\+/", '-', $base64encoded);
		$base64encoded = preg_replace("/\//", '_', $base64encoded);
		$base64encoded = preg_replace("/=+$/", '', $base64encoded);
		return $base64encoded;
	}

	public function redirectToAuthCodeFlow(string $client_id)
	{
		$verifier = $this->generateCodeVerifier(128);
		$challenge = $this->generateCodeChallenge($verifier);

		$data = http_build_query([
			'client_id' => $this->client_id,
			'response_type' => 'code',
			'redirect_uri' => "http://jobcon.localhost",
			'scope' => "user-read-private user-read-email",
			'code_challenge_method' => 'S256',
			'code_challeng' => $challenge
		]);

		return "https://accounts.spotify.com/authorize?{$data}";
	}

	public function getAccessToken(string $client_id, string $code): string
	{
		$verifier = session()->get('verifier');

		$data = [
			'client_id' => $this->client_id,
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => "http://jobcon.localhost",
			'code_verifier' => $verifier
		];

		dump($data);

		$result = Http::withHeaders([
			"Content-Type" => "application/x-www-form-urlencoded"
		])->post("https://accounts.spotify.com/api/token", $data);

		dd($result->json());


		// const result = await fetch("https://accounts.spotify.com/api/token", {
		// 	method: "POST",
		// 	headers: { "Content-Type": "application/x-www-form-urlencoded" },
		// 	body: params
		// });

		// const { access_token } = await result.json();
		// return access_token;
		// TODO: Get access token for code
		return '';
	}

	public function fetchProfile(string $token)
	{
		// TODO: Call Web API
	}

	function populateUI($profile)
	{
		// TODO: Update UI with profile data
	}

	public function test($code)
	{
		$retorno = [
			'url' => '',
			'data' => '',
			'message' => ''
		];

		if (empty($code)) {
			$retorno['url'] = $this->redirectToAuthCodeFlow($this->client_id);
			$retorno['message'] = 'Needs authentication';
		} else {
			$accessToken = $this->getAccessToken($this->client_id, $code);
			$profile = $this->fetchProfile($accessToken);
			$retorno['data'] = $this->populateUI($profile);
		}

		return $retorno;
	}
}
