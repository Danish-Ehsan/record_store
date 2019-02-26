<?php

require_once ('../../data/util/main.php');
require_once('data/util/validate_form.php');
require_once('data/util/valid_admin.php');
require_once ('data/model/database.php');
require_once ('data/model/admin_db.php');
require_once ('data/model/product_db.php');

include('admin/view/header.php');

$action = filter_input(INPUT_GET, 'action');
$message = '';

if (!$action) {
    $action = filter_input(INPUT_POST, 'action');
    if (!$action) {
        $action = 'viewAllAlbums';
    }
}

switch ($action) {
    case 'viewAllAlbums':
        $albums = getAllAlbums();
        include('admin/albums/admin_view_albums.php');
        break;
    
    case 'viewFeatured':
        $albums = getFeatured();
        if (isset($albums['errors'])) {
            $error = $albums['errors'];
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
        include('admin/albums/admin_view_featured.php');
        break;
    
    case 'viewAlbumsByArtist':
        $artistID = filter_input(INPUT_GET, 'artistID', FILTER_VALIDATE_INT);
        $albums = getAlbumsByArtist($artistID);
        $artist = getArtistByID($artistID);
        include('admin/albums/admin_artist_albums.php');
        break;
    
    case 'viewAlbum':
        $albumID = filter_input(INPUT_GET, 'albumID', FILTER_VALIDATE_INT);
        $album = getAlbumByID($albumID);
        $artistID = $album['artistID'];
        $songs = getSongsByAlbum($albumID);
        $artists = getArtists();
        $featured = filter_input(INPUT_POST, 'featured') ? true : false;
        include ('admin/albums/admin_edit_album.php');
        break;
    
    case 'viewAddAlbum':
        $artists = getArtists();
        $artistID = filter_input(INPUT_GET, 'artistID', FILTER_VALIDATE_INT);
        include ('admin/albums/admin_add_album.php');
        break;
    
    case 'addAlbum':
        $albumName = filter_input(INPUT_POST, 'albumName');
        $artistID = filter_input(INPUT_POST, 'artistID');
        $price = filter_input(INPUT_POST, 'price');
        $year = filter_input(INPUT_POST, 'year');
        $label = filter_input(INPUT_POST, 'label');
        $featured = filter_input(INPUT_POST, 'featured') ? true : false;
        $songs = $_POST['songs'];
        
        //Form Validation
        $albumNameValid = validate('text', $albumName, false, 255, 1, true);
        $priceValid = validate('price', $price, false, 10, 1, true);
        $yearValid = validate('number', $year, false, 4, 4, true);
        $labelValid = validate('text', $label, false, 80, 1, true);
        $songsValid = array();
        $allSongsValid = true;
        
        foreach ($songs as $song) {
            $songValid = validate('text', $song, false, 255, 1, true);
            $songsValid[] = $songValid;
            if ($songValid !== true && $allSongsValid) { $allSongsValid = false; }
        }
        
        $formValid = ($albumNameValid === true && $priceValid === true && $yearValid === true && $labelValid === true && $allSongsValid === true);
        
        //if validation errors exists exit script
        if ($formValid !== true) {
            $artists = getArtists();
            include ('admin/albums/admin_add_album.php');
            break;
        }
        
        $albumID = addAlbum($artistID, $albumName, $year, $label, $price, $featured);
        
        foreach ($songs as $key => $value) {
            addSong(($key + 1), $albumID, $value);
        }
        
        $artistName = getArtistByID($artistID)['artistName'];
        $imageSrc = $includePath . createImageLink($artistName, $albumName);
        move_uploaded_file($_FILES['albumCover']['tmp_name'], $imageSrc);
        
        $songs = getSongsByAlbum($albumID);
        $album = getAlbumByID($albumID);
        $artists = getArtists();
        $message = 'Album added successfully';
        include('admin/albums/admin_edit_album.php');
        break;
        
    case 'updateAlbum':
        $newAlbumName = filter_input(INPUT_POST, 'albumName');
        $albumID = filter_input(INPUT_POST, 'albumID');
        $artistID = filter_input(INPUT_POST, 'artistID');
        $artistName = getArtistByID($artistID)['artistName'];
        $newPrice = filter_input(INPUT_POST, 'price');
        $newYear = filter_input(INPUT_POST, 'year');
        $newLabel = filter_input(INPUT_POST, 'label');
        $newFeatured = filter_input(INPUT_POST, 'featured') ? true : false;
        $newSongs = $_POST['songs'];
        
        //Form validation
        $albumNameValid = validate('text', $newAlbumName, false, 255, 1, true);
        $priceValid = validate('price', $newPrice, false, 10, 1, true);
        $yearValid = validate('number', $newYear, false, 4, 4, true);
        $labelValid = validate('text', $newLabel, false, 80, 1, true);
        $songsValid = array();
        $allSongsValid = true;
        
        foreach ($newSongs as $newSong) {
            $songValid = validate('text', $newSong, false, 255, 1, true);
            $songsValid[] = $songValid;
            if ($songValid !== true && $allSongsValid) { $allSongsValid = false; }
        }
        
        $formValid = ($albumNameValid === true && $priceValid === true && $yearValid === true && $labelValid === true && $allSongsValid === true);
        
        //if validation errors exists exit script
        if ($formValid !== true) {
            $artists = getArtists();
            include ('admin/albums/admin_edit_album.php');
            break;
        }
        
        $oldAlbumInfo = getAlbumByID($albumID);
        $oldAlbumName = $oldAlbumInfo['albumName'];
        $oldAristName = getArtistByID($oldAlbumInfo['artistID'])['artistName'];
        
        //if new image isn't uploaded, update the filename of the current image
        if (!isset($_FILES['albumCover'])) {
            $oldImageSrc = $includePath . createImageLink($oldAristName, $oldAlbumName);
            $newImageSrc = $includePath . createImageLink($artistName, $newAlbumName);
            if (file_exists($oldImageSrc)) {
                rename($oldImageSrc, $newImageSrc);
            }
        } elseif ($_FILES['albumCover']['type'] == 'image/jpeg') {
            $newImageSrc = $includePath . createImageLink($artistName, $newAlbumName);
            move_uploaded_file($_FILES['albumCover']['tmp_name'], $newImageSrc);
        }
        updateAlbum($newAlbumName, $albumID, $artistID, $newPrice, $newYear, $newLabel, $newFeatured);

        //update album songs
        deleteAlbumSongs($albumID);
        foreach ($newSongs as $key => $value) {
            addSong(($key + 1), $albumID, $value);
        }
        
        $songs = getSongsByAlbum($albumID);
        $album = getAlbumByID($albumID);
        $artists = getArtists();
        $message = 'Album update successful';
        include('admin/albums/admin_edit_album.php');
        break;
    
    case 'deleteAlbum':
        $albumID = filter_input(INPUT_POST, 'albumID');
        $artistID = getAlbumByID($albumID)['artistID'];
        deleteAlbum($albumID);
        
        $artist = getArtistByID($artistID);
        $albums = getAlbumsByArtist($artistID);
        $albumCount = artistAlbumCount($artistID);
        $message = 'Album successfully deleted';
        
        include ('admin/artists/admin_edit_artist.php');
        break;
    
    default:
        $albums = getAllAlbums();
        include('admin/albums/admin_view_albums.php');
        break;
}



include ('admin/view/footer.php');