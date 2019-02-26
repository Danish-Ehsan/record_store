<?php

require_once ('../../data/util/main.php');
require_once('data/util/validate_form.php');
require_once ('data/model/database.php');
require_once ('data/model/admin_db.php');
require_once ('data/model/product_db.php');

include('admin/view/header.php');

$action = filter_input(INPUT_GET, 'action');
$message = '';

if (!$action) {
    $action = filter_input(INPUT_POST, 'action');
    if (!$action) {
        $action = 'viewLogin';
    }
}

switch ($action) {
    case 'viewLogin':
        include('admin/account/admin_login.php');
        break;
    
    case 'viewAccounts':
        $admins = getAllAdmins();
        include('admin/account/admin_all_accounts.php');
        break;
    
    case 'viewAdmin':
        $adminID = filter_input(INPUT_GET, 'adminID');
        $admin = getAdminByID($adminID);
        $adminPermissions = getAdminPermissions($adminID);
        include('admin/account/admin_view_account.php');
        break;
    
    case 'showAddForm':
        include('admin/account/admin_add_account.php');
        break;
    
    case 'addAdmin':
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $password = filter_input(INPUT_POST, 'password');
        $confirmPassword = filter_input(INPUT_POST, 'confirmPassword');
        $emailAddress = filter_input(INPUT_POST, 'emailAddress');    
        
        //Form Validation
        $firstNameValid = validate('name', $firstName, false, 60, 2, true);
        //echo 'firstNameValid: ' . $firstNameValid;
        $lastNameValid = validate('name', $lastName, false, 60, 2, true);
        $emailAddressValid = validate('email', $emailAddress, false, 255, 3, true);
        $passwordValid = validate('password', $password, $confirmPassword, 20, 2, true);
        
        $formValid = ($firstNameValid === true && $lastNameValid === true && $emailAddressValid === true && $passwordValid === true);
        //echo 'formValid: ' . $formValid;
        
        if ($formValid !== true) {
            include('admin/account/admin_add_account.php');
            break;
        }
        
        $password = sha1($emailAddress . $password);
        
        $permissions = array();
        $permissions['updateItems'] = filter_input(INPUT_POST, 'updateItems') ? true : false;
        $permissions['deleteItems'] = filter_input(INPUT_POST, 'deleteItems') ? true : false;
        $permissions['updateAdmins'] = filter_input(INPUT_POST, 'updateAdmins') ? true : false;
        
        $adminID = addAdmin($firstName, $lastName, $password, $emailAddress);
        addAdminPermissions($adminID, $permissions);
        
        $admins = getAllAdmins();
        include('admin/account/admin_all_accounts.php');
        break;
    
    case 'updateAdmin':
        $adminID = filter_input(INPUT_POST, 'adminID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $password = filter_input(INPUT_POST, 'password');
        $emailAddress = filter_input(INPUT_POST, 'emailAddress');
        $password = sha1($emailAddress . $password);
        
        $permissions = array();
        $permissions['updateItems'] = filter_input(INPUT_POST, 'updateItems') ? true : false;
        $permissions['deleteItems'] = filter_input(INPUT_POST, 'deleteItems') ? true : false;
        $permissions['updateAdmins'] = filter_input(INPUT_POST, 'updateAdmins') ? true : false;
        
        updateAdmin($adminID, $firstName, $lastName, $password, $emailAddress);
        updateAdminPermissions($adminID, $permissions);
        
        $message = 'Admin updated successfully';
        $admin = getAdminByID($adminID);
        $adminPermissions = getAdminPermissions($adminID);
        
        if ($admin['adminID'] == $_SESSION['admin']['adminID']) {
            $_SESSION['admin'] = $admin;
            $_SESSION['admin']['permissions'] = $adminPermissions;
        }
        
        include('admin/account/admin_view_account.php');
        break;
    
    case 'deleteAdmin':
        $adminID = filter_input(INPUT_POST, 'adminID');
        deleteAdmin($adminID);
        deleteAdminPermissions($adminID);
        if ($_SESSION['admin']['adminID'] == $adminID) {
            logout();
            header('Location: ' . $appPath . 'admin');
        } else {
            $admins = getAllAdmins();
            include('admin/account/admin_all_accounts.php');
        }
        break;
        
    case 'login':
        $email = filter_input(INPUT_POST, 'loginEmail');
        $password = filter_input(INPUT_POST, 'loginPassword');
        $validAdmin = checkValidAdmin($email, $password);
        //echo $validAdmin;
        
        if ($validAdmin) {
            $_SESSION['admin'] = getAdminByEmail($email);
            $_SESSION['admin']['permissions'] = getAdminPermissions($_SESSION['admin']['adminID']);
            header('Location: ' . $appPath . 'admin');
        } else {
            $message = 'Invalid Email or Password';
            include ('admin/account/admin_login.php');
            echo $validAdmin;
        }
        break;
        
    case 'logout':
        logout();
        header('Location: ' . $appPath . 'admin');
        break;
        
    default:
        include('admin/account/admin_login.php');
        break;
}

include ('admin/view/footer.php');