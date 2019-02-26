<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>
    
    <?php
        $albumName = isset($newAlbumName) ? $newAlbumName : $album['albumName'];
        $price = isset($newPrice) ? $newPrice : $album['price'];
        $year = isset($newYear) ? $newYear : $album['year'];
        $label = isset($newLabel) ? $newLabel : $album['label'];
        $featured = isset($newFeatured) ? $newFeatured : $album['featured'];
        $songs = isset($newSongs) ? $newSongs : $songs;
    ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2>Edit Album</h2>
            <form action="." method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="updateAlbum">
                <input type="hidden" name="albumID" value="<?php echo $albumID; ?>">
                
                <label><h3>Album Info:</h3></label><br>
                
                <label>Name:</label>
                <input type="text" name="albumName" value="<?php echo $albumName ?>"><br>
                <span class="input-error<?php if (isset($albumNameValid) && $albumNameValid !== true) { echo ' active'; } ?>"><?php if (isset($albumNameValid) && $albumNameValid !== true) { echo $albumNameValid; } ?></span>

                <label>Artist:</label>
                <select name="artistID">
                    <?php foreach ($artists as $artist) {
                        $option = "<option value=$artist[artistID]";
                        if ($artistID == $artist['artistID']) { $option = $option . ' selected'; }
                        $option = $option . ">$artist[artistName]</option>";
                        echo $option;
                    } ?>
                </select><br>
                
                <label>Price:</label>
                <input type="text" name="price" value="<?php echo $price; ?>"><br>
                <span class="input-error<?php if (isset($priceValid) && $priceValid !== true) { echo ' active'; } ?>"><?php if (isset($priceValid) && $priceValid !== true) { echo $priceValid; } ?></span>

                <label>Year:</label>
                <input type="text" name="year" value="<?php echo $year; ?>"><br>
                <span class="input-error<?php if (isset($yearValid) && $yearValid !== true) { echo ' active'; } ?>"><?php if (isset($yearValid) && $yearValid !== true) { echo $yearValid; } ?></span>

                <label>Record Label:</label>
                <input type="text" name="label" value="<?php echo $label; ?>"><br>
                <span class="input-error<?php if (isset($labelValid) && $labelValid !== true) { echo ' active'; } ?>"><?php if (isset($labelValid) && $labelValid !== true) { echo $labelValid; } ?></span>

                <label>Album Cover:</label>
                <input type="file" name="albumCover"><br>
                
                <label>Featured:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="featured" class="checkbox" value="true" <?php if ($featured) { echo 'checked'; } ?>>
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <label id="tracklist-label"><h3>Tracklist:</h3></label><br>
                    <?php
                        $songsCount = isset($songs) ? count($songs) : 7;
                        for ($i = 0; $i < $songsCount; $i++) : ?>
                <div class="tracklist">
                    <label>&nbsp;</label>
                    <span class="tracklist-number"><?php echo ($i + 1) . '. '; ?></span>
                    <input type="text" name="songs[]" value="<?php if (isset($songs[$i]['songName'])) { echo $songs[$i]['songName']; } else { echo $songs[$i]; } ?>">
                    <img class="add-icon" src="<?php echo $appPath ?>images/delete_icon.png">
                    <img class="delete-icon" src="<?php echo $appPath ?>images/delete_icon.png">
                    <span class="input-error<?php if (isset($songsValid[$i]) && $songsValid[$i] !== true) { echo ' active'; } ?>"><?php if (isset($songsValid[$i]) && $songsValid[$i] !== true) { echo $songsValid[$i]; } ?></span><br>
                </div>
                    <?php endfor; ?>
                
                <label>&nbsp;</label>
                <input type="submit" value="Save" class="submit-button">
            </form>
            <?php if ($_SESSION['admin']['permissions']['deleteItems']) : ?>
            <form action="." method="post" class="inline-delete">
                <input type="hidden" name="action" value="deleteAlbum">
                <input type="hidden" name="albumID" value="<?php echo $album['albumID']; ?>">
                <input type="submit" value="Delete Album" class="submit-button delete-button album-form">
            </form><br>
            <?php endif; ?>
            <p><?php echo $message ?></p>
        </div>
    </div><!--#right-panel-->
    <div id="delete-alert-cont">
        <div id="delete-alert-box">
            <p id="delete-alert-message">Are you sure you want to delete: <br></p>
            <button id="alert-button-yes">Yes</button>
            <button id="alert-button-no">No</button>
        </div>
    </div>
</div><!--#container-->
<script type="text/javascript" src="<?php echo $appPath ?>js/admin_functions.js"></script>