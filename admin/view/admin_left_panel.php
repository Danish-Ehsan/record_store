<div id="left-panel">
    <div id="logo">
        <a href="<?php echo $appPath ?>admin"><img src="<?php echo $appPath ?>images/rstore_logo.png"></a>
    </div>
    <ul id="admin-menu">
        <li><a href="<?php echo $appPath ?>admin/artists">View Artists</a></li>
        <li><a href="<?php echo $appPath ?>admin/albums">View Albums</a></li>
        <li><a href="<?php echo $appPath ?>admin/albums?action=viewFeatured">View Featured</a></li>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin']['permissions']['updateItems']) : ?>
        <li><a href="<?php echo $appPath ?>admin/artists?action=viewAddArtist">Add Artist</a></li>
        <li><a href="<?php echo $appPath ?>admin/albums?action=viewAddAlbum">Add Album</a></li>
        <li><a href="<?php echo $appPath ?>admin/orders?action=viewAllOrders">View All Orders</a></li>
        <li><a href="<?php echo $appPath ?>admin/orders?action=viewUnshipped">View Unshipped orders</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['admin'])) : ?>
            <?php if ($_SESSION['admin']['permissions']['updateAdmins']) : ?>
            <li><a href="<?php echo $appPath ?>admin/account?action=viewAccounts">View Admins</a></li>
            <?php endif; ?>
        <li><a href="<?php echo $appPath ?>admin/account?action=logout">Logout</a></li>
        <?php endif; ?>
    </ul>
</div><!--#left-panel-->