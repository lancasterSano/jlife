<?php
class JLIFE_MAIL {
	private $receiver;
	private $subject;
	private $body;
	private $sender;

    public function __construct($receiver, $subject, $body, $sender){
    	$this->receiver = $receiver;
    	$this->subject = $subject;
    	$this->body = $body;
    	$sender = (empty($sender)) ? 'JLIFE <registration@'. $_SERVER['HTTP_HOST'].'>' : $sender;
    	$this->sender = $sender;
	}
	public function send()
	{
		$this->SendMail($this->receiver, $this->subject, $this->body, $this->sender);
	}
	public static function SendMail($to, $subject, $body, $sender)
	{
		$headers = 'From: '. $sender . "\r\n" ;
		$headers .='Reply-To: '. $to . "\r\n" ;
		$headers .='X-Mailer: PHP/' . phpversion();
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";   
		return mail($to, $subject, $body, $headers);
	}
}
?>