<?php

class Transliterators {

	public function __construct() {

	}

 	public function transliterate ($data, $script) {

		switch ($script) {
			case 'iast':
				$data = $this->devanagari2iast($data);
				break;
			case 'kn':
				$data = $this->devanagari2kannada($data);
				break;
			case 'bn':
				$data = $this->devanagari2bengali($data);
				break;
			case 'ta':
				$data = $this->devanagari2tamil($data);
				break;
		}

 		return $data;
 	}

 	// IAST
 	public function devanagari2iast($text) {

 		$chars = preg_split('/(?<!^)(?!$)/u', $text);
 		$etext = '';
 
 		foreach($chars as $c) {

 			$etext .= $this->devanagari2iastCharacter($c);
 		}

 		$etext .= " ";
 		$etext = preg_replace("/a\.(a|ā|i|ī|u|ū|ṛ|ṝ|e|ai|o|au|āṅ)/", "$1$2", $etext);
 		$etext = preg_replace("/\.(ṁ|ḥ|ṅ|ñ|ṇ|n|m)/", "$1", $etext);
 		$etext = preg_replace("/a\.zzz/", "", $etext);
 		// $etext = preg_replace("/a([ [:punct:]])/", "$1", $etext);

 		$etext = preg_replace("/\s$/", "", $etext);

 		return $etext;
 	}

 	public function devanagari2iastCharacter($char) {

 		switch($char) {

 			case "अ" : return("a");
 			case "आ" : return("ā");
 			case "इ" : return("i");
 			case "ई" : return("ī");
 			case "उ" : return("u");
 			case "ऊ" : return("ū");
 			case "ऋ" : return("ṛ");
 			case "ॠ" : return("ṝ");
 			case "ए" : return("e");
 			case "ऐ" : return("ai");
 			case "ओ" : return("o");
 			case "औ" : return("au");
 			case "ऽ" : return("'");
 			case "क" : return("ka");
 			case "ख" : return("kha");
 			case "ग" : return("ga");
 			case "घ" : return("gha");
 			case "ङ" : return("ṅa");
 			case "च" : return("ca");
 			case "छ" : return("cha");
 			case "ज" : return("ja");
 			case "झ" : return("jha");
 			case "ञ" : return("ña");
 			case "ट" : return("ṭa");
 			case "ठ" : return("ṭha");
 			case "ड" : return("ḍa");
 			case "ढ" : return("ḍha");
 			case "ण" : return("ṇa");
 			case "त" : return("ta");
 			case "थ" : return("tha");
 			case "द" : return("da");
 			case "ध" : return("dha");
 			case "न" : return("na");
 			case "प" : return("pa");
 			case "फ" : return("pha");
 			case "ब" : return("ba");
 			case "भ" : return("bha");
 			case "म" : return("ma");
 			case "य" : return("ya");
 			case "र" : return("ra");
 			case "ल" : return("la");
 			case "व" : return("va");
 			case "श" : return("śa");
 			case "ष" : return("ṣa");
 			case "स" : return("sa");
 			case "ह" : return("ha");
 			case "ा" : return(".ā");
 			case "ि" : return(".i");
 			case "ी" : return(".ī");
 			case "ु" : return(".u");
 			case "ू" : return(".ū");
 			case "ृ" : return(".ṛ");
 			case "ॄ" : return(".ṝ");
 			case "े" : return(".e");
 			case "ै" : return(".ai");
 			case "ो" : return(".o");
 			case "ौ" : return(".au");
 			case "ँ" : return(".ṅ");
 			case "ॉ" : return(".āṅ");
 			case "ं" : return(".ṁ");
 			case "ः" : return(".ḥ");
 			case "्" : return(".zzz");
 			case "क़" : return("qa");
 			case "ख़" : return("ḳha");
 			case "ग़" : return("g͟ha");
 			case "ड़" : return("ṛa");
 			case "ढ़" : return("ṛha");
 			case "फ़" : return("fa");
 			case "ज़" : return("za");
 			case "०" : return("0");
 			case "१" : return("1");
 			case "२" : return("2");
 			case "३" : return("3");
 			case "४" : return("4");
 			case "५" : return("5");
 			case "६" : return("6");
 			case "७" : return("7");
 			case "८" : return("8");
 			case "९" : return("9");
 			
 			default : return($char);
 		}
 	}

 	// Kannada
 	public function devanagari2kannada($text) {

 		$chars = preg_split('/(?<!^)(?!$)/u', $text);
 		$etext = '';
 
 		foreach($chars as $c) {

 			$etext .= $this->devanagari2kannadaCharacter($c);
 		}

 		return $etext;
 	}

