<?php

class viewHelper extends View {

    public function __construct() {

    }

    public function processLinks($text) {

        $text = str_replace('#', '?id=', $text);
        $text = str_replace(" । ", "&nbsp;। ", $text);

        $text = preg_replace('/<aside>(.*?)<\/aside>/', '<sup><a tabindex="1" class="footNote" data-toggle="popover"><i class="fa fa-asterisk"></i></a><span class="aside">' . "$1" . '</span></sup>', $text);

        // $text = preg_replace('/<span class="qt"><a href="(.*?)">(.*?) (\(.*?\))<\/a><\/span>/', '<span class="qt"><a href="' . "$1" . '">' . "$2" . ' ' . "$3" . '</a></span>', $text);

        return $text;
    }

    public function removeSpan($text) {

        $text = str_replace('<span', "\n<span", $text);

        $text = preg_replace("/\n<span id=\".*?\">(.*)<\/span>/m", "$1", $text);
        $text = preg_replace("/\n<span id=\".*?\">(.*)<\/span>/m", "$1", $text);

        $text = str_replace("\n<span", "<span", $text);

        return $text;
    }

    public function highlightText($text = '', $word = '') {

        return preg_replace('/' . $word . '/u', '<span class="highlight">' . $word . '</span>', $text);
    }

    public function printSecondaryNav($bhashya) {

        $details = $this->getBhashyaDetails($bhashya);
        $secondaryNav = (isset($details['secondary-nav'])) ? $details['secondary-nav'] : [];

        $text = '<li class="special first"><a href="#" title="Back to top">ग्रन्थारम्भः</a></li>';

        foreach ($secondaryNav as $name => $link) {

            $text .= '<li class="special"><a target="_blank" href="' . BASE_URL . $link . '">' . $name . '</a></li>';
        }

        return $text;
    }

    public function getBhashyaDetails($bhashya) {

        $details = json_decode(file_get_contents(JSON_PRECAST_URL . 'corpus-details.json'), true);
        return $details{$bhashya};
    }

    public function getSanskritName($name) {

        $details = json_decode(file_get_contents(JSON_PRECAST_URL . 'sanskrit-names.json'), true);
        return $details{$name};
    }

    public function printAddress($address, $bhashya) {

        $print = '<span class="addr-span">' . $this->getBhashyadetails($bhashya)['name'] . '</span>';

        $verseNum = intval(preg_replace('/.*V([0-9]+).*/', "$1", $address['id']));
        $address['V'] = (isset($address['V'])) ? $this->convert2devanagari($address['V'] . ' ' . $verseNum) : '';
        $id = $address['id'];
        unset($address['id']);

        foreach ($address as $key => $value) {
            
            $print .= '<span class="addr-span">' . $value . '</span>';
        }

        // If result is from bhashya para, append it to the address
        if(preg_match('/.*_B[0-9]+$/', $id)) $print .= ' - ' . $this->getBhashyadetails($bhashya)['corpus-name'];

        return $print;
    }

    public function printVyakhyaAddress($vyakhya, $bhashya, $parent) {

        $verseNum = intval(preg_replace('/.*V([0-9]+).*/', "$1", $parent));
        $print = '<span class="addr-span">' . $this->getBhashyadetails($this->bhashyaDef{$vyakhya})['name'] . '</span>';
        $print .= '<span class="addr-span">' . $this->getBhashyadetails($bhashya)['name'] . '</span>';

        $primaryNav = file_get_contents(PHY_JSON_PRECAST_URL . 'primary-nav/' . $bhashya . '.php');
        $primaryNav = preg_replace("/\s/", ' ', $primaryNav);
        $padaID = preg_replace('/(.*)_V.*/', "$1", $parent);
        $pada = preg_replace('/.*href="#' . $padaID . '">(.*?)<\/a><\/li>.*/', "$1", $primaryNav);

        $print .= '<span class="addr-span">' . $pada . '</span>';
        $print .= '<span class="addr-span"> ' . $this->getBhashyadetails($bhashya)['verse-type'] . ' ' . $this->convert2devanagari($verseNum) . '</span>';
        // If result is from bhashya para, ppaend it to the address
        if(preg_match('/.*_B[0-9]+$/', $parent)) $print .= ' - भाष्यम्';

        return $print;
    }

    public function printSearchHeadLine($headLine) {

        $pieces = explode('|', $headLine);
        $headLine = [];

        foreach ($pieces as $piece) {
            
            array_push($headLine, $this->getBhashyadetails($piece)['name']);
        }

        return implode(' / ', $headLine);
    }

    public function convert2devanagari($text) {

        $text = str_replace(" 0", "", $text);
        $text = str_replace("0", "०", $text);
        $text = str_replace("1", "१", $text);
        $text = str_replace("2", "२", $text);
        $text = str_replace("3", "३", $text);
        $text = str_replace("4", "४", $text);
        $text = str_replace("5", "५", $text);
        $text = str_replace("6", "६", $text);
        $text = str_replace("7", "७", $text);
        $text = str_replace("8", "८", $text);
        $text = str_replace("9", "९", $text);

        $text = str_replace("sutra", "सूत्रम्", $text);
        $text = str_replace("gadya", "श्लोक", $text);
        $text = str_replace("shloka", "श्लोक", $text);
        $text = str_replace("mantra", "मन्त्र", $text);
        $text = str_replace("shanti_mantra", "शान्ति मन्त्र", $text);
        $text = str_replace("kaarika", "कारिका", $text);
        
        return $text;
    }
    
    public function translatetext($text, $script){
		
		require_once PHY_BASE_URL . 'application/core/Transliterators.php';
		$translator = new Transliterators();
		return $translator->transliterate($text,$script);
	}
}

?>
