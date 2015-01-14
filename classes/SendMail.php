<?php
class SendMail {

	private $error;
	private $mail;
	private static $_instance = null;

	public static function getInstance(){
		//cek koneksi 
		if(!isset(Self::$_instance)){
			Self::$_instance = new SendMail();
		}
		return self::$_instance;
	}


	private function __construct() {
		
		require 'PHPMailerAutoload.php';
		$this->mail = new PHPMailer;
		

		$this->mail->SMTPDebug = 3;                               // Enable verbose debug output

		$this->mail->isSMTP();                                      // Set mailer to use SMTP
		$this->mail->Host = Config::get('mail/smtp_server');  // Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
		$this->mail->Username = Config::get('mail/smtp_user');                 // SMTP username
		$this->mail->Password = Config::get('mail/smtp_pass');                           // SMTP password
		$this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = 587;                                    // TCP port to connect to

		$this->mail->From = Config::get('mail/smtp_user');
		$this->mail->FromName = Config::get('mail/alias');
		
		//$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	}
	public function kirim($toEmail){

		
		$this->mail->addAddress($toEmail['tujuan']);     // Add a recipient
		//$this->mail->addAddress('ellen@example.com');               // Name is optional
		//$this->mail->addReplyTo('info@example.com', 'Information');
		//$this->mail->addCC('cc@example.com');
		//$this->mail->addBCC('bcc@example.com');

		//$this->mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		$this->mail->isHTML(true);                                  // Set email format to HTML

		$this->mail->Subject = $toEmail['subjek'];
		$this->mail->Body    = $toEmail['isi'];
		if(!$this->mail->send()) {
			$this->error=$this->mail->ErrorInfo;
		    return false;
		} else {
		    return true;
		}
	}

	public function getError(){
		return $this->error;
	}
}