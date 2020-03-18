<?php require_once('application/views/jumbotrons/' . $data['vyakhya'] . '.php'); ?>

<div class="container-fluid maintext" data-vyakhya="<?=$viewHelper->getBhashyaDetails($data['vyakhya'])['name']?>">
    <div class="row">
        <!--Main Content -->
        <div class="col-md-10">
            <div class="vyakhyaMain">
        <!-- Scrollspy based address bar is disabled -->
			<!-- <div class="address hidden"></div> -->
<?php
    if(isset($data['vyakhya'])) { $vyakhya = $data['vyakhya']; unset($data['vyakhya']); }

    foreach ($data as $row) {

        echo '<div id="' . $row->id . '">';
        echo $viewHelper->processLinks($row->content);
        echo '</div>';
    }
?>
            </div>
        </div>
    <!--Nav Bar -->
        <nav class="col-md-2 bs-docs-sidebar">
            <ul id="sidebar" class="nav nav-stacked">
                <?=$viewHelper->printSecondaryNav($vyakhya)?>
            </ul>
        </nav>
    </div>
    