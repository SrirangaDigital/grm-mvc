<script type="text/javascript">
// Lazy loading code
$(document).ready( bindScriptSwitch );
</script>

<?=$data['jumbotron']?>
<div class="container-fluid maintext" data-bhashyaID="<?=$data['bhashya']?>" data-bhashya="<?=$viewHelper->getBhashyaDetails($data['bhashya'])['name']?>" data-verseType="<?=$viewHelper->getBhashyaDetails($data['bhashya'])['verse-type']?>">
    <div class="row justify-content-center">
        <!--Main Content -->
        <div class="col-md-9 moolaVerse">
			<!-- <div class="address hidden"></div> -->
<?php
    foreach ($data['content'] as $row) {

        echo $viewHelper->processLinks($row->content);
    }
?>
        </div>
    <!--Nav Bar -->
        <nav id="sidebar" class="col-md-1 bs-docs-sidebar">
            <ul id="sidebarUl" class="nav nav-stacked hidden">
                <li class="nav-item"><span class="motif">&nbsp;</span></li>
                <?=$data['primaryNav']?>
            </ul>
        </nav>
    </div>
    