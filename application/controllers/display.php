<?php

class display extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->bhashya();
	}

	public function bhashya($query = [], $bhashya = DEFAULT_BHASHYA, $script = DEFAULT_SCRIPT) {

		$page = (isset($query['page'])) ? $query['page'] : 1;
		$data['content'] = $this->model->getBhashyaText($bhashya, $page, $script);

		if($data['content']) $data['bhashya'] = $bhashya;
		if($data['content']) $data['primaryNav'] = $this->model->getPrimaryNav($bhashya, $script);
		if($data['content']) $data['jumbotron'] = $this->model->getJumbotron($bhashya, $script);

		($data) ? $this->view('display/bhashya', $data) : $this->view('error/index');
	}

	public function moola($query = [], $bhashya = DEFAULT_BHASHYA, $script = DEFAULT_SCRIPT) {

		// Lazy loading is not required here and hence page = 0
		$page = (isset($query['page'])) ? $query['page'] : 0;
		$data['content'] = $this->model->getMoolaText($bhashya, $page, $script);

		if($data['content']) $data['bhashya'] = $bhashya;
		if($data['content']) $data['primaryNav'] = $this->model->getPrimaryNav($bhashya, $script);
		if($data['content']) $data['jumbotron'] = $this->model->getJumbotron($bhashya . '-moola', $script);

		($data) ? $this->view('display/moola', $data) : $this->view('error/index');
	}

	public function getBhashyaByPage($query = [], $bhashya = DEFAULT_BHASHYA, $script = DEFAULT_SCRIPT) {

		$page = (isset($query['page'])) ? $query['page'] : 1;
		$data = $this->model->getBhashyaText($bhashya, $page, $script);

		if ($data) {
			
			unset($data['bhashya']);
			echo $this->model->processLinks($data[0]->content);
		}
		else {

			echo '';
		}
	}

	public function vyakhya($query = [], $vyakhya = DEFAULT_VYAKHYA) {

		$data = $this->model->getVyakhyaText($vyakhya);

		($data) ? $this->view('display/vyakhya', $data) : $this->view('error/index');
	}

	public function bhashyaVyakhya($query = [], $bhashya = DEFAULT_BHASHYA, $script = DEFAULT_SCRIPT) {

		$page = (isset($query['page'])) ? $query['page'] : 1;
		$data['content'] = $this->model->getBhashyaText($bhashya, $page, $script);

		if($data['content']) $data['bhashya'] = $bhashya;
		if($data['content']) $data['primaryNav'] = $this->model->getPrimaryNav($bhashya, $script);

		if($data['content']) {

			$data['vyakhyaPara'] = (isset($query['vyakhya'])) ? $this->model->listParaWithVyakhya($bhashya, $query['vyakhya']) : '';
			$data['vyakhya'] = (isset($query['vyakhya'])) ? $query['vyakhya'] : '';
			$data['subVyakhya'] = (isset($query['subVyakhya'])) ? $query['subVyakhya'] : '';
			$data['corpusName'] = $this->model->getCorpusName($data['vyakhya'], $script);
			
			$fileName = $data['bhashya'] . '-' . $data['vyakhya'];
			if($data['subVyakhya']) $fileName .= '-' . $data['subVyakhya'];
			
			$data['jumbotron'] = $this->model->getJumbotron($fileName, $script);
	
			$this->view('display/bhashyaVyakhya', $data);
		}
		else{

			$this->view('error/index');
		}
	}

	public function getVyakhya($query = [], $vyakhya = '', $id = '', $script = DEFAULT_SCRIPT) {

		$vyakhsIds = explode(';', $vyakhya);
		$i = 1;
		foreach ($vyakhsIds as $vyakhya) {
			
			$data = $this->model->getVyakhyaByID($vyakhya, $id, $script);

			if($data){
				
				$name = $this->model->getVyakhyaName($vyakhya, $script);
				echo '<div class="' . $vyakhya . ' level-' . $i++ . '"><h5>' . $name . '</h5>' . $data . '</div>';
			}
		}
	}

	public function splitWindow($query = [], $bhashya = '', $vyakhya = '', $id = '') {

		$data = $this->model->getBhashyaByID($id);
		
		if($data) {
			
			$data['prateeka'] = $this->model->listPrateeka($vyakhya, $id);

			$data['prevNext'] = $this->model->getPrevNextVyakhya($bhashya, $vyakhya, $id);
			
			$data['vyakhya'] = $vyakhya;
			$data['vyakhyaPara'] = $this->model->getVyakhyaByID($vyakhya, $id);
			$data['id'] = $id;

			$this->view('display/splitWindow', $data);
		}
		else {

			$this->view('error/index');
		}
	}

	public function getSutraForTheDay() {

		$id = $this->model->getIdOftheDay();
		$data = $this->model->getBhashyaByID($id);
		$data['bhashyaPara'] = $this->model->transliterate($data['bhashyaPara'], 'iast');
		$data['address'] = $this->model->transliterate($data['address'][0], 'iast');
		$data['id'] = $id;
		$data['page'] = intval(preg_replace('/.*C(\d\d)_.*/', "$1", $id));

		echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}

?>