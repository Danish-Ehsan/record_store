<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="admins-list-cont">
            <h2>Admin Accounts</h2>
            <ul id="admins-list">
                <?php 
                    foreach ($admins as $admin) :?>
                        <li><a href="<?php echo '?action=viewAdmin&adminID=' . $admin['adminID']; ?>"><?php echo $admin['firstName'] . ' ' . $admin['lastName']; ?></a></li>
                    <?php endforeach; ?>
                        
                    <form action="." method="post" class="inline-button">
                        <input type="hidden" name="action" value="showAddForm">
                        <input type="submit" value="Add Admin" class="submit-button admin-form" id="add-button">
                    </form>
            </ul>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->