<script type="text/javascript">
$(document).ready(function() {

    // Include vyakhya name for listing of moola and adhikarana
    $('#sidebar li a[href*="listing"]').each(function(){

        var href = $(this).attr('href') + '?vyakhya=' + $('.vyakhyaPara').attr('data-vyakhya');
        $(this).attr('href', href);
    });

    $( window ).on('scroll', bindLazyLoading);
    bindScriptSwitch();
});

</script>

<?=$data['jumbotron']?>
<?php $bhashyaVykahya = $viewHelper->getBhashyaDetails($data['bhashya'])['name'] . ' - ' . $viewHelper->getBhashyaDetails($viewHelper->bhashyaDef{$data['vyakhya']})['name']; ?>

<div class="container-fluid maintext" data-bhashyaID="<?=$data['bhashya']?>" data-bhashya="<?=$bhashyaVykahya?>" data-verseType="<?=$viewHelper->getBhashyaDetails($data['bhashya'])['verse-type']?>">
    <div class="hidden vyakhyaPara" data-vyakhya="<?=$data['vyakhya']?>" data-corpusName="<?=$data['corpusName']?>"><?=$data['vyakhyaPara']?></div>
    <div class="row justify-content-center">
        <!--Main Content -->
        <div class="col-md-9">
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
    