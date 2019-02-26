
var $window = $(window);
var $pageTitle = $('.home-page-title');
//var $logo = $('#logo');
var logoTopMargin;



//Album info scroll events
function albumInfoScroll() {
	//$albumCont var needs to refresh after it is deleted and added back by vue
	var $albumCont = $('.album-image-cont');
	if (!($albumCont.html() == undefined)) {
		if ($albumCont.eq(0).offset().top < ($window.scrollTop() + ($window.height()/1.5))	) {
			if (vm.appSettings.showRightLogo) {
				vm.appSettings.showRightLogo = false;
			}
		} else {
			if (!vm.appSettings.showRightLogo) { 
				vm.appSettings.showRightLogo = true;
				vm.appSettings.showInfo = false;
			}
		}
	}
	if (!($albumCont.html() == undefined)) {
		$albumCont.each(function(index) {
			if ( $(this).offset().top < ($window.scrollTop() + ($window.height()/1.5)) && ( $(this).next().html() == undefined || $(this).next().offset().top > ($window.scrollTop() + ($window.height()/1.5))) ) {
				//need second condition to deal with Vue adding containers before removing them
				if (vm.appSettings.currentAlbum != index && vm.albums.length >= index) {
					vm.appSettings.showInfo = false;
					vm.appSettings.currentAlbum = index;
				}
			}
		});
	}
}

var albumInfoInterval =  setInterval(albumInfoScroll, 200);



//Page title parallax
setInterval(function() {
	//page title needs to be re-stored if it was removed and re-added by Vue
	if(!$pageTitle) {
		var $pageTitle = $('.home-page-title');
	}

	if ($window.scrollTop() >= 0 && $window.scrollTop() < 1000) {
		var scrollPerc = Math.floor((100 - ($window.scrollTop() / 4.7)));
		var scrollPercString = scrollPerc.toString() + '%';
		$pageTitle.css('top', scrollPercString);
	} else if ($window.scrollTop() > 1000) {
		$pageTitle.css('top', '-150%');
	}
}, 100);



//Center homepage logo vertically
function getLogoMargin() {
	console.log('center logo test');
	//re-store logo element if Vue removes and re-adds it
	var $logo = $('#logo');
	logoTopMargin = Math.floor(($window.height() / 2) - ($logo.height() / 2));
	console.log('logoTopMargin: ' + logoTopMargin);
	$logo.css('margin-top', logoTopMargin + 'px');
}

function setLogoMargin() {
	console.log('center logo test 2');
	var	$logo = $('#logo');
	if (logoTopMargin) {
		$logo.css('margin-top', logoTopMargin + 'px');
	} else {
		console.log('logoTopMargin2: ' + logoTopMargin);
		getLogoMargin();
	}
}

getLogoMargin();

function scrollToTop() {
	$('html, body').stop().animate({
	     scrollTop: 0
  	}, 400);
}

function centerAlbumCover() {
	var albumCont = $('.album-image-cont');
}


//Change Vue properties to simulate back/forward buttons
window.onpopstate = function() {
	if (history.state.albums) vm.albums = history.state.albums;
	if (history.state.artists) vm.artists = history.state.artists;
	if (history.state.appSettings) vm.appSettings = history.state.appSettings;
}

