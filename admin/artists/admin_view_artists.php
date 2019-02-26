<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2>Artists</h2>
            <ul id="artists-list">
                <?php 
                    foreach ($artists as $artist) :?>
                        <li><a href="<?php echo '?action=viewArtist&artistID=' . $artist['artistID']; ?>"><?php echo $artist['artistName'] ?></a></li>
                    <?php endforeach; ?>
            </ul>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->

