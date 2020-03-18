<?php

class search extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
	    $this->view('search/index', []);
	}

	public function advanced() {
		
		$getData = $this->model->getGetData();

		// Remove all unwanted form variables

		$script = $this->model->identifyScript($getData['stringData']);
	
		$data['stringData'] = (isset($getData['dontTransliterateInput'])) ? $getData['stringData'] : $this->model->toDevanagari($getData['stringData']);

		// Check if any data is 'Get'ed and check if the value sent is not null.

		if(!$data['stringData']) { $this->redirect('search/index'); return;}
			
		$searchType = $getData['searchType'];

		if (($searchType == 'sutra') || ($searchType == 'bhashya')) $data['bhashya'] = $getData['searchIn'];
		if ($searchType == 'vyakhya') $data['vyakhya'] = $getData['searchIn'];

		$table = constant(strtoupper($searchType) . '_TABLE');

		$data = $this->model->preProcessGET($data);
		$query = $this->model->formGeneralQuery($data, $table);

		$result = $this->model->executeQuery($query);

		$result['snippets'] = $this->model->getSnippets($result, $data['stringData'], $searchType);

		if(!$result['snippets']) {$this->view('error/noResults', 'search/index/'); return;}

		$interData = [];

		foreach ($result['snippets'] as $row) {
			
			$row['text'] = $this->model->transliterate($row['text'], $script);

	        if($searchType == 'vyakhya') {

	        	$row['bhashya'] = $this->model->viewHelper->bhashyaDef{explode('_', $row['parent'])[0]};
	            $row['vyakhya'] = explode('_', $row['id'])[0];

	        	$row['address'] = $this->model->viewHelper->printVyakhyaAddress($row['vyakhya'], $row['bhashya'], $row['parent']);
	        	$row['page'] = intval(preg_replace('/.*_C(\d\d).*/', "$1", $row['parent']));
	        }
	        else {

				$row['bhashya'] = $this->model->viewHelper->bhashyaDef{explode('_', $row['id'])[0]};
	        	$row['address'] = $this->model->viewHelper->printAddress($row['address'], $row['bhashya']);
	        	$row['page'] = intval(preg_replace('/.*_C(\d\d).*/', "$1", $row['id']));
	        }

			$row['address'] = $this->model->transliterate($row['address'], $script);

			array_push($interData, $row);
		}

		$result['snippets'] = $interData;

		$result['word'] = $this->model->transliterate($data['stringData'], $script);
		$result['searchType'] = $searchType;
    	
    	if ($searchType == 'vyakhya') $result['headLine'] = $data['vyakhya'];
		if ($searchType == 'sutra') $result['headLine'] = 'yoga-sutra';
		if ($searchType == 'bhashya') $result['headLine'] = 'vyasa-bhashyam';

    	$result['headLine'] = $this->model->viewHelper->printSearchHeadLine($result['headLine']);
		$result['headLine'] = $this->model->transliterate($result['headLine'], $script);
		
		$result['script'] = $script;

		$this->view('search/searchResult', $result);
	}
}

?>