<div class="jumbotron jumbotron-fluid paper-background">
    <div class="container">
        <div class="row jumbo-section-single">
            <div class="col-md-7">
                <p><?=$viewHelper->getBhashyaDetails($data['vyakhya'])['author']?></p>
                <h1><?=$viewHelper->getBhashyaDetails($data['vyakhya'])['name']?></h1>
                <form class="form-inline jumbotron-form" role="search" action="<?=BASE_URL?>search/vyakhya/" method="get">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="stringData" id="stringData" aria-describedby="search" placeholder="अन्वेषणम्">
                            <input type="hidden" name="vyakhya" id="vyakhya" value="<?=$data['vyakhya']?>">
                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                <ul class="list-inline dependent-texts">
					<li><a target="_blank" href="<?=BASE_URL?>display/bhashyaVyakhya/vyasa-bhashyam?vyakhya=PRV"><i class="fa fa-link"></i> पातञ्जलयोगसूत्रभाष्यम्</a></li>
                </ul>
                <p>change script to <a class="scriptSwitch" href="" data-lang-code="iast"></a> <a class="scriptSwitch" href="" data-lang-code="devanagari"></a> <a class="scriptSwitch" href="" data-lang-code="kn"> <a class="scriptSwitch" href="" data-lang-code="bn"> <a class="scriptSwitch" href="" data-lang-code="ta"></a></p>
            </div>
        </div>
    </div>
</div>
