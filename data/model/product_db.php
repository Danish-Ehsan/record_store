<?php

function getAllAlbums() {
    global $db;
    $query = 'SELECT *
              FROM albums';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getFeatured() {
    global $db;
    $query = 'SELECT albums.albumID, albums.albumName, artists.artistName, albums.year, albums.price
              FROM albums
              INNER JOIN artists ON albums.artistID = artists.artistID
              WHERE albums.featured = TRUE';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getSongsByAlbum($albumID) {
    global $db;
    $query = 'SELECT *
              FROM songs
              WHERE albumID = :albumID
              ORDER BY songSequence';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':albumID', $albumID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getArtists() {
    global $db;
    $query = 'SELECT *
              FROM artists
              ORDER BY artistName';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getAlbumsByArtist($artistID) {
    global $db;
    $query = 'SELECT albums.albumID, albums.albumName, artists.artistName, albums.year, albums.price
              FROM albums
              INNER JOIN artists ON albums.artistID = artists.artistID
              WHERE albums.artistID = :artistID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':artistID', $artistID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getArtistByID($artistID) {
    global $db;
    $query = 'SELECT *
              FROM artists
              WHERE artistID = :artistID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':artistID', $artistID);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getAlbumByID($albumID) {
    global $db;
    $query = 'SELECT *
              FROM albums
              WHERE albumID = :albumID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':albumID', $albumID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        //echo print_r($result);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function formatAlbums(&$albums) {
    function format(&$album) {
        $album['songs'] = array();
        $imageSrc = createImageLink($album['artistName'], $album['albumName']);
        if (!file_exists('../' . $imageSrc)) {
            $imageSrc = 'images/missing_image.jpg';
        }
        //echo $imageSrc . '<br>';
        $album['image'] = $imageSrc;
        $songs = getSongsByAlbum($album['albumID']);
        foreach ($songs as $song) {
            $album['songs'][] = $song['songName'];
        }
    }
    
    //if argument is array of albums loop through each. Otherwise format single album
    if (isset($albums[0]) && gettype($albums[0]) == 'array') {
        foreach ($albums as &$album) {
            format($album);
        }
    } else {
        format($albums);
    }
}

function createImageLink($artistName, $albumName) {
    $artistNameForm = preg_replace("/[^A-Za-z0-9 ]/", '', $artistName);
    $artistNameForm = preg_replace('/\s+/', ' ',$artistNameForm);
    $artistNameForm = trim($artistNameForm);
    $albumNameForm = preg_replace("/[^A-Za-z0-9 ]/", '', $albumName);
    $albumNameForm = preg_replace('/\s+/', ' ',$albumNameForm);
    $albumNameForm = trim($albumNameForm);
    $imageSrc = 'images/album_covers/' . str_replace(' ', '_', $artistNameForm) . '_' . str_replace(' ', '_', $albumNameForm) . '.jpg';
    return $imageSrc;
}
function addAlbum($artistID, $albumName, $year, $label, $price, $featured) {
    global $db;
    $query = 'INSERT INTO albums (artistID, albumName, year, label, price, featured)
              VALUES (:artistID, :albumName, :year, :label, :price, :featured)';
    try {
        if ($_SESSION['admin']['permissions']['updateItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':artistID', $artistID);
            $statement->bindValue(':albumName', $albumName);
            $statement->bindValue(':year', $year);
            $statement->bindValue(':label', $label);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':featured', $featured);
            $statement->execute();
            $statement->closeCursor();
            $albumID = $db->lastInsertID();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to add albums.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
        return $albumID;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function updateAlbum($albumName, $albumID, $artistID, $price, $year, $label, $featured) {
    global $db;
    $query = 'UPDATE albums
              SET 
                albumName = :albumName, 
                artistID = :artistID, 
                price = :price,
                year = :year,
                label = :label,
                featured = :featured
              WHERE albumID = :albumID';
    try {
        if ($_SESSION['admin']['permissions']['updateItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':albumName', $albumName);
            $statement->bindValue(':artistID', $artistID);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':year', $year);
            $statement->bindValue(':label', $label);
            $statement->bindValue(':albumID', $albumID);
            $statement->bindValue(':featured', $featured);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to update albums.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function deleteAlbum($albumID) {
    global $db;  
    deleteAlbumSongs($albumID);
    
    $query = 'DELETE FROM albums
              WHERE albumID = :albumID';
    try {
        if ($_SESSION['admin']['permissions']['deleteItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':albumID', $albumID);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to delete albums.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function deleteAlbumSongs($albumID) {
    global $db;
    $query = 'DELETE FROM songs
              WHERE albumID = :albumID';
    try {
        if ($_SESSION['admin']['permissions']['updateItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':albumID', $albumID);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to update albums.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function addSong($key, $albumID, $songName) {
    global $db;
    $query = 'INSERT INTO songs (songSequence, albumID, songName)
              VALUES (:key, :albumID, :songName)';
    try {
        if ($_SESSION['admin']['permissions']['updateItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':key', $key);
            $statement->bindValue(':albumID', $albumID);
            $statement->bindValue(':songName', $songName);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to update albums.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function addArtist($artistName) {
    global $db;
    $query = 'INSERT INTO artists (artistName)
              VALUES (:artistName)';
    try {
        if ($_SESSION['admin']['permissions']['updateItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':artistName', $artistName);
            $statement->execute();
            $statement->closeCursor();
            $artistID = $db->lastInsertID();
            return $artistID;
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to add artists.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }   
}

function updateArtist($artistID, $artistName) {
    global $db;
    $query = 'UPDATE artists
              SET artistName = :artistName
              WHERE artistID = :artistID';
    try {
        if ($_SESSION['admin']['permissions']['updateItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':artistID', $artistID);
            $statement->bindValue(':artistName', $artistName);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to update artists.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function artistAlbumCount($artistID) {
    global $db;
    $query = 'SELECT COUNT(albumID) AS albumCount
              FROM albums
              WHERE artistID = :artistID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':artistID', $artistID);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function allAlbumsCount() {
    global $db;
    $query ='SELECT COUNT(albumID) AS albumCount
             FROM albums';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function deleteArtist($artistID) {
    global $db;
    $query = 'DELETE FROM artists
              WHERE artistID = :artistID';
    try {
        if ($_SESSION['admin']['permissions']['deleteItems']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':artistID', $artistID);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to delete artists.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function searchAlbums($searchVal) {
    global $db;
    $query = 'SELECT *
              FROM albums
              WHERE albumName LIKE :searchVal';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(":searchVal", '%'.$searchVal.'%');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function searchArtists($searchVal) {
    global $db;
    $query = 'SELECT *
              FROM artists
              WHERE artistName LIKE :searchVal';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(":searchVal", '%'.$searchVal.'%');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function searchSongs($searchVal) {
    global $db;
    $query = 'SELECT *
              FROM songs
              WHERE songName LIKE :searchVal';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(":searchVal", '%'.$searchVal.'%');
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}