<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="admins-list-cont">
            <h2>Edit Admin</h2>
            <form action="." method="post">
                <input type="hidden" name="action" value="updateAdmin">
                <input type="hidden" name="adminID" value="<?php echo $adminID; ?>">
                
                <label><h3>Admin Info:</h3></label><br>
                
                <label>First Name:</label>
                <input type="text" name="firstName" value="<?php echo $admin['firstName']; ?>">
                <span class="input-error"></span><br>
                
                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?php echo $admin['lastName']; ?>">
                <span class="input-error"></span><br>
                
                <label>email:</label>
                <input type="text" name="emailAddress" value="<?php echo $admin['emailAddress']; ?>">
                <span class="input-error"></span><br>
                
                <label>password:</label>
                <input type="password" name="password">
                <span class="input-error"></span><br>
                
                <label>confirm password:</label>
                <input type="password" name="confirmPassword">
                <span class="input-error"></span><br>
                
                <label><h3>Permissions:</h3></label><br>
                
                <label>Add/Edit Items:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="updateItems" class="checkbox" value="true" <?php if ($adminPermissions['updateItems']) echo 'checked'; ?>>
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <label>Delete Items:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="deleteItems" class="checkbox" value="true" <?php if ($adminPermissions['deleteItems']) echo 'checked'; ?>>
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <label>Update Admins:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="updateAdmins" class="checkbox" value="true" <?php if ($adminPermissions['updateAdmins']) echo 'checked'; ?>>
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <input type="submit" value="Save" class="submit-button">
            </form>
            <form action="." method="post" class="inline-delete">
                <input type="hidden" name="action" value="deleteAdmin">
                <input type="hidden" name="adminID" value="<?php echo $admin['adminID']; ?>">
                <input type="submit" value="Delete Admin" class="submit-button delete-button album-form">
            </form><br>
            <p><?php echo $message ?></p>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->