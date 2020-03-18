<?php

class searchModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public function formQuery($data, $orderBy = '') {

		$textArray = array();
		$textQuery = '';
	
		$filter = '';
		$words = array();

		if(isset($data['fulltext'])) {

			$filter = 'id IN (SELECT id FROM fulltextsearch WHERE MATCH (text) AGAINST (? IN BOOLEAN MODE))';
			array_push($words, $data['fulltext']);
			unset($data['fulltext']);
		}

		$data = $this->regexFilter($data);

		if($filter != '') array_unshift($data['filter'], $filter);
		$data['words'] = array_merge($words, $data['words']);


		$sqlFilter = (count($data['filter'] > 1)) ? implode(' and ', $data['filter']) : array_values($data['filter']);
		$sqlStatement = 'SELECT * FROM ' . METADATA_TABLE . ' WHERE ' . $textQuery . $sqlFilter . $orderBy;

		$data['query'] = $sqlStatement;
		$data['words'] = array_merge($textArray, $data['words']);

		return $data;
	}

	public function executeQuery($data) {

		$dbh = $this->db->connect(DB_NAME);

		$sth = $dbh->prepare($data['query']);
		$sth->execute($data['words']);

		$data = null;
		$i = 0;
		while($result = $sth->fetch(PDO::FETCH_OBJ))
		{
			$data[$i] = $result;
	        $i++;
		}
		$dbh = null;

		return $data;
	}

	public function regexFilter($var) {

		$data['filter'] = array();
		$data['words'] = array();

		if (empty($var)) return $data;

		while (list($key, $val) = each($var)) {

			$filterArr = array();

			$val = html_entity_decode($val, ENT_QUOTES);

			// Only paranthesis and hyphen will be quoted to include them in search
		    $val = preg_replace('/(\(|\)|\-)/', "\\\\$1", $val);
		    $words = preg_split('/ /', $val);
		    $words = array_filter($words, 'strlen');
		    
			$data['words'] = array_merge($data['words'], $words);

		    foreach($words as $word) {
		    	$filterArr[] = $key . ' REGEXP ?';
		    }

		    $filter[$key] = implode(' ' . SEARCH_OPERAND . ' ', $filterArr);
		}

		$data['filter'] = $filter;

		return $data;
	}

	public function formGeneralQuery($data, $table, $orderBy = '') {

		$data = $this->regexFilter($data);

		$sqlFilter = (count($data['filter']) > 1) ? implode(' and ', $data['filter']) : array_shift($data['filter']);
		$sqlStatement = 'SELECT * FROM ' . $table . ' WHERE ' . $sqlFilter . $orderBy;

		$data['query'] = $sqlStatement;

		return $data;
	}

	public function getSnippets($data = array(), $word = '', $in = 'bhashya') {

		if (!($data)) return null;
		$snippets = [];

		if($in == 'vyakhya') {

	        $snippets = $this->getVyakhyaSnippets($data, $word);
	        return $snippets;
		}
		else{

			foreach ($data as $row) {

				$xml = simplexml_load_string($row->content);

				switch ($in) {
				    case 'bhashya':
				        $snippets = array_merge($snippets, $this->getBhashyaSnippets($xml, $word));
				        break;
				    case 'sutra':
				        $snippets = array_merge($snippets, $this->getVerseSnippets($xml, $word));
				        break;
				}
		        // $snippets = array_merge($snippets, $this->getVerseSnippets($xml, $word), $this->getBhashyaSnippets($xml, $word));
			}
			return array_values(array_filter($snippets));
		}
	}

	private function getBhashyaSnippets($xml, $word, $in = 'bhashya') {

		$results = $xml->xpath("//div[contains(@class, '" . $in . "')][contains(., '" . $word . "')]");
		
		$snippets = array();
		foreach ($results as $find) {
			
			$id = (string) $find->attributes()['id'];
			$snipArray['id'] = $id;

			$extractXML = $xml->xpath('//div[@id="' . $id . '"]');
			$extract = strip_tags(((string) $extractXML[0]->asXML()));

			// If input is a phrase, consider only the first word to determine snippet boundary
			$word = preg_replace('/(.*?) .*/', "$1", $word);
		
			$words = explode(" ", $extract);
			$matches = preg_grep('/.*' . $word . '.*/', $words);
			$matchesKey = array_keys($matches);

			$left = $matchesKey[0] - 10;
			$left = ($left < 0) ? 0 : $left;

			$right = end($matchesKey) + 10;
			$right = ($right > sizeof($words)) ? sizeof($words) : $right;

			$snipArray['text'] = implode(' ', array_slice($words, $left, $right-$left));
			$snipArray['address'] = $this->getAddress($find);
			$snipArray['address']['id'] = $id;
			
			$snippets[] = $snipArray;
		}

		return $snippets;
	}

	private function getAddress($find) {

		$address = [];
		$verse = $find->xpath('ancestor::div[@class="verse"]');
		$section = $find->xpath('ancestor::div[@class="section"]');
		$chapter = $find->xpath('ancestor::div[@class="chapter"]');

		if(isset($chapter[0])) $address['C'] = (string) $chapter[0]->attributes()['data-name'];
		if(isset($section[0])) $address['S'] = (string) $section[0]->attributes()['data-name'];
		if(isset($verse[0])) {
		
			$address['V'] = (string) $verse[0]->attributes()['type'];
		}

		return $address;
	}

	private function getVerseSnippets($xml, $word, $in = 'versetext') {

		$results = $xml->xpath("//div[@class = '" . $in . "'][contains(., '" . $word . "')]");

		$snippets = array();
		foreach ($results as $find) {
			
			$ancestor = $find->xpath('ancestor::div[@class="verse"]');
			
			$id = (string) $ancestor[0]->attributes()['id'];
			$snipArray['id'] = $id;

			$extract = $xml->xpath('//div[@id="' . $id . '"]');
			$versetext = $extract[0]->xpath('child::div[@class="versetext"]');
			// Here end is used to get the last element because of the presence of type="gadya" kind of versetext
			$versetext = end($versetext);

			$snipArray['text'] = strip_tags(((string) $versetext->asXML()));
			$snipArray['address'] = $this->getAddress($find);
			$snipArray['address']['id'] = $id;
			
			$snippets[] = $snipArray;
		}

		return $snippets;
	}

	public function getVyakhyaSnippets($data, $word) {

		if (!($data)) return null;

		$snippets = array();
		foreach ($data as $row) {
			
			$snipArray['id'] = (string) $row->id;
			$snipArray['parent'] = (string) $row->parent;
			$extract = strip_tags((string) $row->content);

			$words = explode(" ", $extract);

			// Here if two words are input, only first word is considered to extarct the snippet
			$word = explode(" ", $word)[0];
			
			$matches = preg_grep('/.*' . $word . '.*/', $words);
			
			if($matches) {

				$matchesKey = array_keys($matches);

				$left = $matchesKey[0] - 10;
				$left = ($left < 0) ? 0 : $left;
				$right = end($matchesKey) + 10;
				$right = ($right > sizeof($words)) ? sizeof($words) : $right;

				$snipArray['text'] = implode(' ', array_slice($words, $left, $right-$left));
				$snipArray['address']['id'] = $snipArray['id'];
			}
			else{

				$snipArray['text'] = '';
				$snipArray['address'] = [];
			}
			$snippets[] = $snipArray;
		}

		return $snippets;
	}
}

?>