<?php

namespace XRuff\App\Model\Utils;

use Nette;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

/**
 * Email management.
 */
class Email extends Nette\Object
{

	/** @var IMailer */
	private $mailer;

	/** @var Message */
	private $mail;

	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	/**
	 * Send plain text email.
	 * @param string|array $from Email or format "John Doe" <doe@example.com> or array of those
	 * @param string|array $to Email or format "John Doe" <doe@example.com> or array of those
	 * @param string $subject
	 * @param string $body
	 */
	public function send($from, $to, $subject, $body)
	{
		$mail = $this->composeMail($from, $to, $subject);
		$mail->setBody($body);
		$this->mailer->send($mail);
	}

	/**
	 * Send plain html formated email.
	 * @param string|array $from Email or format "John Doe" <doe@example.com> or array of those
	 * @param string|array $to Email or format "John Doe" <doe@example.com> or array of those
	 * @param string $subject
	 * @param string $body
	 */
	public function sendHtml($from, $to, $subject, $body)
	{
		$mail = $this->composeMail($from, $to, $subject);
		$mail->setHtmlBody($body);
		$this->mailer->send($mail);
	}

	/**
	 * Add email to mail message object
	 * @param string|array $email
	 */
	private function addEmail($email)
	{
		if (is_array($email)) {
			$this->mail->addTo($email[0], $email[1]);
		} else {
			$this->mail->addTo($email);
		}
	}

	private function processToEmail($email)
	{
		if (is_array($email)) {
			foreach ($email as $item) {
				$this->addEmail($item);
			}
		} else {
			$this->addEmail($email);
		}

		return $this;
	}

	private function composeMail($from, $to, $subject)
	{
		$this->mail = new Message;
		$this->mail
			->setFrom($from)
			->setSubject($subject);

		$this->processToEmail($to);

		return $this->mail;
	}

}
