<?php

class Model {

	public function __construct() {

		$this->db = new Database();

		$this->viewHelper = new viewHelper();
		$this->transliterator = new Transliterators();
	}

	public function getPostData() {

		if (isset($_POST['submit'])) {

			unset($_POST['submit']);	
		}

		if(!array_filter($_POST)) {
		
			return false;
		}
		else {

			return array_filter(filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS));
		}
	}

	public function getGETData() {

		if(!array_filter($_GET)) {
		
			return false;
		}
		else {

			return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
		}
	}

	public function getArtefactFromJsonPath($path){

		$contentString = file_get_contents($path);
		$content = json_decode($contentString, true);

		return $content;
	}


	public function preProcessPOST ($data) {

		return array_map("trim", $data);
	}

	public function preProcessGET ($data) {

		return array_map("trim", $data);
	}

	public function encrypt ($data) {

		return sha1(SALT.$data);
	}
	
	public function sendLetterToPostman ($fromName = SERVICE_NAME, $fromEmail = SERVICE_EMAIL, 
		$toName = SERVICE_NAME, $toEmail = SERVICE_EMAIL, $subject = 'Bounce', 
		$message = '', $successMessage = 'Bounce', $errorMessage = 'Error') {

	    $mail = new PHPMailer();
        $mail->isSendmail();
        $mail->isHTML(true);
        $mail->setFrom($fromEmail, $fromName);
        $mail->addReplyTo($fromEmail, $fromName);
        $mail->addAddress($toEmail, $toName);
        $mail->Subject = $subject;
        $mail->Body = $message;
        
        return $mail->send();
 	}

 	public function bindVariablesToString ($str = '', $data = array()) {

 		unset($data['count(*)']);
	    
	    while (list($key, $val) = each($data)) {
	    
	        $str = preg_replace('/:'.$key.'/', $val, $str);
		}
	    return $str;
 	}

 	public function listFiles ($path = '') {

 		if (!(is_dir($path))) return array();

 		$files = scandir($path);
 
 		unset($files[array_search('.', $files)]);
 		unset($files[array_search('..', $files)]);
 
 		return $files;
 	}

 	public function transliterate ($data, $script) {

 		return $this->transliterator->transliterate($data, $script);
 	}

 	public function identifyScript($text) {

 		// This should be elaborated to include other scripts
 		if (preg_match('/[அஆஇஈஉஊஏஐஓஔகஙசஜஞடணதநபமயரலவஶஷஸஹாிீுூேைோௌௐ]/u', $text)) return 'ta';
 		return (preg_match('/[a-zA-Zāīūṛṝṁḥṭḍṅñṇśṣ]/u', $text)) ? 'iast' : 'devanagari';
 	}

 	public function toDevanagari($text) {

 		$text = $this->iast2devanagari($text);
 		return $text;
 	}

 	public function iast2devanagari($text) {

		$text = str_replace('kh', 'ख.', $text);
		$text = str_replace('gh', 'घ.', $text);
		$text = str_replace('ch', 'छ.', $text);
		$text = str_replace('jh', 'झ.', $text);
		$text = str_replace('ṭh', 'ठ.', $text);
		$text = str_replace('ḍh', 'ढ.', $text);
		$text = str_replace('th', 'थ.', $text);
		$text = str_replace('dh', 'ध.', $text);
		$text = str_replace('ph', 'फ.', $text);
		$text = str_replace('bh', 'भ.', $text);
		$text = str_replace('k', 'क.', $text);
		$text = str_replace('g', 'ग.', $text);
		$text = str_replace('ṅ', 'ङ.', $text);
		$text = str_replace('c', 'च.', $text);
		$text = str_replace('j', 'ज.', $text);
		$text = str_replace('ñ', 'ञ.', $text);
		$text = str_replace('ṭ', 'ट.', $text);
		$text = str_replace('ḍ', 'ड.', $text);
		$text = str_replace('ṇ', 'ण.', $text);
		$text = str_replace('t', 'त.', $text);
		$text = str_replace('d', 'द.', $text);
		$text = str_replace('n', 'न.', $text);
		$text = str_replace('p', 'प.', $text);
		$text = str_replace('b', 'ब.', $text);
		$text = str_replace('m', 'म.', $text);
		$text = str_replace('y', 'य.', $text);
		$text = str_replace('r', 'र.', $text);
		$text = str_replace('l', 'ल.', $text);
		$text = str_replace('v', 'व.', $text);
		$text = str_replace('ś', 'श.', $text);
		$text = str_replace('ṣ', 'ष.', $text);
		$text = str_replace('s', 'स.', $text);
		$text = str_replace('h', 'ह.', $text);

		$text = str_replace('ai', 'ऐ', $text);
		$text = str_replace('au', 'औ', $text);
		$text = str_replace('a', 'अ', $text);
		$text = str_replace('ā', 'आ', $text);
		$text = str_replace('i', 'इ', $text);
		$text = str_replace('ī', 'ई', $text);
		$text = str_replace('u', 'उ', $text);
		$text = str_replace('ū', 'ऊ', $text);
		$text = str_replace('ṛ', 'ऋ', $text);
		$text = str_replace('ṝ', 'ॠ', $text);
		$text = str_replace('e', 'ए', $text);
		$text = str_replace('o', 'ओ', $text);
		$text = str_replace('\'', 'ऽ', $text);
		$text = str_replace('ṁ', 'ं', $text);
		$text = str_replace('ḥ', 'ः', $text);

		$text = str_replace('.अ', '', $text);
		$text = str_replace('.आ', 'ा', $text);
		$text = str_replace('.इ', 'ि', $text);
		$text = str_replace('.ई', 'ी', $text);
		$text = str_replace('.उ', 'ु', $text);
		$text = str_replace('.ऊ', 'ू', $text);
		$text = str_replace('.ऋ', 'ृ', $text);
		$text = str_replace('.ॠ', 'ॄ', $text);
		$text = str_replace('.ए', 'े', $text);
		$text = str_replace('.ऐ', 'ै', $text);
		$text = str_replace('.ओ', 'ो', $text);
		$text = str_replace('.औ', 'ौ', $text);

		$text = str_replace('.', '्', $text);

		$text = str_replace('0', '०', $text);
		$text = str_replace('1', '१', $text);
		$text = str_replace('2', '२', $text);
		$text = str_replace('3', '३', $text);
		$text = str_replace('4', '४', $text);
		$text = str_replace('5', '५', $text);
		$text = str_replace('6', '६', $text);
		$text = str_replace('7', '७', $text);
		$text = str_replace('8', '८', $text);
		$text = str_replace('9', '९', $text);

 		return $text;
 	}
}

?>