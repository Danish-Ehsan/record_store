<?php

function getAdminName() {
    global $db;
    $query = 'SELECT firstName, lastName
              FROM admins';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function checkValidAdmin($email, $password) {
    global $db;
    $password = sha1($email . $password);
    $query = 'SELECT *
              FROM admins
              WHERE emailAddress = :email AND password = :password';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $password);
        $statement->execute();
        $valid = ($statement->rowCount() == 1);
        $statement->closeCursor();
        return $valid;
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function getAdminByEmail($email) {
    global $db;
    $query = 'SELECT adminID, emailAddress, firstName, lastName
              FROM admins
              WHERE emailAddress = :email';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function getAdminByID($adminID) {
    global $db;
    $query = 'SELECT adminID, emailAddress, firstName, lastName
              FROM admins
              WHERE adminID = :adminID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':adminID', $adminID);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function getAllAdmins() {
    global $db;
    $query = 'SELECT adminID, emailAddress, firstName, lastName
              FROM admins';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function getAdminPermissions($adminID) {
    global $db;
    $query = 'SELECT updateItems, deleteItems, updateAdmins
              FROM adminPermissions
              WHERE adminID = :adminID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':adminID', $adminID);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
            
}

function addAdmin($firstName, $lastName, $password, $emailAddress) {
    global $db;
    $query = 'INSERT INTO admins (firstName, lastName, password, emailAddress)
              VALUES (:firstName, :lastName, :password, :emailAddress)';
    try {
        if ($_SESSION['admin']['permissions']['updateAdmins']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':firstName', $firstName);
            $statement->bindValue(':lastName', $lastName);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':emailAddress', $emailAddress);
            $statement->execute();
            $statement->closeCursor();
            $adminID = $db->lastInsertID();
            return $adminID;
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to add admins.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function addAdminPermissions($adminID, $permissions) {
    global $db;
    $query = 'INSERT INTO adminPermissions (adminID, updateItems, deleteItems, updateAdmins)
              VALUES (:adminID, :updateItems, :deleteItems, :updateAdmins)';
    try {
        if ($_SESSION['admin']['permissions']['updateAdmins']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':adminID', $adminID);
            $statement->bindValue(':updateItems', $permissions['updateItems']);
            $statement->bindValue(':deleteItems', $permissions['deleteItems']);
            $statement->bindValue(':updateAdmins', $permissions['updateAdmins']);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to add admins.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function updateAdmin($adminID, $firstName, $lastName, $password, $emailAddress) {
    global $db;
    $query = 'UPDATE admins
              SET
                firstName = :firstName,
                lastName = :lastName,
                emailAddress = :emailAddress,
                password = :password
              WHERE adminID = :adminID';
    try {
        if ($_SESSION['admin']['permissions']['updateAdmins']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':adminID', $adminID);
            $statement->bindValue(':firstName', $firstName);
            $statement->bindValue(':lastName', $lastName);
            $statement->bindValue(':emailAddress', $emailAddress);
            $statement->bindValue(':password', $password);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to update admins.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function updateAdminPermissions($adminID, $permissions) {
    global $db;
    $query = 'UPDATE adminPermissions
              SET
                updateItems = :updateItems,
                deleteItems = :deleteItems,
                updateAdmins = :updateAdmins
              WHERE adminID = :adminID';
    try {
        if ($_SESSION['admin']['permissions']['updateAdmins']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':adminID', $adminID);
            $statement->bindValue(':updateItems', $permissions['updateItems']);
            $statement->bindValue(':deleteItems', $permissions['deleteItems']);
            $statement->bindValue(':updateAdmins', $permissions['updateAdmins']);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to update admins.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function deleteAdmin($adminID) {
    global $db;
    $query = 'DELETE FROM admins
              WHERE adminID = :adminID';
    try {
        if ($_SESSION['admin']['permissions']['updateAdmins']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':adminID', $adminID);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to delete admins.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function deleteAdminPermissions($adminID) {
    global $db;
    $query = 'DELETE FROM adminPermissions
              WHERE adminID = :adminID';
    try {
        if ($_SESSION['admin']['permissions']['updateAdmins']) {
            $statement = $db->prepare($query);
            $statement->bindValue(':adminID', $adminID);
            $statement->execute();
            $statement->closeCursor();
        } else {
            global $appPath;
            $error_message = "This account doesn't have permission to delete admins.";
            include ('admin/errors/admin_display_error.php');
            include ('admin/view/footer.php');
            exit();
        }
    } catch (PDOException $e) {
        global $appPath;
        $error_message = $e->getMessage();
        include ('admin/errors/admin_display_error.php');
        include ('admin/view/footer.php');
        exit();
    }
}

function logout() {
    $_SESSION = array();
    session_destroy();
}