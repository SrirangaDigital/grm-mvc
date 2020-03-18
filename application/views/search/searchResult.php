<div class="jumbotron jumbotron-fluid mb-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <img src="<?=PUBLIC_URL?>images/floral-decor-top.png" alt="floral-decor" class="vertical-floral-delimiter top">
                <h4><?=$data['headLine']?></h4>
                <h1>Search results</h1>
                <ul class="list-inline auxiliary-links mb-0" />
            </div>
        </div>
    </div>
</div>

<div class="container maintext">
    <div class="row">
        <div class="col-md-12">
<?php

    $count = sizeof($data['snippets']);
    echo '<h4 class="searchCount">' . $count;
    echo ($count > 1) ? ' results' : ' result';
    echo '</h4>';

    foreach ($data['snippets'] as $row) {

        echo '<div class="search-result result-card">';
        echo '<h4>' . $row['address'] . '</h4>';
        echo '<p>………' . $viewHelper->highlightText($row['text'], $data['word']) . '………</p>';

        if($data['searchType'] == 'vyakhya') {

            $combiVyakhya = 'vyakhya=' . $row['vyakhya'];

            // Bad method, used for the time being
            if($row['vyakhya'] == 'PRV') $combiVyakhya = 'vyakhya=TVV&subVyakhya=' . $row['vyakhya'];

            echo '<p class="more"><a target="_blank" href="' . BASE_URL . 'display/bhashyaVyakhya/' . $row['bhashya'] . '/' . $data['script'] . '?' . $combiVyakhya . '&page=' . $row['page'] . '&id=' . $row['parent'] . '&hlBhashya=' . $data['word'] . '">read on...</a></p>';
        }
        elseif ($data['searchType'] == 'sutra') {

            echo '<p class="more"><a target="_blank" href="' . BASE_URL . 'display/moola/' . $row['bhashya'] . '/' . $data['script'] . '?page=' . $row['page'] . '&id=' . $row['id'] . '&hlBhashya=' . $data['word'] . '">read on...</a></p>';
        }
        else {

            echo '<p class="more"><a target="_blank" href="' . BASE_URL . 'display/bhashya/' . $row['bhashya'] . '/' . $data['script'] . '?page=' . $row['page'] . '&id=' . $row['id'] . '&hlBhashya=' . $data['word'] . '">read on...</a></p>';
        }
        echo '</div>';
    }
?>
        </div>
    </div>
