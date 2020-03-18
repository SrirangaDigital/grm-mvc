// ScrollStart/ScrollStop events: http://james.padolsey.com/javascript/special-scroll-events-for-jquery/

baseUrl = window.location.href.replace(/(.*)display.*/, "$1");

$(document).ready(function() {

	var stickyOffset = $('#header').outerHeight() + $('.jumbotron').outerHeight();

	$('body').scrollspy({
	    target: '#sidebar',
	    offset: 40
	});

	$(window).on('load scrollstop', function(){

		$('.address').html(getAddress());
	});

	$(window).on('scroll', function(){

		if($('body, html').scrollTop() > stickyOffset){

			$('.address').removeClass('hidden');
			$('#sidebarUl').removeClass('hidden');
			$('#sidebarUl').addClass('affix');
		}
		else{
			
			$('.address').addClass('hidden');
			$('#sidebarUl').addClass('hidden');
			$('#sidebarUl').removeClass('affix');
		}
	});


	$('.expand-all').click(function(){

		$('.all-bhashya').toggleClass('collapse');
	});
	// Insertion of headings
	
	insertChapterName();
	insertSectionName();

	// printNavigation();
	// printNavigationFromFile();

	// insertBhashyaTrigger();
	wrapAndFoldBhashya();

	insertTargetBlank();

    // Insert Vyakhya Links
    if($('.vyakhyaPara').length) {
		
		insertVyakhyaLinks();
		// Increase bhashya font size by 10%
    	// $( '.intro_bhashya, .aux_bhashya, .leading_bhashya, .bhashya, .versetext' ).css({'font-size' : '1.1em', 'line-height' : '33px'});
	}

	bindJumpToID();
	reformLinks();
	addPageNumber();

	$('#sidebar a, .auxiliary-links a').on('click', function(event){

		nextID = $(this).attr('href').replace('#', '');
		
		if(!($('.maintext #' + nextID).length)) {

			bhashya = $('.maintext').attr('data-bhashyaID');
			page = parseInt(nextID.replace(/.*(\d\d)/, "$1"));
		    currentID = $('.chapter').last().attr('id');

		    $('#loader').fadeIn('slow');
			addPageLazilyAndJump(bhashya, page, currentID, nextID);
    		$('#loader').fadeOut('slow');
		}
		else{
			var jumpLoc = $('#' + nextID).offset().top - 20;
	        $("html, body").animate({scrollTop: jumpLoc}, 500);
		}
	});

	$( "a, button" ).click(function(){
        $( "#ajaxLoader" ).remove();
        $(this).append("<i id=\"ajaxLoader\" class=\"fa fa-spinner fa-spin\"></i>");
        $( "#ajaxLoader" ).hide();
    });

    $( document )
    .ajaxStart(function() {
        setTimeout( function(){$( "#ajaxLoader" ).fadeIn( 50 );}, 1);
    })
    .ajaxStop(function() {
        setTimeout( function(){$( "#ajaxLoader" ).fadeOut( 250 );}, 1);
    })
    ;

    if(document.getElementById('viewerGallery')){
        
        var viewer = new Viewer(document.getElementById('viewerGallery'), {url: 'data-original'});
    }

    $('#searchIcon').on('click', function(){

    	$(this).parents('form').submit();
    });

});

(function(){var e=jQuery.event.special,t="D"+ +(new Date),n="D"+(+(new Date)+1);e.scrollstart={setup:function(){var n,r=function(t){var r=this,i=arguments;if(n){clearTimeout(n)}else{t.type="scrollstart";jQuery.event.handle.apply(r,i)}n=setTimeout(function(){n=null},e.scrollstop.latency)};jQuery(this).bind("scroll",r).data(t,r)},teardown:function(){jQuery(this).unbind("scroll",jQuery(this).data(t))}};e.scrollstop={latency:300,setup:function(){var t,r=function(n){var r=this,i=arguments;if(t){clearTimeout(t)}t=setTimeout(function(){t=null;n.type="scrollstop";jQuery.event.dispatch.apply(r,i)},e.scrollstop.latency)};jQuery(this).bind("scroll",r).data(n,r)},teardown:function(){jQuery(this).unbind("scroll",jQuery(this).data(n))}}})();

