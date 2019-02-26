<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="artists-list-cont">
            <h2>Admin Login</h2>
            <form action="." method="post">
                <input type="hidden" name="action" value="login">

                <label>Email:</label>
                <input type="text" name="loginEmail"><br>
                
                <label>Password:</label>
                <input type="password" name="loginPassword"><br>
                
                <label>&nbsp;</label>
                <input type="submit" value="Login" class="submit-button artist-form">
            </form>
            <p><?php echo $message; ?></p>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->