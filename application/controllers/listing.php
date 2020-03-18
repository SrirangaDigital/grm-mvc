<?php

class listing extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {

		$this->moola();
	}

	public function categories($query = []){

		$data = $this->model->getDistinctCategories();
		var_dump($data);
		exit(0);
	}

	public function moola($query = [], $bhashya = DEFAULT_BHASHYA) {

		$corpus = $this->viewHelper->getBhashyaDetails($bhashya)['corpus-type'];
		$moola = $this->model->listMoola($bhashya, $corpus);

		// Default sort is numeric.
		if (!(isset($query['sort']))) $query['sort'] = 'numeric';
		if ($query['sort'] == 'alphabetic') asort($moola);

		if ($moola) {

			$moola['vyakhya'] = (isset($query['vyakhya'])) ? $query['vyakhya'] : '';
			$moola['type'] = (isset($query['type'])) ? $query['type'] : '';
			
			$this->view('listing/moola', $moola);
		}
		else{

			$this->view('error/index');
		}
	}

	public function ullekha($query = [], $bhashya = DEFAULT_BHASHYA) {

		$ullekha = $this->model->listUllekha($bhashya);

		// Default sort is numeric.
		if (!(isset($query['sort']))) $query['sort'] = 'numeric';
		if ($query['sort'] == 'alphabetic') asort($ullekha);

		if ($ullekha) {

			$ullekha['vyakhya'] = (isset($query['vyakhya'])) ? $query['vyakhya'] : '';
			$this->view('listing/ullekha', $ullekha);
		}
		else {

			$this->view('error/index');
		}
	}
}

?>