function bindScriptSwitch(sParam) {

	var langName = {'iast' : 'roman', 'devanagari' : 'देवनागरी', 'kn' : 'ಕನ್ನಡ', 'bn' : 'বাংলা', 'ta' : 'தமிழ்'}
	var url = window.location.href;
	var parentUrl = url.replace(/\/(devanagari|iast|kn|bn|ta)/, '/');
	var langCode = url.replace(/.*\/(devanagari|iast|kn|bn|ta).*/, "$1");

	$('.scriptSwitch').each(function(){

		if($(this).attr('data-lang-code') === langCode) {

			$(this).hide();
		}
		else{

			$(this).attr('href', parentUrl.replace(/(.*)\/(.*)/, "$1" + '/' + $(this).attr('data-lang-code') + "$2"));
			$(this).html(langName[$(this).attr('data-lang-code')]);
		}
	});
}

function getUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return sParameterName[1];
        }
    }
}

function getAddress(){

	var verse = $('div.verse:in-viewport').first();

	var address = '<span>' + $('.maintext').attr('data-bhashya') + '</span>';
	
	var chapter = verse.parents('.chapter').attr('data-name');
	if(!(chapter)) chapter = verse.parents('.chapter').attr('data-title');
	
	var section = verse.parents('.section').attr('data-name');
	if(!(section)) section = verse.parents('.section').attr('data-title');
	
	var subsection = verse.attr('data-adhikarana');
	var sutra = verse.children('.versetext').children('p').html();
	if(!(sutra)) sutra = verse.children('.versetext').html();
	sutraNumber = getSutraNumber(sutra);

	address += (chapter != null) ? '<span>' + chapter + '</span>' : '';
	address += (section != null) ? '<span>' + section + '</span>' : '';
	address += (subsection != null) ? '<span>' + subsection + '</span>' : '';
	address += (sutraNumber != null) ? '<span>' + sutraNumber + '</span>' : '';

	address = '<i class="fa fa-map-marker map-marker"></i>' + address;

	return address;
}

function getAddressByID(id){

	var verse = $( '#' + id);

	var address = '<span>' + $('.maintext').attr('data-bhashya') + '</span>';
	
	var chapter = verse.parent().parent('.chapter').attr('data-name');
	if(!(chapter)) chapter = verse.parent().parent('.chapter').attr('data-title');

	var section = verse.parent('.section').attr('data-name');
	if(!(section)) section = verse.parent('.section').attr('data-title');
	
	var subsection = verse.attr('data-adhikarana');
	var sutra = getSutraNumber(verse.children('.versetext').children('p').html());
	
	address += (chapter != null) ? '<span>' + chapter + '</span>' : '';
	address += (section != null) ? '<span>' + section + '</span>' : '';
	address += (subsection != null) ? '<span>' + subsection + '</span>' : '';
	address += (sutra != null) ? '<span>' + sutra + '</span>' : '';

	address = '<i class="fa fa-map-marker map-marker"></i>' + address;

	return address;
}

function insertChapterName(){

	$('.chapter').each(function(){
		
		// Here pseudo element is checked for the case of Isha
		if(!($(this).find('h1').length) && ($(this).attr('type') != 'pseudo')) {
		
			var chapterName = $(this).attr('data-title');
			if(!(chapterName)) chapterName = $(this).attr('data-name');
			chapterName = '<h1><span class="motif"></span> ' + chapterName + '</h1>';

			$(this).prepend(chapterName);
		}
	});
}

function insertSectionName(){

	$('.section').each(function(){
		
		if(!($(this).find('h2').length)) {
		
			var sectionName = $(this).attr('data-name');
			if(!(sectionName)) sectionName = $(this).attr('data-title');
			sectionName = '<h2>' + sectionName + '</h2>';

			$(this).prepend(sectionName);
		}
	});
}

function insertBhashyaTrigger(){

	$('.verse .versetext').each(function(){

		if(!($(this).find('.show-bhashya').length)) {
			// Include bhashya trigger only when bhashya paragraphs are available and type is not gadya
			
			if(($(this).siblings('.bhashya').length) && ($(this).attr('type') != 'gadya')) {

				var verseID = $(this).parent('.verse').attr('id');
				var verseContents = '<p>' + $(this).html() + '</p>'+ '<div class="show-bhashya"><a data-toggle="collapse" href="#bhashya-' + verseID + '"><i class="fa fa-long-arrow-down"></i> भाष्यम्</a></div>';
				$(this).html(verseContents);
			}
		}
	});
}

