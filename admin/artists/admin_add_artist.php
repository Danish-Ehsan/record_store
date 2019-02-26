<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2>Add Artist</h2>
            <form action="." method="post">
                <input type="hidden" name="action" value="addArtist">

                <label>Artist Name:</label>
                <input type="text" name="artistName" value="<?php if (isset($newArtistName)) { echo $newArtistName; } ?>"><br>
                <span class="input-error<?php if (isset($artistNameValid) && $artistNameValid !== true) { echo ' active'; } ?>"><?php if (isset($artistNameValid) && $artistNameValid !== true) { echo $artistNameValid; } ?></span><br>
                
                <label>&nbsp;</label>
                <input type="submit" value="Save" class="submit-button artist-form">
            </form>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->