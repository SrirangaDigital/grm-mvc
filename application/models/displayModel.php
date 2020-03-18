<?php

class displayModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function getBhashyaText($bhashya, $page = 0, $script = DEFAULT_SCRIPT) {
		
		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$pageQuery = ($page != 0) ? ' ORDER BY id LIMIT ' . ($page - 1) . ', 1' : '';

		$sth = $dbh->prepare('SELECT * FROM ' . BHASHYA_TABLE . ' WHERE bhashya = :bhashya' . $pageQuery);
		$sth->bindParam(':bhashya', $bhashya);
		
		$sth->execute();
		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			$result->content = $this->transliterate($result->content, $script);
			array_push($data, $result);
		}

		$dbh = null;
		return $data;
	}

	public function getMoolaText($bhashya, $page = 0, $script = DEFAULT_SCRIPT) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$pageQuery = ($page != 0) ? ' ORDER BY id LIMIT ' . ($page - 1) . ', 1' : '';

		$sth = $dbh->prepare('SELECT * FROM ' . BHASHYA_TABLE . ' WHERE bhashya = :bhashya' . $pageQuery);
		$sth->bindParam(':bhashya', $bhashya);
		
		$sth->execute();
		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			$xml = simplexml_load_string($result->content);
			foreach ($xml->xpath("//div[contains(@class, 'bhashya')]") as $bhashyaDiv) {
				
				// Remove all bhashyas
				$dom = dom_import_simplexml($bhashyaDiv);
		        $dom->parentNode->removeChild($dom);				
			}

			$result->content = html_entity_decode($xml->asXML());
			$result->content = $this->transliterate($result->content, $script);
			array_push($data, $result);
		}

		$dbh = null;
		return $data;
	}

	public function getVyakhyaText($vyakhya) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;
	
		$sth = $dbh->prepare('SELECT * FROM ' . VYAKHYA_TABLE . ' WHERE vyakhya = :vyakhya');
		$sth->bindParam(':vyakhya', $vyakhya);
		
		$sth->execute();
		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			array_push($data, $result);
		}

		if($data) $data['vyakhya'] = $vyakhya;

		$dbh = null;
		return $data;
	}

	public function getVyakhyaByID($vyakhya, $id, $script = DEFAULT_SCRIPT) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$bindID = '^' . $vyakhya . '_' . $id . '_VD';

		$sth = $dbh->prepare('SELECT * FROM ' . VYAKHYA_TABLE . ' WHERE id regexp :id');
		$sth->bindParam(':id', $bindID);
		$sth->execute();
		
		$data = '';

		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			$content = $result->content;
			$content = str_replace('data-anchor', 'id="' . $result->id . '" data-anchor', $content);
			$content = $this->transliterate($content, $script);

			$data .= $content;
		}

		$dbh = null;
		return $this->processLinks($data);
	}

	public function listParaWithVyakhya($bhashya, $vyakhya) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh)) return null;
		
		$id = '^' . $vyakhya . '_' . $this->viewHelper->getBhashyaDetails($bhashya)['prefix'] . '_';

		$sth = $dbh->prepare('SELECT id FROM ' . VYAKHYA_TABLE . ' WHERE id REGEXP :id ORDER BY vdid');

		$sth->bindParam(':id', $id);
		
		$sth->execute();
		$data = array();

		while($result = $sth->fetch(PDO::FETCH_OBJ)) {

			$id = $result->id;
			$id = preg_replace('/' . $vyakhya . '_(.*)_VD.*/', "$1", $id);
			array_push($data, $id);
		}

		$dbh = null;
		return implode(';', array_unique($data));
	}

	public function getBhashyaByID($id) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh)) return null;
		
		$bindID = 'id="' . $id . '"';
		$sth = $dbh->prepare('SELECT * FROM ' . BHASHYA_TABLE . ' WHERE content REGEXP :id');
		$sth->bindParam(':id', $bindID);
		$sth->execute();
		$result = $sth->fetch(PDO::FETCH_OBJ);
		if(!($result)) return False;

		$xml = simplexml_load_string($result->content);

		$bhashyas = $xml->xpath('//div[@id="' . $id . '"]');
		$bhashya = $bhashyas[0];

		$moolaID = preg_replace('/_B[0-9]+/', '', $id);

		// moolaID will be same as id for leading. aux and intro bhashyas
		$data['versetext'] = '';
		if($moolaID != $id) {

			$moolas = $xml->xpath('//div[@id="' . $moolaID . '"]');
			$versetext = $moolas[0]->xpath('child::div[@class="versetext"]');

			foreach ($versetext as $versetextSnippet) {

				$data['versetext'] .= $versetextSnippet->asXML();
			}
		}

		$ancestors = $bhashya->xpath('ancestor::div[@class="chapter"] | ancestor::div[@class="section"]');

		$address = array();
	
		foreach ($ancestors as $ancestor) {
			
			$attr = $ancestor->attributes();
			array_push($address, (string) $attr['data-title']);
		}

		$data['bhashyaPara'] = $bhashya->asXML();
		$data['address'] = $address;
		$data['bhashya'] = (string) $result->bhashya;

		return $data;
	}

	public function getPrevNextVyakhya($bhashya = '', $vyakhya = '', $id = '') {

		$vyakhyaList = $this->listParaWithVyakhya($bhashya, $vyakhya);

		$vyakhyaParas = explode(';', $vyakhyaList);

		$key = array_search($id, $vyakhyaParas);

		$data = [];
		if ($key > 0) $data['prevID'] = $vyakhyaParas[$key - 1];
		if ($key < (sizeof($vyakhyaParas) - 1 )) $data['nextID'] = $vyakhyaParas[$key + 1];

		return $data;
	}

	public function listPrateeka($vyakhya, $id) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh)) return null;
		
		$queryID = $vyakhya . '_' . $id . '_VD.*';

		$sth = $dbh->prepare('SELECT * FROM ' . VYAKHYA_TABLE . ' WHERE id REGEXP :id');
		$sth->bindParam(':id', $queryID);
		$sth->execute();

		$content = array();
		while($result = $sth->fetch(PDO::FETCH_ASSOC)) {

			$xml = new SimpleXMLElement($result['content']);
			foreach ($xml->p as $para) {
				
				$prateeka['id'] = (string) $xml['data-anchor'];
				if(($para['class'] == 'prateeka') || ($para['class'] == 'uddharana')) {

					$prateeka['vid'] = $result['id'];
					$prateeka['text'] = strip_tags(((string) $para->asXML()));
					array_push($content, $prateeka);
				}
			}
		}
		return $content;
	}

    public function processLinks($text) {

        $text = str_replace('#', '?id=', $text);
        $text = str_replace(" । ", "&nbsp;। ", $text);

        $text = preg_replace('/<aside>(.*?)<\/aside>/', '<sup><a tabindex="1" class="footNote" data-toggle="popover"><i class="fa fa-asterisk"></i></a><span class="aside">' . "$1" . '</span></sup>', $text);

        // $text = preg_replace('/<span class="qt"><a href="(.*?)">(.*?) (\(.*?\))<\/a><\/span>/', '<span class="qt"><a href="' . "$1" . '">' . "$2" . ' ' . "$3" . '</a></span>', $text);

        return $text;
    }

    public function getPrimaryNav($bhashya, $script) {

        return $this->transliterate(file_get_contents(PHY_JSON_PRECAST_URL . 'primary-nav/' . $bhashya . '.php'), $script);
    }

    public function getJumbotron($bhashya, $script) {

    	$data = $this->transliterate(file_get_contents(PHY_JUMBOTRON_URL . $bhashya . '.php'), $script);
        $data = str_replace('{{PUBLIC_URL}}', PUBLIC_URL, $data);
        return str_replace('{{BASE_URL}}', BASE_URL, $data);
    }

    public function getCorpusName($vyakhya, $script) {

		$data = $this->viewHelper->getBhashyaDetails($this->viewHelper->bhashyaDef{$vyakhya})['corpus-name'];
		return $this->transliterate($data, $script);
    }

    public function getVyakhyaName($vyakhya, $script) {

		$data = $this->viewHelper->getBhashyaDetails($this->viewHelper->bhashyaDef{$vyakhya})['name'];
		return $this->transliterate($data, $script);
    }

    public function getIdOftheDay() {

    	date_default_timezone_set('Asia/Kolkata');
    	$date = date("Y-n-j");

    	$fragments = explode('-', $date);
    	$year = intval($fragments[0]);
    	$month = intval($fragments[1]);
    	$day = intval($fragments[2]);

    	$numberOfTheDay = (($year) * ($month * $month) * ($day * $day * $day)) % TOTAL_SUTRAS;

    	return $this->getIdFromIndex($numberOfTheDay);
    }

    public function getIdFromIndex($index) {
    	
    	$bounds = [51, 55, 55, 34];

    	foreach ($bounds as $key => $value) {

    		if($index < $value)
    			return 'REF_C' . sprintf("%02d", $key + 1) . '_V' . sprintf("%02d", $index);

    		$index = $index - $value;
    	}

    	return 'REF_C01_V01';
    }
}

?>