function wrapAndFoldBhashya(){

	var bhashya = $('.maintext').attr('data-bhashyaID');

	var loadAudio = (window.location.href.match(/display\/bhashya\/vyasa-bhashyam/)) ? true : false;
	$('.verse').each(function(){

		// Include audio links
		var verse_id = $(this).attr('id');
		if(!($(this).find('.playPause').length) && loadAudio) {

			$(this).prepend('<i class="fa fa-play-circle playPause" data-id="audio-' + verse_id + '" id="control-audio-' + verse_id + '" title="Audio book - Play"></i><audio id="audio-' + verse_id + '" src="' + baseUrl + 'audio-sources/' + bhashya + '/' + verse_id.replace(/^audio-/, '') + '.mp3">Your user agent does not support the HTML5 Audio element.</audio>');
		}

		if(!($(this).find('.all-bhashya').length)) {

			var verseID = $(this).attr('id');

			$('[id^=' + verseID + '_B].bhashya').wrapAll('<div id="bhashya-' + verseID + '" class="all-bhashya">');
		}
	});
}

// function printNavigationFromFile() {

// 	var bhashya = $('.maintext').attr('data-bhsahyaID');

//     var url = "http://localhost/sbsn/json-precast/" + bhashya + "-primary-nav.php";

//     $.get( url, function( data ) {

// 		$('#sidebar').prepend(data);
//     });
// }

function printNavigation(){

	var nav = '';

	$('.chapter').each(function(){

		var chapter = $(this);
		nav += '<li>';
		nav += '<a href="#' + chapter.attr('id') + '">' + chapter.children('h1').html() + '</a>';
        
        if(chapter.children('.section').length) {                
			
			nav += '<ul class="nav nav-stacked">';
			chapter.children('.section').each(function(){

				var section = $(this);
				nav += '<li>';
				nav += '<a href="#' + section.attr('id') + '">' + section.children('h2').html() + '</a>';
				
		        if(section.children('.subsection').length) {                
					
					nav += '<ul class="nav nav-stacked">';
					section.children('.subsection').each(function(){

						var subsection = $(this);
						nav += '<li>';
						nav += '<a href="#' + subsection.attr('id') + '">' + subsection.attr('data-title') + '</a>';
						nav += '</li>';
					});
					nav += '</ul>';
				}

				nav += '</li>';
			});
			nav += '</ul>';
		}
		nav += '</li>\n';
	});

	$('#sidebar').prepend(nav);
}

function insertTargetBlank() {

	$('.qt a').attr('target', '_blank');
}

function reformLinks() {

    $(function () {

    	$('[data-toggle="popover"]').popover({
            
            content: function () {
    			
    	        return $(this).next('span.aside').html();
            },
            trigger: 'focus',
            html: true,
            placement: 'bottom'
    	});

    	$('[data-toggle="popover"]').on('shown.bs.popover', function(){

    		reformFootNoteHref();
    	});
    })
}