 	public function devanagari2kannadaCharacter($char) {

 		switch($char) {

 			case "अ" : return("ಅ");
 			case "आ" : return("ಆ");
 			case "इ" : return("ಇ");
 			case "ई" : return("ಈ");
 			case "उ" : return("ಉ");
 			case "ऊ" : return("ಊ");
 			case "ऋ" : return("ಋ");
 			case "ॠ" : return("ೠ");
 			case "ऌ" : return("ಌ");
 			case "ॡ" : return("ೡ");
 			case "ए" : return("ಏ");
 			case "ऐ" : return("ಐ");
 			case "ओ" : return("ಓ");
 			case "औ" : return("ಔ");
 			case "ऽ" : return("ಽ");
 			case "क" : return("ಕ");
 			case "ख" : return("ಖ");
 			case "ग" : return("ಗ");
 			case "घ" : return("ಘ");
 			case "ङ" : return("ಙ");
 			case "च" : return("ಚ");
 			case "छ" : return("ಛ");
 			case "ज" : return("ಜ");
 			case "झ" : return("ಝ");
 			case "ञ" : return("ಞ");
 			case "ट" : return("ಟ");
 			case "ठ" : return("ಠ");
 			case "ड" : return("ಡ");
 			case "ढ" : return("ಢ");
 			case "ण" : return("ಣ");
 			case "त" : return("ತ");
 			case "थ" : return("ಥ");
 			case "द" : return("ದ");
 			case "ध" : return("ಧ");
 			case "न" : return("ನ");
 			case "प" : return("ಪ");
 			case "फ" : return("ಫ");
 			case "ब" : return("ಬ");
 			case "भ" : return("ಭ");
 			case "म" : return("ಮ");
 			case "य" : return("ಯ");
 			case "र" : return("ರ");
 			case "ल" : return("ಲ");
 			case "व" : return("ವ");
 			case "श" : return("ಶ");
 			case "ष" : return("ಷ");
 			case "स" : return("ಸ");
 			case "ह" : return("ಹ");
 			case "ा" : return("ಾ");
 			case "ि" : return("ಿ");
 			case "ी" : return("ೀ");
 			case "ु" : return("ು");
 			case "ू" : return("ೂ");
 			case "ृ" : return("ೃ");
 			case "ॄ" : return("ೄ");
 			case "ॢ" : return("ೢ");
 			case "ॣ" : return("ೣ");
 			case "े" : return("ೇ");
 			case "ै" : return("ೈ");
 			case "ो" : return("ೋ");
 			case "ौ" : return("ೌ");
 			case "ँ" : return("ಁ");
 			case "ॉ" : return("ಾಁ");
 			case "ं" : return("ಂ");
 			case "ः" : return("ಃ");
 			case "्" : return("್");
 			case "०" : return("೦");
 			case "१" : return("೧");
 			case "२" : return("೨");
 			case "३" : return("೩");
 			case "४" : return("೪");
 			case "५" : return("೫");
 			case "६" : return("೬");
 			case "७" : return("೭");
 			case "८" : return("೮");
 			case "९" : return("೯");
 			
 			default : return($char);
 		}
 	}
 	
 	// Bengali
 	public function devanagari2bengali($text) {

 		$chars = preg_split('/(?<!^)(?!$)/u', $text);
 		$etext = '';
 
 		foreach($chars as $c) {

 			$etext .= $this->devanagari2bengaliCharacter($c);
 		}

 		return $etext;
 	}

 	public function devanagari2bengaliCharacter($char) {

 		switch($char) {

 			case "अ" : return("অ");
 			case "आ" : return("আ");
 			case "इ" : return("ই");
 			case "ई" : return("ঈ");
 			case "उ" : return("উ");
 			case "ऊ" : return("ঊ");
 			case "ऋ" : return("ঋ");
 			case "ॠ" : return("ৠ");
 			case "ऌ" : return("ঌ");
 			case "ॡ" : return("ৡ");
 			case "ए" : return("এ");
 			case "ऐ" : return("ঐ");
 			case "ओ" : return("ও");
 			case "औ" : return("ঔ");
 			case "ऽ" : return("ঽ");
 			case "क" : return("ক");
 			case "ख" : return("খ");
 			case "ग" : return("গ");
 			case "घ" : return("ঘ");
 			case "ङ" : return("ঙ");
 			case "च" : return("চ");
 			case "छ" : return("ছ");
 			case "ज" : return("জ");
 			case "झ" : return("ঝ");
 			case "ञ" : return("ঞ");
 			case "ट" : return("ট");
 			case "ठ" : return("ঠ");
 			case "ड" : return("ড");
 			case "ढ" : return("ঢ");
 			case "ण" : return("ণ");
 			case "त" : return("ত");
 			case "थ" : return("থ");
 			case "द" : return("দ");
 			case "ध" : return("ধ");
 			case "न" : return("ন");
 			case "प" : return("প");
 			case "फ" : return("ফ");
 			case "ब" : return("ব");
 			case "भ" : return("ভ");
 			case "म" : return("ম");
 			case "य" : return("য");
 			case "र" : return("র");
 			case "ल" : return("ল");
 			case "व" : return("ব");
 			case "श" : return("শ");
 			case "ष" : return("ষ");
 			case "स" : return("স");
 			case "ह" : return("হ");
 			case "ा" : return("া");
 			case "ि" : return("ি");
 			case "ी" : return("ী");
 			case "ु" : return("ূ");
 			case "ू" : return("ৃ");
 			case "ृ" : return("ৄ");
 			case "ॢ" : return("ৢ");
 			case "ॣ" : return("ৣ");
 			case "े" : return("ে");
 			case "ै" : return("ৈ");
 			case "ो" : return("ো");
 			case "ौ" : return("ৌ");
 			case "ँ" : return("ঁ");
 			case "ं" : return("ং");
 			case "ः" : return("ঃ");
 			case "्" : return("্");
 			case "०" : return("০");
 			case "१" : return("১");
 			case "२" : return("২");
 			case "३" : return("৩");
 			case "४" : return("৪");
 			case "५" : return("৫");
 			case "६" : return("৬");
 			case "७" : return("৭");
 			case "८" : return("৮");
 			case "९" : return("৯");
 			
 			default : return($char);
 		}
 	}

