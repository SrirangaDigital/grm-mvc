<script type="text/javascript">
$(document).ready(function() {

    // This is done to scroll prateekas even if the page does not have enough data
    $('.refBanner .vyakhyaHolder').append($('<div>&nbsp;</div>').height($(window).height()));
    // Set vayjhya banner height
    $('.refBanner').height($(window).height());

    // Include affix to vyakhya banner
    var navHeight = $( '.navbar' ).outerHeight() + 1;
    $(".refBanner").affix({
        offset: {
          top: navHeight + $( '.splitWindow-headMain' ).outerHeight()
        }
    });

    $(".splitWindow-headMain").affix({
        offset: {
          top: navHeight
        }
    });

    $(".splitWindow-headMain").on('affixed.bs.affix', function(){

        $('.splitwindow-text').css('padding-top', $('.splitWindow-headMain').height());
    });

    $(".splitWindow-headMain").on('affix-top.bs.affix', function(){

        $('.splitwindow-text').css('padding-top', 0);
    });

    // Set width same as that of bhashyaPara. This is required as afix element does a position fixed on the banner
    $('.refBanner').on('affixed.bs.affix', function(){

        $(this).width($('.bhashyaPara').width());
    });

    // Remove anchors (links to other texts) from bhashyaPara
    $('.bhashyaHolder a').each(function(){

        $(this).replaceWith($(this).html());
    });    
    
    var ffid = '<?=$data['id']?>';

    $( '.overlayText' ).html($( '.bhashyaHolder' ).html().replace(/id=\"/g, 'id="OL_'));
    $( '.overlayText' ).width($( '.bhashyaHolder' ).width());
  
    var topAllowance = 2 * Math.ceil($( '.overlayText' ).css('font-size').replace('px', ''));


    $('.prateekaList .prateeka a').on("click", function(event){

        $.Event(event).preventDefault();
        var anchorHref = $( this ).attr( 'href' ).split("#");


        var prt = anchorHref[1].replace( /\+/g, "\\+" );
        var vid = anchorHref[0];

        // Reset scrollTop of refBanner
        $(".refBanner").scrollTop(0);
        var tpSearch = $( '.refBanner' ).find( "[id='" + vid + "']" ).find('.prateeka, .uddharana');
        
        if(tpSearch.length == 0) {

            tpSearch = $( '.refBanner' ).find( "[id='" + vid + "']" );
        }
        var tp = tpSearch.position().top - 5;

        if($('.splitWindow-headMain').hasClass('affix')) {

            var oldTp = tp;
            tp = tp - $('.splitWindow-headMain').height();
        }

        if(tp < 0){

            $("html, body").animate({scrollTop: 0}, 0);
            $(".refBanner").scrollTop(oldTp);
        }
        else{

            $(".refBanner").scrollTop(tp);
        }

    });

    $( '.prateekaList .prateeka a' ).on("mouseenter", function(){

        var prt = $( this ).attr( 'href' ).split("#")[1];
        var list = getIDList(ffid, prt);

        for (i = 0; i < list.length; i++) {
            
            if(isScrolledIntoView('#' + list[i])) {

                $( '#' + list[i] ).addClass( 'boldClr' );
            }
            else{
                
                $( '#OL_' + list[i] ).addClass( 'boldClr' );
                $( '.overlayText' ).show();
                $( '.overlayText' ).scrollTop(0);
                $( '.overlayText' ).scrollTop($( '#OL_' + list[0] ).position().top - topAllowance);
            }
        }
    });

    $( '.prateekaList .prateeka a' ).on("mouseleave", function(){

        $( '.overlayText' ).hide();
        
        var prt = $( this ).attr( 'href' ).split("#")[1];
        var list = getIDList(ffid, prt);

        for (i = 0; i < list.length; i++) {

            $( '#' + list[i] ).removeClass( 'boldClr' );
            $( '#OL_' + list[i] ).removeClass( 'boldClr' );
        }
    });

    function getIDList(bhashyaID, ids) {

        ids = ids.replace(/;/g, "+");
        ids = ids.replace(/ \- /g, "-");

        var list = new Array();

        if(ids.match(/\+/)) {

            list = ids.split("+");;
        }
        else if(ids.match(/\-/)) {


            var range = ids.split('\-');
            var begin = range[0];
            var end = range[1];

            var fullList = new Array();
            
            $( '#' + bhashyaID + ' span').each(function(){

                if($(this).children().length == 0) {

                    fullList.push($(this).attr( 'id' ));
                }
            });

            var beginIndex = $.inArray(begin, fullList);
            var endIndex = $.inArray(end, fullList);

            return (fullList.slice(beginIndex, endIndex + 1));
        }
        else{

            list.push(ids);
        }
        return (list);
    }

    function isScrolledIntoView(elem) {

        var $elem = $(elem);
        var $window = $(window);

        var splitHead = $('.splitWindow-headMain').height();
        var docViewTop = $window.scrollTop() + splitHead;
        var docViewBottom = docViewTop + $window.height();

        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $elem.height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    $('.full-page-mask').hide();

});
</script>
<div class="full-page-mask">
    <i class="fa fa-spinner fa-spin"></i>
</div>
<div class="container-fluid maintext">
    <div class="row splitWindow-headMain">
        <div class="col-md-6 clear-paddings">
            <div class="splitWindow-head text-right"><?=$viewHelper->getBhashyaDetails($data['bhashya'])['name']?></div>
            <div class="splitWindow-address text-right">
                <?php
                    foreach ($data['address'] as $value) {

                        echo '<span>' . $value . '</span>';
                    }
                ?>
                <?php
                    if($data['prevNext']) {

                        echo '<div class="prevNext">';
                        if(isset($data['prevNext']['prevID'])) echo '<span class="prev"><a href="' . $data['prevNext']['prevID'] . '"><i class="fa fa-angle-left"></i> पूर्वपृष्ठम् &nbsp;&nbsp;</a></span>';
                        if(isset($data['prevNext']['nextID'])) echo '<span class="next"><a href="' . $data['prevNext']['nextID'] . '">&nbsp;&nbsp; उत्तरपृष्ठम् <i class="fa fa-angle-right"></i></a></span>';
                        echo '</div>';
                    }
                ?>
            </div>
        </div>
        <div class="col-md-6 clear-paddings" style="border-left: 5px solid #B3BDF0;">
            <div class="splitWindow-head text-left"><?=$viewHelper->getBhashyaDetails($viewHelper->bhashyaDef{$data['vyakhya']})['name']?></div>
            <div class="splitWindow-address">
                &nbsp;
            </div>
        </div>
    </div>
    <div class="row splitwindow-text">
        <!--Main Content -->
        <div class="col-md-6 bhashyaPara clear-paddings">
            <div class="overlayText"></div>
            <div class="bhashyaHolder">
                <?=$data['versetext']?>
                <?=$data['bhashyaPara']?>
            </div>

            <div class="prateekaList" id="prateekaList">
            
<?php

foreach ($data['prateeka'] as $prateeka) {

    $prateeka['text'] = preg_replace('/।/', '', $prateeka['text']);
    $prateeka['text'] = preg_replace('/\s$/', '', $prateeka['text']);

    echo '<span class="prateeka"><a href="' . $prateeka['vid'] . '#' . $prateeka['id'] . '">' . $prateeka['text'] . '</a></span> ; ';
}

?>
            </div>
        </div>
        <div class="col-md-6 clear-paddings">
            <div class="refBanner">
                <div class="vyakhyaHolder">
                    <?=$data['vyakhyaPara']?>
                </div>
            </div>
        </div>
        </div>
