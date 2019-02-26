$(function() {
	var $deleteSongBtn = $('.delete-icon');
	var $addSongBtn = $('.add-icon');

	var $deleteAlbBtn = $('.delete-button');
	var lastEvent;
	var $deleteAlrtCont = $('#delete-alert-cont');
	var $deleteMsgCont = $('#delete-alert-message');

	var $deleteAlrtYes = $('#alert-button-yes');
	var $deleteAlrtNo = $('#alert-button-no');


	$deleteSongBtn.on('click', function(e) {
		deleteEvent(e);
	});

	$addSongBtn.on('click', function(e) {
		addEvent(e);
	});

	$deleteAlbBtn.on('click', function(e) {
		deleteAlert(e);
	});

	$deleteAlrtYes.on('click', function() {
		$(lastEvent.target).parent().submit();
		$deleteAlrtCont.hide();
	});

	$deleteAlrtNo.on('click', function() {
		$deleteAlrtCont.hide();
	});


	//Update tracklist numbers when track is deleted/added
	function updateTrackNumbers() {
		var $trackNumbers = $('.tracklist-number');
		$trackNumbers.each(function(index) {
			$(this).text(' ' + (index + 1) + '. ');
		});
	}

	//delete song input
	function deleteEvent(e) {
		if ( $('.tracklist').length > 1 ) {
			$(e.target).parent().remove();
		}
		updateTrackNumbers();
	}

/*
	//add song input
	function addEvent(e) {
		var $element = $('<div class="tracklist"></div>').append($('<label></label>').html('&nbsp;'));
		$element.append($('<span class="tracklist-number"></span'));
		$element.append($('<input type="text" name="songs[]">'));
		$element.append($('<img class="add-icon" src="../../images/delete_icon.png">').on('click', function(e){ addEvent(e); } ));
		$element.append($('<img class="delete-icon" src="../../images/delete_icon.png">').on('click', function(e){ deleteEvent(e); } ));
		$(e.target).parent().after($element);
		$element.children('input').focus();

		updateTrackNumbers();
	}
*/

	//add song input
	function addEvent(e) {
		var element = $(e.target).parent();
		var newElement = element.clone();
		newElement.children('.add-icon').on('click', function(e) { addEvent(e); } );
		newElement.children('.delete-icon').on('click', function(e) { deleteEvent(e); } );
		newElement.children('input').val('');
		element.after(newElement);
		newElement.children('input').focus();

		updateTrackNumbers();
	}

	//pop-up alert when deleting
	function deleteAlert(e) {
		e.preventDefault();
		lastEvent = e;
		console.log(lastEvent.target);
		if ($(e.target).is('.artist-form')) {
			var albumName = $(e.target).parent().prev().children().text();
			$deleteMsgCont.html('Are you sure you want to delete album: <br>' + '<span>' + albumName + '</span>');
		} else if ($(e.target).is('.album-form')) {
			var albumName = $('input[name="albumName"]').val();
			$deleteMsgCont.html('Are you sure you want to delete album: <br>' + '<span>' + albumName + '</span>');
		} else if ($(e.target).is('.delete-artist')) {
			var artistName = $('input[name="artistName"]').val();
			$deleteMsgCont.html('Are you sure you want to delete artist: <br>' + '<span>' + artistName + '</span>');
		}
		$deleteAlrtCont.show();
	}
});