<?php

namespace App\Models\Slack;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SlackNotification
{

	protected $token;
	protected $channel;
	protected $status;
	protected $message;
	protected $username;
	protected $request;

	public function setToken(string $token)
	{
		$this->token = $token;
		return $this;
	}

	public function setChannel(string $channel)
	{
		$this->channel = $channel;
		return $this;
	}

	public function setStatus(string $status)
	{
		$this->status = $status;
		return $this;
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
		return $this;
	}

	public function from(string $username) {
		$this->username = $username;
		return $this;
	}

	private function sendMessage($request)
	{
		$data = [
			"channel" => $this->channel,
			"text" => $this->message,
		];

		if(!empty($this->username)) {
			$data = array_merge($data, [
				"username" => $this->username
			]);
		}

		$request->post('https://slack.com/api/chat.postMessage', $data);
		return $this;
	}

	private function sendStatus($request)
	{
		$request->post('https://slack.com/api/users.profile.set', [
			"profile" => [
				"status_emoji" => $this->status,
				"status_expiration" => 0
			]
		]);
		return $this;
	}

	public function notify()
	{
		$request = Http::withHeaders([
			'Content-Type' => 'application/json;charset=utf-8',
			'Authorization' => 'Bearer ' . $this->token
		]);
		$this
			->sendMessage($request)
			->sendStatus($request);
	}
}


// Http::withHeaders([
// 			'Content-Type' => 'application/json;charset=utf-8',
// 			'Authorization' => 'Bearer ' . config('notifications.SLACK_BOT_USER_OAUTH_TOKEN')
// 		])
// 		->post('https://slack.com/api/chat.postMessage',[
// 			"channel" => "C07GVH97WAC",
// 			"text" => $saudacao->getTexto(),
// 			"username" => "Newton Costa"
// 		]);

// 		Http::withHeaders([
// 			'Content-Type' => 'application/json;charset=utf-8',
// 			'Authorization' => 'Bearer ' . config('notifications.SLACK_BOT_USER_OAUTH_TOKEN')
// 		])
// 		->post('https://slack.com/api/users.profile.set',[
// 			"profile" => [
// 				"status_emoji" => $saudacao->getIcone(),
// 				"status_expiration"=> 0
// 			]
// 		]);
