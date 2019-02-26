<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2><?php echo $artist['artistName']; ?></h2>
            <ul id="albums-list">
                <?php 
                    foreach ($albums as $album) :?>
                        <li><a href="<?php echo '?action=viewAlbum&albumID=' . $album['albumID']; ?>"><?php echo $album['albumName'] ?></a></li>
                    <?php endforeach; ?>
            </ul>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->