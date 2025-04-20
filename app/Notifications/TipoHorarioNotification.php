<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class TipoHorarioNotification extends Notification
{
	use Queueable;
	public $message;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($message)
	{
		$this->message = $message;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['slack'];
	}

	public function toSlack(object $notifiable)
	{
		return (new SlackMessage)
			->from('Newton Costa', ':smile:') // user
			// ->to('reuniao') // channel name
			->content($this->message);
		// ->attachment(function ($attachment) {
		//     $attachment->title('Registro de batida de ponto')
		//         ->content($this->message);
		// });
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			//
		];
	}
}
