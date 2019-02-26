<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2>Add Album</h2>
            <form action="." method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="addAlbum">
                
                <label><h3>Album Info:</h3></label><br>
                
                <label>Name:</label>
                <input type="text" name="albumName" value="<?php if (isset($albumName)) { echo $albumName; } ?>">
                <span class="input-error<?php if (isset($albumNameValid) && $albumNameValid !== true) { echo ' active'; } ?>"><?php if (isset($albumNameValid) && $albumNameValid !== true) { echo $albumNameValid; } ?></span><br>

                <label>Artist:</label>
                <select name="artistID">
                    <?php foreach ($artists as $artist) {
                        $option = "<option value=$artist[artistID]";
                        if ($artistID == $artist['artistID']) $option = $option . ' selected';
                        $option = $option . ">$artist[artistName]</option>";
                        echo $option;
                    } ?>
                </select><br>
                
                <label>Price:</label>
                <input type="text" name="price" value="<?php if (isset($price)) { echo $price; } ?>">
                <span class="input-error<?php if (isset($priceValid) && $priceValid !== true) { echo ' active'; } ?>"><?php if (isset($priceValid) && $priceValid !== true) { echo $priceValid; } ?></span><br>

                <label>Year:</label>
                <input type="text" name="year" value="<?php if (isset($year)) { echo $year; } ?>">
                <span class="input-error<?php if (isset($yearValid) && $yearValid !== true) { echo ' active'; } ?>"><?php if (isset($yearValid) && $yearValid !== true) { echo $yearValid; } ?></span><br>

                <label>Record Label:</label>
                <input type="text" name="label" value="<?php if (isset($label)) { echo $label; } ?>">
                <span class="input-error<?php if (isset($labelValid) && $labelValid !== true) { echo ' active'; } ?>"><?php if (isset($labelValid) && $labelValid !== true) { echo $labelValid; } ?></span><br>

                <label>Album Cover:</label>
                <input type="file" name="albumCover"><br>
                
                <label>Featured:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="featured" class="checkbox" value="true" <?php if (isset($featured) && $featured === true) { echo 'checked'; } ?>>
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <label id="tracklist-label"><h3>Tracklist:</h3></label><br>
                <?php
                    $songsCount = isset($songs) ? count($songs) : 7;
                    for ($i = 0; $i < $songsCount; $i++) : 
                ?>
                    <div class="tracklist">
                        <label>&nbsp;</label>
                        <span class="tracklist-number"><?php echo ($i + 1) . '. '; ?></span>
                        <input type="text" name="songs[]" value="<?php if (isset($songs)) { echo $songs[$i]; } ?>">
                        <img class="add-icon" src="<?php echo $appPath ?>images/delete_icon.png">
                        <img class="delete-icon" src="<?php echo $appPath ?>images/delete_icon.png">
                        <span class="input-error<?php if (isset($songsValid[$i]) && $songsValid[$i] !== true) { echo ' active'; } ?>"><?php if (isset($songsValid[$i]) && $songsValid[$i] !== true) { echo $songsValid[$i]; } ?></span><br>
                    </div>
                <?php endfor; ?>
                <input type="submit" value="Save" class="submit-button">
            </form>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->
<script type="text/javascript" src="<?php echo $appPath ?>js/admin_functions.js"></script>