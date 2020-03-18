<script type="text/javascript">
$(document).ready(function() {

    var hloc = window.location.href;
    if(hloc.match('=alphabetic')){

        $("a#alphabetic").replaceWith($("a#alphabetic").html());
    }
    else {

        $("a#numeric").replaceWith($("a#numeric").html());
    }

});
</script>

<?php

$bhashya = '';
$corpus = '';

$bhashya =  (isset($data['bhashya'])) ? $data['bhashya'] : $bhashya;
$corpus =  (isset($data['corpus'])) ? $data['corpus'] : $corpus;
$vyakhya =  ($data['vyakhya']) ? '&vyakhya=' . $data['vyakhya'] : '';

unset($data['bhashya']);
unset($data['corpus']);
unset($data['vyakhya']);

$type = $data['type'];unset($data['type']);

if(!($type)) {

    $type = current(array_keys($data));
    $data = reset($data);
}
else{

    $data = $data{$type};
}

?>
<div class="container-fluid maintext">
    <div class="row">
        <div class="col-md-10">
            <h2><?=$viewHelper->getBhashyaDetails($bhashya)['root']?> - <?=$viewHelper->getSanskritName($type)['plural']?></h2>
            <!-- <p class="sort-key">
                <span><a id="numeric" href="<?=BASE_URL . 'listing/moola/' . $bhashya . '?' . $vyakhya?>" title="Sort by order of occurrence"><i class="fa fa-sort-numeric-asc"></i></a></span>
                <span><a id="alphabetic" href="<?=BASE_URL . 'listing/moola/' . $bhashya . '?' . $vyakhya?>&amp;sort=alphabetic" title="Sort alphabetically"><i class="fa fa-long-arrow-down"></i>अ</a></span>
            </p> -->
            <ol class="numeric list-holder">
<?php
    
    $num = 1;

    foreach ($data as $id => $text) {

        $page = intval(preg_replace('/.*_C(\d\d).*/', "$1", $id));

        if($vyakhya) {
         
            echo '<li id="item-' . $num . '" class="moola"><a target="_blank" href="' . BASE_URL . 'display/bhashyaVyakhya/' . $bhashya . $vyakhya . '?page=' . $page . '&id=' . $id . '">' . $text . '</a></li>';
        }
        else{

            echo '<li id="item-' . $num . '" class="moola"><a target="_blank" href="' . BASE_URL . 'display/' . $corpus . '/' . $bhashya . '?page=' . $page . '&id=' . $id . '">' . $text . '</a></li>';
        }

        $num++;
    }
?>
            </ol>
        </div>
    </div>