 	// Tamil
 	public function devanagari2tamil($text) {
		
		// Preprocessing
		$text = str_replace('ऋ', 'रु', $text);
		$text = str_replace('ॠ', 'रू', $text);
		$text = str_replace('ृ', '्रु', $text);
		$text = str_replace('ॄ', '्रू', $text);
		$text = str_replace('ऌ', 'लु', $text);
		$text = str_replace('ॡ', 'लू', $text);
		$text = str_replace('ॢ', '्लु', $text);
		$text = str_replace('ॣ', '्लू', $text);
		$text = str_replace('ँ', 'ं', $text);
		
 		$chars = preg_split('/(?<!^)(?!$)/u', $text);
 		$etext = '';
 	
 		foreach($chars as $c) {

 			$etext .= $this->devanagari2tamilCharacter($c);
 		}

 		return $etext;
 	}

 	public function devanagari2tamilCharacter($char) {

 		switch($char) {

 			case "अ" : return("அ");
 			case "आ" : return("ஆ");
 			case "इ" : return("இ");
 			case "ई" : return("ஈ");
 			case "उ" : return("உ");
 			case "ऊ" : return("ஊ");
 			case "ए" : return("ஏ");
 			case "ऐ" : return("ஐ");
 			case "ओ" : return("ஓ");
 			case "औ" : return("ஔ");
 			case "ऽ" : return("(அ)"); //special case
 			case "क" : return("க");
 			case "ख" : return("க");
 			case "ग" : return("க");
 			case "घ" : return("க");
 			case "ङ" : return("ங");
 			case "च" : return("ச");
 			case "छ" : return("ச");
 			case "ज" : return("ஜ");
 			case "झ" : return("ஜ");
 			case "ञ" : return("ஞ");
 			case "ट" : return("ட");
 			case "ठ" : return("ட");
 			case "ड" : return("ட");
 			case "ढ" : return("ட");
 			case "ण" : return("ண");
 			case "त" : return("த");
 			case "थ" : return("த");
 			case "द" : return("த");
 			case "ध" : return("த");
 			case "न" : return("ந");
 			case "प" : return("ப");
 			case "फ" : return("ப");
 			case "ब" : return("ப");
 			case "भ" : return("ப");
 			case "म" : return("ம");
 			case "य" : return("ய");
 			case "र" : return("ர");
 			case "ल" : return("ல");
 			case "व" : return("வ");
 			case "श" : return("ஶ");
 			case "ष" : return("ஷ");
 			case "स" : return("ஸ");
 			case "ह" : return("ஹ");
 			case "ा" : return("ா");
 			case "ि" : return("ி");
 			case "ी" : return("ீ");
 			case "ु" : return("ு");
 			case "ू" : return("ூ");
 			case "े" : return("ே");
 			case "ै" : return("ை");
 			case "ो" : return("ோ");
 			case "ौ" : return("ௌ");
 			case "ं" : return("ம்");
 			case "ः" : return(":");
 			case "्" : return("்");
 			case "०" : return("0");
 			case "१" : return("1");
 			case "२" : return("2");
 			case "३" : return("3");
 			case "४" : return("4");
 			case "५" : return("5");
 			case "६" : return("6");
 			case "७" : return("7");
 			case "८" : return("8");
 			case "९" : return("9");
 			case "ॐ" : return("ௐ");
 			
 			default : return($char);
 		}
 	}
}

?>
