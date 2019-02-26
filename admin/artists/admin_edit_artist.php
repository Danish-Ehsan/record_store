<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2>Edit Artist</h2>
            <form action="." method="post">
                <input type="hidden" name="action" value="updateArtist">
                <input type="hidden" name="artistID" value="<?php echo $artist['artistID']; ?>">
                
                <label><h3>Artist Info:</h3></label><br>
                
                <label>Artist Name:</label>
                <input type="text" name="artistName" value="<?php echo $artist['artistName']; ?>"><br>
                <span class="input-error<?php if (isset($artistNameValid) && $artistNameValid !== true) { echo ' active'; } ?>"><?php if (isset($artistNameValid) && $artistNameValid !== true) { echo $artistNameValid; } ?></span><br>
                
                <label>&nbsp;</label>
                <input type="submit" value="Save" class="submit-button artist-form">
            </form>
            <?php if ($_SESSION['admin']['permissions']['deleteItems']) : ?>
            <form action="." method="post">
                <input type="hidden" name="action" value="deleteArtist">
                <input type="hidden" name="artistID" value="<?php echo $artist['artistID']; ?>">
                <input type="submit" value="Delete Artist" class="submit-button delete-button delete-artist"
                       <?php if ($albumCount > 0) echo 'disabled'; ?>
                >
            </form>
            <?php endif; ?>
            
            <h3 id="album-subhead">Albums</h3>
            <ul class="album-list artist-form">
            <?php foreach ($albums as $album) : ?>
                <li><a href="<?php echo $appPath; ?>admin/albums?action=viewAlbum&albumID=<?php echo $album['albumID']; ?>"><?php echo $album['albumName']; ?></a></li>
                <?php if ($_SESSION['admin']['permissions']['deleteItems']) : ?>
                <form action="<?php echo $appPath; ?>admin/albums/index.php" method="post" class="inline-delete">
                    <input type="hidden" name="action" value="deleteAlbum">
                    <input type="hidden" name="albumID" value="<?php echo $album['albumID']; ?>">
                    <input type="submit" value="Delete" class="submit-button delete-button artist-form">
                </form>
                <?php endif; ?>
                <br>
            <?php endforeach; ?>
                <li><form action="<?php echo $appPath; ?>admin/albums/index.php" method="get" class="inline-button">
                        <input type="hidden" name="action" value="viewAddAlbum">
                        <input type="hidden" name="artistID" value="<?php echo $artist['artistID']; ?>">
                        <input type="submit" value="Add Album" class="submit-button artist-form" id="add-button">
                    </form></li>
            </ul>
            <p><?php echo $message;?></p>
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
<script type="text/javascript" src="<?php echo $appPath; ?>js/admin_functions.js"></script>