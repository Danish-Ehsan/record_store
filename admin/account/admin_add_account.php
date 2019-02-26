<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="admins-list-cont">
            <h2>Add Admin</h2>
            <form action="." method="post">
                <input type="hidden" name="action" value="addAdmin">
                
                <label><h3>Admin Info:</h3></label><br>
                
                <label>First Name:</label>
                <input type="text" name="firstName" value="<?php if (isset($firstName)) { echo $firstName; } ?>">
                <span class="input-error<?php if (isset($firstNameValid) && $firstNameValid !== true) { echo ' active'; } ?>"><?php if (isset($firstNameValid) && $firstNameValid !== true) { echo $firstNameValid; } ?></span><br>
                
                <label>Last Name:</label>
                <input type="text" name="lastName" value="<?php if (isset($lastName)) { echo $lastName; } ?>">
                <span class="input-error<?php if (isset($lastNameValid) && $lastNameValid !== true) { echo ' active'; } ?>"><?php if (isset($lastNameValid) && $lastNameValid !== true) { echo $lastNameValid; } ?></span><br>
                
                <label>email:</label>
                <input type="text" name="emailAddress" value="<?php if (isset($emailAddress)) { echo $emailAddress; } ?>">
                <span class="input-error<?php if (isset($emailAddressValid) && $emailAddressValid !== true) { echo ' active'; } ?>"><?php if (isset($emailAddressValid) && $emailAddressValid !== true) { echo $emailAddressValid; } ?></span><br>
                
                <label>password:</label>
                <input type="password" name="password">
                <span class="input-error<?php if (isset($passwordValid) && $passwordValid !== true) { echo ' active'; } ?>"><?php if (isset($passwordValid) && $passwordValid !== true) { echo $passwordValid; } ?></span><br>
                
                <label>confirm password:</label>
                <input type="password" name="confirmPassword">
                <span class="input-error"></span><br>
                
                <label><h3>Permissions:</h3></label><br>
                
                <label>Add/Edit Items:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="updateItems" class="checkbox" value="true">
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <label>Delete Items:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="deleteItems" class="checkbox" value="true">
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <label>Edit Admins:</label>
                <label class="checkbox-label">
                    <input type="checkbox" name="updateAdmins" class="checkbox" value="true">
                    <span class="custom-checkbox"></span>
                </label><br>
                
                <input type="submit" value="Save" class="submit-button">
            </form>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->