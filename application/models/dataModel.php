<?php

class dataModel extends Model {

	protected $ullekhaCount = 1;

	public function __construct() {

		parent::__construct();
	}


	public function getFilesIteratively($dir, $pattern = '/*/'){

		$files = [];
	    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(rtrim($dir, "/")));
		$regex = new RegexIterator($iterator, $pattern, RecursiveRegexIterator::GET_MATCH);

	    foreach($regex as $file => $object) {
	        
			array_push($files, $file);
	    }

	    sort($files);
	    return ($files);
	}

	public function getBhashyaData($bhashya = DEFAULT_BHASHYA) {

		if (file_exists(PHY_XML_SRC_URL . $bhashya . '/' . $bhashya . '.xml')) { $fileName = XML_SRC_URL . $bhashya . '/' . $bhashya . '.xml'; }
		else { return False; }

		$xml = simplexml_load_file($fileName);

		$content = array();

		$this->ullekhaCount = 1;

		foreach ($xml->div->div->div as $chapter) {
			
			if ($chapter['class'] == 'authorline') continue; 

			$data = array();
			$data['id'] = (string) $chapter['id'];
			$data['bhashya'] = $bhashya;
			$data['type'] = (string) $chapter['type'];
			$data['content'] = $this->processContent($chapter->asXML());
			$data['content'] = $this->insertUllekhaCount($data['content']);
			$data['stringData'] = $this->processContent(strip_tags(((string) $chapter->asXML())));
			
			array_push($content, $data);
		}

		return $content;
	}

	public function getVyakhyaData($vyakhya) {

		if (file_exists(PHY_XML_SRC_URL . $vyakhya . '/' . $vyakhya . '.xml')) {
			$fileName = XML_SRC_URL . $vyakhya . '/' . $vyakhya . '.xml';
		}
		else {
			exit("Failed to open $vyakhya");
		}

		$xml = simplexml_load_file($fileName);
		$vyakhyaCode = $this->viewHelper->getBhashyaDetails($vyakhya)['prefix'];

		$vyakhyaDescriptors = $xml->xpath('//div[@class="VyakhyaDescriptor"]');

		$list = [];
		$index = 1;
		foreach ($vyakhyaDescriptors as $vyakhyaDescriptor) {

			$data['parent'] = (string) $vyakhyaDescriptor->attributes()['data-parent'];
			$data['anchor'] = (string) $vyakhyaDescriptor->attributes()['data-anchor'];
			$data['vdid'] = sprintf("%05d", $index);
			$data['id'] = $vyakhyaCode  . '_' . $data['parent'] . '_VD' . $data['vdid'];
			$data['vyakhya'] = $vyakhya;
			$data['content'] = $this->processContent($vyakhyaDescriptor->asXML());
			$data['stringData'] = $this->processContent(strip_tags(((string) $vyakhyaDescriptor->asXML())));

			$list[] = $data;
			$index++;
		}
		return $list;
	}

	public function processContent($content = '') {

		// $content = str_replace('_id.html#', '?id=', $content);
		
		
		return $content;
	}

	public function insertUllekhaCount($content = '') {

		$content = preg_replace_callback('/<span class="qt/', array($this, 'ulCountCallback'), $content);

		return $content;
	}
	
	public function ulCountCallback($matches) {
	
		return '<span id="quote_' . $this->ullekhaCount++ . '" class="qt';
	}

	public function getBhashyaDataSecondary($bhashya = DEFAULT_BHASHYA) {

		if (file_exists(PHY_XML_SRC_URL . $bhashya . '/' . $bhashya . '.xml')) { $fileName = XML_SRC_URL . $bhashya . '/' . $bhashya . '.xml'; }
		else { return False; }

		$xml = simplexml_load_file($fileName);

		$data = array();

		// Get vyakhyaDescriptors and grpup them into paragraphs
		foreach ($xml->div->div as $vyakhyaDescriptor) {
			
			if ($vyakhyaDescriptor['class'] == 'btitle') continue; 

			$native = (string) $vyakhyaDescriptor['data-native'];

			if(!(isset($data{$native}['content']))) $data{$native}['content'] = '';
			$data{$native}['content'] .= $this->processContent($vyakhyaDescriptor->asXML());
		}

		// Get paragraphs and group them into chapters
		$chapterData = array();
		foreach ($data as $native => $row) {
			
			$row['content'] = '<div class="bhashya secondaryBhashya" id="' . $native . '">' . $row['content'] . '</div>';
			// Get chapter id
			$chapter = preg_replace('/(.*C[0-9][0-9]).*/', "$1", $native);

			if(!(isset($chapterData{$chapter}['content']))) $chapterData{$chapter}['content'] = '';
			$chapterData{$chapter}['content'] .= $row['content'];
		}

		// Crude method to display chapter name, this will have t be moved to precast later.
	    $sanskritName = array("", "प्रथमं", "द्वितीयं", "तृतीयं", "तुरीयं", "पञ्चमं", "षष्ठं", "सप्तमं", "अष्टमं", "नवमं");

		// Assign chapter id an rest of the information
		$data = array();
		foreach ($chapterData as $chapter => $row) {
			
			$chapterName = $sanskritName[intval(preg_replace('/.*_C([0-9]+).*/', "$1", $chapter))] . ' वर्णकम्';
			$row['content'] = '<div class="chapter" id="' . $chapter . '" type="varnaka" data-name="' . $chapterName . '">' . $row['content'] .'</div>';
			$row['id'] = $chapter;
			$row['type'] = 'varnaka';
			$row['bhashya'] = $bhashya;

			array_push($data, $row);
		}

		return $data;
	}
}

?>