function reformFootNoteHref(href) {

	var bhashya = $('.maintext').attr('data-bhashyaID');

	$('.popover-body a').each(function(){
		
		var parentURL = window.location.href
			.replace('/bhashyaVyakhya/', '/bhashya/')
			.replace(/(.*)#.*/, "$1")
			.replace(/(.*)\?.*/, "$1");

		var href = parentURL + $(this).attr('href').replace(bhashya, '');

		if(!(href.match('page='))) {

			page = parseInt(href.split('?id=')[1].replace(/.*_C(\d\d).*/, "$1"));
			href = href.replace('?id=', '?page=' + page + '&id=');
		}

		if(!(href.match('&hl='))) {
			var anchorText = $(this).text();

			anchorText = anchorText.replace('‘', '');
			anchorText = anchorText.replace('’', '');
			anchorText = anchorText.replace(/ \(.*\)/, '');

			href = href + '&hl=' + anchorText;
			$(this).attr('href', href);
		}
		$(this).attr('target', '_blank')
	});
}

function bindJumpToID() {

	var hloc = window.location.href;
    if(hloc.match('id=')){


    	var jumpID = getUrlParameter('id');
    	var re = new RegExp('_B[0-9]+$');
		jumpID = jumpID.replace(re, '');

	    if(hloc.match('hl=')){

    		var hlText = decodeURIComponent(getUrlParameter('hl'));
    		var jumpLoc = $('#' + jumpID).offset().top - 40;
        	$("html, body").animate({scrollTop: jumpLoc}, 1000, insertHlText(jumpID, hlText));
    	}
    	else if(hloc.match('hlBhashya=')){

	    	var bhashyaID = getUrlParameter('id');

    		var hlText = decodeURIComponent(getUrlParameter('hlBhashya'));

    		if(getUrlParameter('vyakhya') !== undefined) {

	    		$( 'a[data-id="' + bhashyaID + '"]' ).click();
	    		var jumpLoc = $( 'a[data-id="' + bhashyaID + '"]' ).offset().top - 50;
    		}
    		else{
    			
    			insertHlTextBhashya(jumpID, bhashyaID, hlText);
	    		var jumpLoc = $('#' + bhashyaID).offset().top - 150;
    		}

        	$("html, body").animate({scrollTop: jumpLoc}, 1000);
    	}
    	else if(hloc.match('hlUllekha=')){
	
	    	var ullekhaID = getUrlParameter('id');
			$('#' + ullekhaID).addClass('highlight');
			$('#' + ullekhaID).parents('.all-bhashya').removeClass('collapse');
			
    		var jumpLoc = $('#' + ullekhaID).offset().top - 150;
        	$("html, body").animate({scrollTop: jumpLoc}, 1000);
    	}
    	else{

    		var jumpLoc = $('#' + jumpID).offset().top - 40;
        	$("html, body").animate({scrollTop: jumpLoc}, 1000);	
    	}
    }
}

function insertHlText(id, text) {

	$('#' + id + ' .versetext').before('<div class="hl-text"><i class="fa fa-hand-o-right"></i> ' + text + '</div>');
	$('#bhashya-' + id).removeClass('collapse');

	// Highlight text

	var verse = $('#' + id + ' .versetext p').html();
	if(verse == null) verse = $('#' + id + ' .versetext').html();

	var re = new RegExp('(' + text + '.*?) ', "g");
	verse = verse.replace(re, '<span class="highlight">' + "$1" + '</span> ');

	$('#' + id + ' .versetext p').html(verse);
}

function insertHlTextBhashya(id, bhashyaID, text) {


	$('#' + bhashyaID).before('<div class="hl-text"><i class="fa fa-search"></i> ' + text + '</div>');
	$('#bhashya-' + id).removeClass('collapse');

	// Highlight text

	// Remove word spans before highlighting
	$('#' + bhashyaID + ' span:not(.qt)').contents().unwrap();
	var bhashya = $('#' + bhashyaID).html();



	var re = new RegExp('(' + text + '.*?)([ <])', "g");
	bhashya = bhashya.replace(re, '<span class="highlight">' + "$1" + '</span>' + "$2");

	$('#' + bhashyaID).html(bhashya);
}

function insertHlTextVyakhya(vyakhyaID, text) {

	$('#' + vyakhyaID + '.vyakhyaHolder').before('<div class="hl-text"><i class="fa fa-search"></i> ' + text + '</div>');

	// Highlight text

	var vyakhya = $('#' + vyakhyaID + '.vyakhyaHolder').html();

	var re = new RegExp('(' + text + '.*?)', "g");
	vyakhya = vyakhya.replace(re, '<span class="highlight">' + "$1" + '</span>');

	$('#' + vyakhyaID + '.vyakhyaHolder').html(vyakhya);
}

function getSutraNumber(verse) {

	if(verse) {

		var verseNumber = verse.replace(/.*॥ ([०१२३४५६७८९]+) ॥.*/, "$1");

		return (verseNumber == verse) ? '' : $('.maintext').attr('data-verseType') + ' ' + verseNumber;
	}
	return null;
}

function addPageNumber() {

	$('.chapter').each(function(){
		
		if(!($(this).attr('data-page'))) {
		
			id = parseInt($(this).attr('id').replace(/.*(\d\d)/, "$1"));

			// For bhashyas having no chapters, pseudo page 0 is made as 1
			if (id == 0) id = 1;

			$(this).attr('data-page', id);
		}
	});
}

function addPageLazilyAndJump(bhashya, page, currentID, nextID) {

	var parentURL = window.location.href
		.replace('/bhashya/', '/getBhashyaByPage/')
		.replace('/bhashyaVyakhya/', '/getBhashyaByPage/')
		.replace(/(.*)#.*/, "$1")
		.replace(/(.*)\?.*/, "$1");

    var url = parentURL + "?page=" + page;
    
    $.get( url, function( data ) {
        
        if(data) {

	        $( "#" + currentID ).after( data );
            insertChapterName();
            insertSectionName();

            // printNavigation();

            // insertBhashyaTrigger();
            wrapAndFoldBhashya();

            insertTargetBlank();

            // bindJumpToID();

            reformLinks();

            addPageNumber();

            if($('.vyakhyaPara').length) {
				
				insertVyakhyaLinks();
			}
        }
		    
		var jumpLoc = $('#' + nextID).offset().top - 20;
        $("html, body").animate({scrollTop: jumpLoc}, 500);
    });
}

function slideDownVyakhya() {
    
    var id = $(this).attr("data-id");
    var vyakhya = $(this).attr("data-vyakhya");
    
    var combiVyakhya = (getUrlParameter('subVyakhya') !== undefined) ? vyakhya + ';' + getUrlParameter('subVyakhya') : vyakhya;

	var bhashya = $('.maintext').attr('data-bhashyaID');

    if ($( '#' + vyakhya + '_' + id ).is(':empty')) {
  
        $( 'a[data-id="' + id + '"]' ).prepend("<i class=\"fa fa-spinner fa-spin ajaxLoader\"></i>");

    	var script = window.location.href
			.replace('/' + bhashya, '')
			.replace(/.*?display\/bhashya.*?\/(.*)\?vyakhya.*/, "$1");

		var url = baseUrl + "display/getVyakhya/" + combiVyakhya + "/" + id + "/" + script;

        $( '#' + vyakhya + '_' + id ).load( url, function(){

        	reformLinks();
            insertTargetBlank();
        	
            $( '.ajaxLoader' ).remove();
            $( '#' + vyakhya + '_' + id ).slideDown(300);

            console.log(getUrlParameter('hlBhashya'));
            if(getUrlParameter('hlBhashya') !== undefined) {

	    		var hlText = decodeURIComponent(getUrlParameter('hlBhashya'));
	        	insertHlTextVyakhya(vyakhya + "_" + id, hlText);
            }
        });
    }
    else{
        $( '#' + vyakhya + '_' + id ).slideToggle(300);
    }
}

function insertVyakhyaLinks() {
    
	var vyakhya = $('.vyakhyaPara').attr('data-vyakhya');
	var corpusName = $('.vyakhyaPara').attr('data-corpusName');

	if(!(vyakhya)) return null;

	var vyakhyaPara = $('.vyakhyaPara').html();

	var paragraphs = vyakhyaPara.split(';');

	for(var i = 0; i < paragraphs.length; i++) {

	    var id = paragraphs[i];

		if(!($('#' + id).next('.vyakhyaTrigger').length)) {

			var bhashya = $('.maintext').attr('data-bhashyaID');

		    var insertContent = '<div class="vyakhyaTrigger"><span class="show_vyakhya"><a data-id="' + id + '" data-vyakhya="' + vyakhya + '" class="vyakhya-trigger-slide"><i class="fa fa-chevron-down"></i> ' + corpusName + '</a>';
		    // Split window link disabled
		    // if (vyakhya != 'TV') insertContent += '<a target="_blank" href="' + baseUrl + 'display/splitWindow/' + bhashya + '/' + vyakhya + '/' + id + '"><i class="fa fa-columns rotate"></i></a>';
		    insertContent += '</span></div><div id="' + vyakhya + '_' + id + '" class="vyakhyaHolder"></div>';

		    $('#' + id).after(insertContent);
		}
	}

    // Bind vyakhya slide down event
    $(".vyakhya-trigger-slide").on("click", slideDownVyakhya);
}

function bindLazyLoading() {

    var bottomAllownace = 200;
    if($(window).scrollTop() + $(window).height() > $(document).height() - bottomAllownace) {

        addPageBottom();
    }
}


function addPageBottom() {

    $('#loader').fadeIn('slow');

    $( window ).off('scroll', bindLazyLoading);

	var bhashya = $('.maintext').attr('data-bhashyaID');

    var page = parseInt($('.chapter').last().attr('data-page')) + parseInt(1);
    var currentID = $('.chapter').last().attr('id');

	var parentURL = window.location.href
		.replace('/bhashya/', '/getBhashyaByPage/')
		.replace('/bhashyaVyakhya/', '/getBhashyaByPage/')
		.replace(/(.*)#.*/, "$1")
		.replace(/(.*)\?.*/, "$1");

    var url = parentURL + "?page=" + page;

    $.get( url, function( data ) {
        
        if(data) {
         
            $( "#" + currentID ).after( data );
            insertChapterName();
            insertSectionName();

            // printNavigation();

            // insertBhashyaTrigger();
            wrapAndFoldBhashya();

            insertTargetBlank();

            // bindJumpToID();

            reformLinks();

            addPageNumber();

        	if($('.vyakhyaPara').length) {
		
				insertVyakhyaLinks();
			}

            $( window ).on('scroll', bindLazyLoading);
        }
        $('#loader').fadeOut('slow');
    });
}

function insertArrow() {

    // 75 is padding left
    var top = $('.jumbotron .jumbo-section .left').position().top + $('.jumbotron .jumbo-section .left').outerHeight() + $('.navbar').outerHeight();
    var bottom = $('.jumbotron .jumbo-section .right').position().top + $('.jumbotron .jumbo-section .right').outerHeight() + $('.navbar').outerHeight();
    var ht = bottom - top - ($('.jumbotron .jumbo-section .right p').outerHeight());
    var left = $('.jumbotron .jumbo-section .left').outerWidth() - (75 * 2.5);
    var width = $('.jumbotron .jumbo-section .left').outerWidth() + (75 * 1.5) - left;

    $('.arrow').css('height', Math.ceil(ht) + 'px');
    $('.arrow').css('top', Math.ceil(top) + 'px');
    $('.arrow').css('width', Math.ceil(width) + 'px');
    $('.arrow').css('left', Math.ceil(left) + 'px').show();
}

function updateSelectSutra(){
    
    var sutra = '';
    $('.select-works.sutra input').each(function(){

        if($(this).is(':checked'))
            sutra += '|' + $(this).val();
    });
    $('#searchIn').val(sutra.replace(/^\|/, ''));
    console.log($('#searchIn').val());
}
function updateSelectBhashya(){
    
    var bhashya = '';
    $('.select-works.bhashya input').each(function(){

        if($(this).is(':checked'))
            bhashya += '|' + $(this).val();
    });
    $('#searchIn').val(bhashya.replace(/^\|/, ''));
    console.log($('#searchIn').val());
}
function updateSelectVyakhya(){
    
    var vyakhya = '';
    $('.select-works.vyakhya input').each(function(){

        if($(this).is(':checked'))
            vyakhya += '|' + $(this).val();
    });
    $('#searchIn').val(vyakhya.replace(/^\|/, ''));
    console.log($('#searchIn').val());
}

function embedAudio() {

	// event-delegation used; Sufficient to bind just once.
	$('body').on('click', '.playPause', function(){

	    var id = $(this).attr('data-id');
	    var myAudio = document.getElementById(id);

	    if (myAudio.paused) {

	    	if($('.maintext.footer').attr('data-audio-playing') !== undefined) {

	    		var prevId = $('.maintext.footer').attr('data-audio-playing');
	    		var prevAudio = document.getElementById(prevId);
	    		prevAudio.pause();
		        $("#control-" + prevId).removeClass('fa-pause-circle').addClass('fa-play-circle').attr('title', 'Audio book - Play');
	    	}

    		$('.maintext.footer').attr('data-audio-playing', id);
	        myAudio.play();
	        $("#control-" + id).removeClass('fa-play-circle').addClass('fa-pause-circle').attr('title', 'Audio book - Pause');
	    }
	    else {
	    
	        myAudio.pause();
	        $("#control-" + id).removeClass('fa-pause-circle').addClass('fa-play-circle').attr('title', 'Audio book - Play');
	    }

	    $(myAudio).bind("ended", function(){

	        $("#control-" + id).removeClass('fa-pause-circle').addClass('fa-play-circle').attr('title', 'Audio book - Play');
	    });
	});
}