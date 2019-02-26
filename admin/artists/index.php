<?php

require_once ('../../data/util/main.php');
require_once('data/util/valid_admin.php');
require_once('data/util/validate_form.php');
require_once ('data/model/database.php');
require_once ('data/model/admin_db.php');
require_once ('data/model/product_db.php');

include('admin/view/header.php');

$action = filter_input(INPUT_GET, 'action');
$message = '';

if (!$action) {
    $action = filter_input(INPUT_POST, 'action');
    if (!$action) {
        $action = 'viewAllArtists';
    }
}

switch ($action) {
    case 'viewAllArtists':
        $artists = getArtists();
        include('admin/artists/admin_view_artists.php');
        break;

    case 'viewArtist':
        $artistID = filter_input(INPUT_GET, 'artistID', FILTER_VALIDATE_INT);
        $artist = getArtistByID($artistID);
        $albums = getAlbumsByArtist($artistID);
        $albumCount = artistAlbumCount($artistID);
        include ('admin/artists/admin_edit_artist.php');
        break;
    
    case 'viewAddArtist':
        include ('admin/artists/admin_add_artist.php');
        break;
    
    case 'addArtist':
        $newArtistName = filter_input(INPUT_POST, 'artistName');
        
        //Validate artist name
        $artistNameValid = validate('text', $newArtistName, false, 80, 1, true);
        
        if ($artistNameValid !== true) {
            include ('admin/artists/admin_add_artist.php');
            break;
        }
        
        $artistID = addArtist($newArtistName);
        $artist = getArtistByID($artistID);
        $albums = getAlbumsByArtist($artistID);
        $albumCount = artistAlbumCount($artistID);
        
        $message = 'Artist added successfully';
        include ('admin/artists/admin_edit_artist.php');
        break;
    
    case 'updateArtist':
        $artistID = filter_input(INPUT_POST, 'artistID');
        $oldArtistName = getArtistByID($artistID)['artistName'];
        $artistName = filter_input(INPUT_POST, 'artistName');
        
        //Validate artist name
        $artistNameValid = validate('text', $artistName, false, 80, 1, true);
        
        if ($artistNameValid !== true) {
            $artist = getArtistByID($artistID);
            $albums = getAlbumsByArtist($artistID);
            $albumCount = artistAlbumCount($artistID);
            include ('admin/artists/admin_edit_artist.php');
            break;
        }
        
        updateArtist($artistID, $artistName);
        $message = 'Artist updated successfully';
        
        //change album cover file names to reflect updated artist name
        $oldArtistNameForm = preg_replace("/[^A-Za-z0-9 ]/", '', $oldArtistName);
        $oldArtistNameForm = preg_replace('/\s+/', ' ',$oldArtistNameForm);
        $oldArtistNameForm = trim($oldArtistNameForm);
        $oldArtistNameForm = str_replace(' ', '_', $oldArtistNameForm);
        //echo print_r(glob($includePath . 'images/album_covers/' . trim(str_replace(' ', '_', $oldArtistNameForm)) . '_*'));
        $oldImagePaths = glob($includePath . 'images/album_covers/' . trim(str_replace(' ', '_', $oldArtistNameForm)) . '_*');
        
        $artistNameForm = preg_replace("/[^A-Za-z0-9 ]/", '', $artistName);
        $artistNameForm = preg_replace('/\s+/', ' ',$artistNameForm);
        $artistNameForm = trim($artistNameForm);
        $artistNameForm = str_replace(' ', '_', $artistNameForm);
        
        foreach ($oldImagePaths as $imagePath) {
            $newImagePath = str_replace($oldArtistNameForm, $artistNameForm, $imagePath);
            rename($imagePath, $newImagePath);
        }
        
        $artist = getArtistByID($artistID);
        $albums = getAlbumsByArtist($artistID);
        $albumCount = artistAlbumCount($artistID);
        include ('admin/artists/admin_edit_artist.php');
        break;
        
    case 'deleteArtist':
        $artistID = filter_input(INPUT_POST, 'artistID');
        $albumCount = artistAlbumCount($artistID);
        
        if ($albumCount > 0) {
            $error_message = "You must delete all albums from the artist before deleting the artist itself.";
        } else {
            deleteArtist($artistID);
            header('Location: ?action=viewAllArtists');
        }
        break;
        
    default:
        $artists = getArtists();
        echo $action;
        //include('admin/artists/admin_view_artists.php');
        break;
}



include ('admin/view/footer.php');