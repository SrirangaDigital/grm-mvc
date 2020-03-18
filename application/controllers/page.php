<?php

class page extends Controller {

	public function __construct() {
		
		parent::__construct();
	}

	public function index() {
		
		$this->view('flat/Home/index');
	}

	public function flat() {

		$args = func_get_args();

		// Last item in the array contains url filter elements. This need to be removed as currently for page/flat, they are not used
		$args = array_filter($args);

		$path = 'flat/' . implode('/', $args);
		$this->view($path);
	}
}

?>