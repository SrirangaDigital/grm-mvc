<script type="text/javascript">
$(document).ready(function() {

    // var hloc = window.location.href;
    // if(hloc.match('=alphabetic')){

    //     $("a#alphabetic").replaceWith($("a#alphabetic").html());
    // }
    // else {

    //     $("a#numeric").replaceWith($("a#numeric").html());
    // }

    $( '.ullekhaParent' ).on('click', function(){

        targetId = $(this).attr('id').replace('show-', 'list-');
        $('#' + targetId).slideToggle();
    });

});
</script>

<?php

$data['bhashya'] = (isset($data['bhashya'])) ? $data['bhashya'] : '';
$vyakhya = ($data['vyakhya']) ? '&vyakhya=' . $data['vyakhya'] : '';

?>
<div class="container-fluid maintext">
    <div class="row">
        <div class="col-md-10">
            <h2><?=$viewHelper->getBhashyaDetails($data['bhashya'])['name']?> - उल्लेखाः</h2>
            <p class="sort-key">&nbsp;</p>
            <!-- <p class="sort-key">
                <span><a id="numeric" href="<?=BASE_URL . 'listing/ullekha/' . $data['bhashya']?>" title="Sort by order of occurrence"><i class="fa fa-sort-numeric-asc"></i></a></span>
                <span><a id="alphabetic" href="<?=BASE_URL . 'listing/ullekha/' . $data['bhashya']?>?sort=alphabetic" title="Sort alphabetically"><i class="fa fa-long-arrow-down"></i>अ</a></span>
            </p> -->
            <ul class="list-holder">
<?php
    
    $bhashya = $data['bhashya'];
    unset($data['bhashya']);
    unset($data['vyakhya']);

    // ksort($data);

    foreach ($data as $ullekhaBhashya => $row) {

        echo '<li class="ullekhaParent" id="show-' . $ullekhaBhashya . '"><span>' . $viewHelper->getBhashyaDetails($ullekhaBhashya)['root'] . '</span>';

        echo '<ol class="numeric ullekhaList" id="list-' . $ullekhaBhashya . '">';
 

        // Here the array row containing text and href should be sorted by text       
        $sortedArray = [];

        foreach ($row as $key => $value) {

            // Same ullekhas should be sorted based on their occurrence or in other words as quote ids
            $idNum = explode('_', $value['id'])[1];
            $sortedArray[$key] = $value['text'] . '-' . sprintf("%05d", $idNum);
        }

        array_multisort($sortedArray, $row);


        foreach ($row as $anchor) {

            $anchor['href'] = str_replace('_id.html#', '?id=', $anchor['href']);

            // Ullekha text starting with '(' are not printed
            if(preg_match('/^\(/', $anchor['text'])) continue;

            if($vyakhya) {
    
                echo '<li class="ullekha"><a target="_blank" href="' . BASE_URL . 'display/bhashyaVyakhya/' . $bhashya . '?' . $vyakhya . '&page=' . $anchor['page'] . '&id=' . $anchor['id'] . '&hlUllekha=' . $anchor['text'] . '">' . $anchor['text'] . '</a></li>';
            }
            else {

                echo '<li class="ullekha"><a target="_blank" href="' . BASE_URL . 'display/bhashya/' . $bhashya . '?page=' . $anchor['page'] . '&id=' . $anchor['id'] . '&hlUllekha=' . $anchor['text'] . '">' . $anchor['text'] . '</a></li>';
            }
        }
        echo '</ol>';

        echo '</li>';
    }
?>
            </ul>
        </div>
    </div>
