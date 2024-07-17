<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuperUserEmail extends Mailable
{
	use Queueable, SerializesModels;
	public $user, $password;

	/**
	 * Create a new message instance.
	 *
	 * @param $user
	 */
	public function __construct($user, $password)
	{
		$this->user = $user;
		$this->password = $password;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('System User Credential')
			->markdown('emails.superuser_email');
	}
}
