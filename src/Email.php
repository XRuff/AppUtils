<?php

namespace XRuff\App\Model\Utils;

use Nette;
use Nette\Database\Context;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
use Latte\Engine;
use Nette\DI\Container;

/**
 * Email management.
 */
class Email extends Nette\Object
{

	/** @var Nette\Database\Context */
	private $database;

	/** @var Nette\DI\Container */
	private $config;

	public function __construct(Context $database, Container $config)
	{
		$this->database = $database;
		$this->config = $config;
	}

	public function send($from, $to, $subject, $body)
	{
		$mail = new Message;
		$mail->setFrom($from)
			->addTo($to)
			->setSubject($subject)
			->setHtmlBody($body);
		$params = $this->config->getParameters();
		$mailer = new SmtpMailer(array(
			'host' => $params['mailer']['host'],
			'username' => $params['mailer']['username'],
			'password' => $params['mailer']['password'],
			'port' => $params['mailer']['port']
		));
		$mailer->send($mail);
	}

}