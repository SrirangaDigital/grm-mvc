<?php

class listingModel extends Model {

	public function __construct() {

		parent::__construct();
	}

	public functionn getDistinctCategories(){

		$db = $this->db->useDB();
		$collection = $this->db->selectCollection($db, ARTEFACT_COLLECTION);

		$iterator = $collection->distinct('common-id');
		var_dump($iterator);
	}

	public function listMoola($bhashya, $corpus) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;

		$table = constant(strtoupper($corpus) . '_TABLE');
		$sth = $dbh->prepare('SELECT * FROM ' . $table . ' WHERE ' . $corpus . ' = :corpus');
		$sth->bindParam(':corpus', $bhashya);
		$sth->execute();

		$string = '<div class="full">';
		while($result = $sth->fetch(PDO::FETCH_OBJ)) { $string .= $result->content; }
		$string .= '</div>';

		$xml = simplexml_load_string($string);
		$verses = $xml->xpath('//div[@class="verse"]');

		$verseList = [];
		foreach ($verses as $verse) {

			$id = (string) $verse->attributes()['id'];
			$type = (string) $verse->attributes()['type'];
			// Here we should exclude versetext with type gadya
			$versetext = $verse->xpath('child::div[contains(@class, "versetext") and not(contains(@type, "gadya"))]');
			$verseList{$type}[$id] = strip_tags(((string) $versetext[0]->asXML()));
		}

		$verseList = array_filter($verseList);
		
		if (!(empty($verseList))){

			$verseList['bhashya'] = $bhashya;
			$verseList['corpus'] = $corpus;
		}

		$dbh = null;
		return $verseList;
	}

	public function listAdhikarana($bhashya) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;
	
		$sth = $dbh->prepare('SELECT * FROM ' . BHASHYA_TABLE . ' WHERE bhashya = :bhashya');
		$sth->bindParam(':bhashya', $bhashya);
		$sth->execute();

		$string = '<div class="full">';
		while($result = $sth->fetch(PDO::FETCH_OBJ)) { $string .= $result->content; }
		$string .= '</div>';

		$xml = simplexml_load_string($string);
		$verses = $xml->xpath('//div[@class="verse"]');

		$adhikaranaList = [];
		foreach ($verses as $verse) {

			$id = (string) $verse->attributes()['data-adhikaranaID'];
			$adhikaranaList[$id] = (string) $verse->attributes()['data-adhikarana'];
		}
		
		$adhikaranaList = array_filter($adhikaranaList);

		if (!(empty($adhikaranaList))) $adhikaranaList['bhashya'] = $bhashya;

		$dbh = null;
		return $adhikaranaList;
	}

	public function listUllekha($bhashya) {

		$dbh = $this->db->connect(DB_NAME);
		if(is_null($dbh))return null;
	
		$sth = $dbh->prepare('SELECT * FROM ' . BHASHYA_TABLE . ' WHERE bhashya = :bhashya');
		$sth->bindParam(':bhashya', $bhashya);
		$sth->execute();

		$string = '<div class="full">';
		while($result = $sth->fetch(PDO::FETCH_OBJ)) { $string .= $result->content; }
		$string .= '</div>';

		$xml = simplexml_load_string($string);
		$ullekhas = $xml->xpath('//span[@class="qt"]//a');

		$ullekhaList = [];
		foreach ($ullekhas as $ullekha) {

			$quote = $ullekha->xpath('ancestor::span[@class="qt"]');
			$array['id'] = (string) $quote[0]->attributes()['id'];
		
			// If bhashya is not found, page is defaulted to 0
			$array['page'] = 0;
			$bhashyaDIV = $ullekha->xpath('ancestor::div[@class="bhashya"]');
			if($bhashyaDIV) {

				$bhashyaID = (string) $bhashyaDIV[0]->attributes()['id'];
		        $array['page'] = intval(preg_replace('/.*_C(\d\d).*/', "$1", $bhashyaID));
			}

			$array['href'] = (string) $ullekha->attributes()['href'];
			$array['text'] = strip_tags(((string) $ullekha->asXML()));
			$array['text'] = preg_replace('/^‘\s+/', '‘', $array['text']);

			$ullekhaBhashya = explode('_id', $array['href'])[0];

			$ullekhaList{$ullekhaBhashya}[] = $array;
		}

		$sortOrder = array("Isha", "Kena_pada", "Kena_vakya", "Kathaka", "Prashna", "Mundaka", "Mandukya", "Taitiriya", "Aitareya", "Chandogya", "Brha", "svt", "kst", "jbl", "Gita", "BS");

		foreach ($sortOrder as $value) {
			
			if (isset($ullekhaList{$value})) {

				$ulList{$value} = $ullekhaList{$value};
			}
		}

		$ullekhaList = array_filter($ulList);
		
		$ullekhaOthers = $xml->xpath('//span[@class="qt_o"]');

		foreach ($ullekhaOthers as $ullekha) {

			$array['page'] = 0;
			$bhashyaDIV = $ullekha->xpath('ancestor::div[@class="bhashya"]');
			if($bhashyaDIV) {

				$bhashyaID = (string) $bhashyaDIV[0]->attributes()['id'];
		        $array['page'] = intval(preg_replace('/.*_C(\d\d).*/', "$1", $bhashyaID));
			}

			$array['id'] = (string) $ullekha->attributes()['id'];
			$array['href'] = '';
			$array['text'] = strip_tags(((string) $ullekha->asXML()));
			$array['text'] = preg_replace('/^‘\s+/', '‘', $array['text']);
			
			$ullekhaList['others'][] = $array;
		}

		$ullekhaList = array_filter($ullekhaList);

		if (!(empty($ullekhaList))) $ullekhaList['bhashya'] = $bhashya;
		$dbh = null;
		return $ullekhaList;
	}
}

?>