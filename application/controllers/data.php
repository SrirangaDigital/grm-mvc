<?php

class data extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->insert();
	}


	public function createJsonFromXML(){

		$path = PHY_XML_SRC_URL . "books.xml";

		$booksList = $this->model->getBooksListFromXML($path);

		foreach ($booksList as $book) {
			
			$jsonFileName = PHY_JSON_SRC_URL . $book['id'] . '/index.json';
			$jsonData = json_encode($book,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);

			if(!(file_put_contents($jsonFileName,$jsonData))){

				echo "Not able to create file: " . $jsonFileName . "\n";
			}
		}
	}

	public function insert(){


		$jsonFiles = $this->model->getFilesIteratively(PHY_JSON_SRC_URL, $pattern = '/index.json$/i');
		
		$db = $this->model->db->useDB();
		$collection = $this->model->db->createCollection($db, ARTEFACT_COLLECTION);
	
		foreach ($jsonFiles as $jsonFile) {

			$content = $this->model->getArtefactFromJsonPath($jsonFile);
			$result = $collection->insertOne($content);
		}

		// Insert fulltext
		$this->insertFulltext();
	
		echo "Database insertion is over";
	}

	public function insertFulltext() {

	
		$txtFiles = $this->model->getFilesIteratively(PHY_JSON_SRC_URL, $pattern = '/\/text\/\d+\.txt$/i');

		$db = $this->model->db->useDB();
		$collection = $this->model->db->createCollection($db, FULLTEXT_COLLECTION);

		foreach ($txtFiles as $txtFile) {

			$content['text'] = file_get_contents($txtFile);
			$content['text'] = $this->model->processFulltext($content['text']);
			
			$txtFile = str_replace(PHY_JSON_SRC_URL, '', $txtFile);
			preg_match('/^(.*)\/text\/(.*)\.txt/', $txtFile, $matches);

			$content['id'] = $matches[1];
			$content['page'] = $matches[2];

			$result = $collection->insertOne($content);
		}
	}
}

?>
