<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Mailable
{
	use Queueable, SerializesModels;
	public $emailToken;

	/**
	 * Create a new message instance.
	 *
	 * @param $emailToken
	 */
	public function __construct($emailToken)
	{
		$this->emailToken = $emailToken;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->subject('Verify Email')
		            ->markdown('emails.verify_email');
	}
}
