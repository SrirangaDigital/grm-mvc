<?php

class mail extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
		$this->send();
	}

	public function send() {
		
		$data = $this->model->getPostData();
		($data) ? $this->postman($data) : $this->view('error/prompt', array('msg' => FB_FAILURE_MSG));
	}

	public function postman($data) {

		$mail = new PHPMailer();

		if(!$data['g-recaptcha-response']){
			$this->view('error/prompt', array('msg' => FB_CAPTCHA_MSG));
		}
		else{
	
			$secretKey = "6LctMFQUAAAAAAnO81mv2gu6r05uTL1fXpS4ys2g";
	        $ip = $_SERVER['REMOTE_ADDR'];
	        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=". $data['g-recaptcha-response'] . "&remoteip=".$ip);
	        $responseKeys = json_decode($response,true);

	        if(intval($responseKeys["success"])){

	        	date_default_timezone_set('Etc/UTC');

	        	//Tell PHPMailer to use SMTP
	        	$mail->isSMTP();
	        	$mail->Host = 'smtp.gmail.com';
	        	$mail->Port = 587;
	        	$mail->SMTPSecure = 'tls';
	        	$mail->SMTPAuth = true;
	        	$mail->Username = SERVICE_EMAIL;
	        	$mail->Password = SERVICE_EMAIL_PASSWORD;
	        	$mail->setFrom($data['email'], $data['name']);
	        	$mail->addReplyTo($data['email'], $data['name']);
	        	$mail->addAddress(SERVICE_EMAIL, SERVICE_NAME);
	        	$mail->Subject = $data['subject'];
	        	$mail->msgHTML($data['message']);
	        	$mail->AltBody = $data['message'];
	        	
	        	// if (!$mail->send()) {
	        	//     echo "Mailer Error: " . $mail->ErrorInfo;
	        	// } else {
	        	//     echo "Message sent!";
	        	// }
				// $mail->isSMTP();                                      // Set mailer to use SMTP
				// $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				// $mail->SMTPAuth = true;                               // Enable SMTP authentication
				// $mail->Username = SERVICE_EMAIL;                 // SMTP username
				// $mail->Password = SERVICE_EMAIL_PASSWORD;                           // SMTP password
				// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				// $mail->Port = 465;                                    // TCP port to connect to


				// $mail->isMail();
				// $mail->setFrom($data['email'], $data['name']);
				// $mail->addReplyTo($data['email'], $data['name']);
				// $mail->addAddress(SERVICE_EMAIL, SERVICE_NAME);
				// $mail->Subject = '[' . $data['type'] . '] ' . $data['subject'];
				// $mail->Body = $data['message'];

				if($mail->send()) {

					$this->view('page/prompt', array('msg' => FB_SUCCESS_MSG));
				}
				else {

					$this->view('error/prompt', array('msg' => FB_FAILURE_MSG));
				}
			}
			else{
					$this->view('error/prompt', array('msg' => FB_CAPTCHA_RESP_MSG));
			}
		}
	}
}

?>