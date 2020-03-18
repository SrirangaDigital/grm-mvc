<script type="text/javascript">
$(document).ready(function(){

    $('input[name="searchType"]').on('change, click', function(){

        $('.select-works').addClass('hidden'); 
        $('#select-works-' + $(this).val()).removeClass('hidden'); 
    
        $('.select-works.' + $(this).val() + ' input').trigger('change');
    });

    // Defaulted to sutra
    $('#radio-sutra').trigger('click');
    updateSelectSutra();
    $('.select-works.sutra input').on('change', updateSelectSutra);
    $('.select-works.bhashya input').on('change', updateSelectBhashya);
    $('.select-works.vyakhya input').on('change', updateSelectVyakhya);
});

</script>
<script type="text/javascript" src="<?=PUBLIC_URL?>js/devanagari_kbd.js"></script>

<div class="jumbotron jumbotron-fluid mb-0">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <img src="<?=PUBLIC_URL?>images/floral-decor-top.png" alt="floral-decor" class="vertical-floral-delimiter top">
                <h1>Search</h1>
                <ul class="list-inline auxiliary-links mb-0" />
            </div>
        </div>
    </div>
</div>
<div class="container maintext search">
    <form method="get" action="<?=BASE_URL . 'search/advanced'?>" class="form-horizontal">
        <div class="row justify-content-center align-items-start searchTypeRadio">
            <div class="list-inline-item col-md-3">
                <label><input name="searchType" id="radio-sutra" type="radio" value="sutra" aria-label="Radio button to select pricipal text" checked="checked"> Principal Yoga texts</label><br />
                <div class="select-works sutra" id="select-works-sutra">
                    <input id="VYB-verse" type="checkbox" value="vyasa-bhashyam" aria-label="Checkbox to select vyasa-bhashyam" CHECKED/> <label for="VYB-verse">Yogasūtras</label><br />
                    <input id="HYP-verse" type="checkbox" value="hathayoga-pradipika" aria-label="Checkbox to select hathayoga-pradipika" CHECKED/> <label for="HYP-verse">Haṭha-yoga-pradīpikā</label><br />
                    <input id="BHG-verse" type="checkbox" value="bhagavadgita" aria-label="Checkbox to select bhagavadgita" CHECKED/> <label for="BHG-verse">Bhagavadgīta</label>
                </div>
            </div>
            <div class="list-inline-item col-md-3">
                <label><input name="searchType" id="radio-bhashya" type="radio" value="bhashya" aria-label="Radio button to select bhashya"> Commentaries</label><br />
                <div class="hidden select-works bhashya" id="select-works-bhashya">
                    <input id="VYB" type="checkbox" value="vyasa-bhashyam" aria-label="Checkbox to select vyasa-bhashyam" CHECKED/> <label for="VYB">Vyāsa bhāṣyam</label><br />
                    <input id="YOV" type="checkbox" value="yogavalli-bhashyam" aria-label="Checkbox to select yogavalli-bhashyam" CHECKED/> <label for="YOV">Yogavallī</label><br />
                    <input id="YOC" type="checkbox" value="yoga-chandrika" aria-label="Checkbox to select yoga-chandrika" CHECKED/> <label for="YOC">Yogacandrikā</label><br />
                    <input id="YOS" type="checkbox" value="yoga-sudhakara" aria-label="Checkbox to select yoga-sudhakara" CHECKED/> <label for="YOS">Yogasudhākara</label><br />
                    <input id="PRA" type="checkbox" value="pradipika" aria-label="Checkbox to select pradipika" CHECKED/> <label for="PRA">Pradīpikā</label><br />
                    <input id="VRT" type="checkbox" value="vritti" aria-label="Checkbox to select vritti" CHECKED/> <label for="VRT">Vṛtti</label><br />
                    <input id="SUT" type="checkbox" value="sutrarthabodhini" aria-label="Checkbox to select sutrarthabodhini" CHECKED/> <label for="SUT">Sutrārthabodhinī</label><br />
                    <input id="RAJ" type="checkbox" value="rajamartanda" aria-label="Checkbox to select rajamartanda" CHECKED/> <label for="RAJ">Rājamārtāṇḍa</label><br />
                    <input id="HYP" type="checkbox" value="hathayoga-pradipika" aria-label="Checkbox to select hathayoga-pradipika" CHECKED/> <label for="HYP">Jyotsna</label>
                </div>
            </div>
            <div class="list-inline-item col-md-3">
                <label><input name="searchType" id="radio-vyakhya" type="radio" value="vyakhya" aria-label="Radio button to select vyakhya"> Sub commentaries</label><br />
                <div class="hidden select-works vyakhya" id="select-works-vyakhya">
                    <input id="TVV" type="checkbox" value="tattvavaisharadi-vyakhya" aria-label="Checkbox to select tattvavaisharadi" CHECKED/> <label for="TVV">Tattvavaiśāradī</label></label><br />
                    <input id="VIV" type="checkbox" value="vivaranam-vyakhya" aria-label="Checkbox to select vivaraṇam" CHECKED/> <label for="VIV">Vivaraṇam</label><br />
                    <input id="YVV" type="checkbox" value="yogavartikam-vyakhya" aria-label="Checkbox to select yogavārtikam" CHECKED/> <label for="YVV">Yogavārtikam</label><br />
                    <input id="BAV" type="checkbox" value="bhasvati-vyakhya" aria-label="Checkbox to select bhāsvati" CHECKED/> <label for="BAV">Bhāsvatī</label><br />
                    <input id="PRV" type="checkbox" value="patanjalarahasyam-vyakhya" aria-label="Checkbox to select pātañjala rahasyam" CHECKED/> <label for="PRV">Pātañjala Rahasyam</label><br />
                </div>
            </div>
            <input name="searchIn" id="searchIn" type="hidden" value=""/>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 mb-5">
                <input type="text" class="form-control" id="stringData" name="stringData" placeholder="Word in either Roman or Devanagari script" onfocus="SetId('stringData')">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 text-right">
                <div class="row submitButton justify-content-end">
                    <input name="submit" type="submit" id="submit" value="Search"/>
                    <input name="reset" type="reset" id="reset" value="Reset"/>
                </div>
            </div>
            <div class="col-md-6">
                <p><small>You may also use this soft keyboard to enter text</small></p>
                <div id="kbd">
                    <div class="keys tline lline" id="abar" style="clear: left;" onclick="InsertText('ā')">ā</div>
                    <div class="keys tline" id="ibar" onclick="InsertText('ī')">ī</div>
                    <div class="keys tline" id="ubar" onclick="InsertText('ū')">ū</div>
                    <div class="keys tline" id="rdot" onclick="InsertText('ṛ')">ṛ</div>
                    <div class="keys tline" id="rbardot" onclick="InsertText('ṝ')">ṝ</div>
                    <div class="keys tline" id="mdot" onclick="InsertText('ṁ')">ṁ</div>
                    <div class="keys tline" id="hdot" onclick="InsertText('ḥ')">ḥ</div>
                    <div class="keys tline" id="tdot" onclick="InsertText('ṭ')">ṭ</div>
                    <div class="keys tline" id="ddot" onclick="InsertText('ḍ')">ḍ</div>
                    <div class="keys lline" id="ntopdot" style="clear: left;" onclick="InsertText('ṅ')">ṅ</div>
                    <div class="keys" id="ntilde" onclick="InsertText('ñ')">ñ</div>
                    <div class="keys" id="ndot" onclick="InsertText('ṇ')">ṇ</div>
                    <div class="keys" id="sacute" onclick="InsertText('ś')">ś</div>
                    <div class="keys" id="sdot" onclick="InsertText('ṣ')">ṣ</div>
                    <div class="keys lline" id="a" style="clear: left;" onclick="InsertText('अ')">अ</div>
                    <div class="keys" id="A" onclick="InsertText('आ')">आ</div>
                    <div class="keys" id="i" onclick="InsertText('इ')">इ</div>
                    <div class="keys" id="I" onclick="InsertText('ई')">ई</div>
                    <div class="keys" id="u" onclick="InsertText('उ')">उ</div>
                    <div class="keys tline" id="U" onclick="InsertText('ऊ')">ऊ</div>
                    <div class="keys tline" id="Ru" onclick="InsertText('ऋ')">ऋ</div>
                    <div class="keys tline" id="RU" onclick="InsertText('ॠ')">ॠ</div>
                    <div class="keys tline" id="e" onclick="InsertText('ए')">ए</div>
                    <div class="keys tline" id="ai" onclick="InsertText('ऐ')">ऐ</div>
                    <div class="keys lline" id="o" style="clear:left;border-bottom: none;" onclick="InsertText('ओ')">ओ</div>
                    <div class="keys" id="au" style="border-bottom: none;" onclick="InsertText('औ')">औ</div>
                    <div class="keys" id="M" style="border-bottom: none;" onclick="InsertText('ं')">ं</div>
                    <div class="keys" id="H" style="border-bottom: none;" onclick="InsertText('ः')">ः</div>
                    <div class="keys lline tline" style="clear: left;" id="ka" onclick="InsertText('क')">क</div>
                    <div class="keys tline" id="Ka" onclick="InsertText('ख')">ख</div>
                    <div class="keys tline" id="ga" onclick="InsertText('ग')">ग</div>
                    <div class="keys tline" id="Ga" onclick="InsertText('घ')">घ</div>
                    <div class="keys tline" id="kna" onclick="InsertText('ङ')">ङ</div>
                    <div class="keys lline" style="clear: left;" id="ca" onclick="InsertText('च')">च</div>
                    <div class="keys" id="Ca" onclick="InsertText('छ')">छ</div>
                    <div class="keys" id="ja" onclick="InsertText('ज')">ज</div>
                    <div class="keys" id="Ja" onclick="InsertText('झ')">झ</div>
                    <div class="keys" id="cna" onclick="InsertText('ञ')">ञ</div>
                    <div class="keys lline" style="clear: left;" id="Ta" onclick="InsertText('ट')">ट</div>
                    <div class="keys" id="Tha" onclick="InsertText('ठ')">ठ</div>
                    <div class="keys" id="Da" onclick="InsertText('ड')">ड</div>
                    <div class="keys" id="Dha" onclick="InsertText('ढ')">ढ</div>
                    <div class="keys" id="Na" onclick="InsertText('ण')">ण</div>
                    <div class="keys lline" style="clear: left;" id="ta" onclick="InsertText('त')">त</div>
                    <div class="keys" id="tha" onclick="InsertText('थ')">थ</div>
                    <div class="keys" id="da" onclick="InsertText('द')">द</div>
                    <div class="keys" id="dha" onclick="InsertText('ध')">ध</div>
                    <div class="keys" id="na" onclick="InsertText('न')">न</div>
                    <div class="keys lline" style="clear: left;border-bottom:none;" id="pa" onclick="InsertText('प')">प</div>
                    <div class="keys" id="Pa" style="border-bottom: none;" onclick="InsertText('फ')">फ</div>
                    <div class="keys" id="ba" style="border-bottom: none;" onclick="InsertText('ब')">ब</div>
                    <div class="keys" id="Ba" style="border-bottom: none;" onclick="InsertText('भ')">भ</div>
                    <div class="keys" id="ma" style="border-bottom: none;" onclick="InsertText('म')">म</div>
                    <div class="keys lline tline" style="clear: left;" id="ya" onclick="InsertText('य')">य</div>
                    <div class="keys tline" id="ra" onclick="InsertText('र')">र</div>
                    <div class="keys tline" id="la" onclick="InsertText('ल')">ल</div>
                    <div class="keys tline" id="va" onclick="InsertText('व')">व</div>
                    <div class="keys tline" id="Sa" onclick="InsertText('श')">श</div>
                    <div class="keys tline" id="sha" onclick="InsertText('ष')">ष</div>
                    <div class="keys tline" id="sa" onclick="InsertText('स')">स</div>
                    <div class="keys tline" id="ha" onclick="InsertText('ह')">ह</div>
                    <div class="keys tline" id="La" onclick="InsertText('ळ')">ळ</div>
                    <div class="keys lline" style="clear: left;" id="Akara" onclick="InsertText('ा')">ा</div>
                    <div class="keys" id="ikara" onclick="InsertText('ि')">ि</div>
                    <div class="keys" id="Ikara" onclick="InsertText('ी')">ी</div>
                    <div class="keys" id="ukara" onclick="InsertText('ु')">ु</div>
                    <div class="keys" id="Ukara" onclick="InsertText('ू')">ू</div>
                    <div class="keys" id="Rukara" onclick="InsertText('ृ')">ृ</div>
                    <div class="keys" id="RUkara" onclick="InsertText('ृ')">ॄ</div>
                    <div class="keys lline" id="ekara" style="clear: left;" onclick="InsertText('े')">े</div>
                    <div class="keys" id="aikara" onclick="InsertText('ै')">ै</div>
                    <div class="keys" id="okara" onclick="InsertText('ो')">ो</div>
                    <div class="keys" id="aukara" onclick="InsertText('ौ')">ौ</div>
                    <div class="keys" id="avagraha" onclick="InsertText('ऽ')">ऽ</div>
                    <div class="keys" id="halanta" onclick="InsertText('्')">्</div>
                </div>
            </div>
        </form>
    </div>
