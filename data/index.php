<?php

require_once ('./util/main.php');
require_once ('data/model/database.php');
require_once ('data/model/product_db.php');
require_once ('data/model/customer_db.php');

$action = filter_input(INPUT_GET, 'action');

if ($action == null) {
    $action = filter_input(INPUT_POST, 'action');
    if ($action == null) {
        $action = 'featured';
    }
}

switch ($action) {
    case 'featured':
        $albums = getFeatured();
        formatAlbums($albums);
        
        $albums = json_encode($albums);
        echo $albums;
        break;
    case 'getArtists':
        $artists = getArtists();
        $artists = json_encode($artists);
        echo $artists;
        break;
    case 'getArtistCatalog':
        $artistID = filter_input(INPUT_GET, 'artistID');
        $albums = getAlbumsByArtist($artistID);
        formatAlbums($albums);
        $albums = json_encode($albums);
        echo $albums;
        break;
    case 'getAlbumsCatalog':
        $albums = getAllAlbums();
        $albums = json_encode($albums);
        echo $albums;
        break;
    case 'getAlbum':
        $albumID = filter_input(INPUT_GET, 'albumID', FILTER_VALIDATE_INT);
        $album = getAlbumByID($albumID);
        $album['artistName'] = getArtistByID($album['artistID'])['artistName'];
        formatAlbums($album);
        $album = json_encode($album);
        echo $album;
        break;
    case 'userLogin':
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $validCustomer = checkValidCustomer($email, $password);
        
        if ($validCustomer) {
            $customer = array();
            $customer['details'] = getCustomerByEmail($email);
            //if customer has associated address add it to customer array
            if ($customer['details']['addressID'] != null) {
                $customer['details']['address'] = getAddressByID($customer['details']['addressID']);
            } else {
                //generate blank address array
                $customerAddress = array();
                $customerAddress['city'] = '';
                $customerAddress['country'] = '';
                $customerAddress['postalCode'] = '';
                $customerAddress['line1'] = '';
                $customerAddress['line2'] = '';
                $customer['details']['address'] = $customerAddress;
            }
            $orders = getOrderbyCustomerID($customer['details']['customerID']);
            //if customer has orders add them to customer array
            if (count($orders) > 0) {
                foreach($orders as &$order) {
                    $items = getItemsByOrderID($order['orderID']);
                    $albums = array();
                    foreach ($items as $item) {
                        $album = getAlbumByID($item['productID']);
                        $albumName = $album['albumName'];
                        $albumID = $item['productID'];
                        $quantity = $item['quantity'];
                        $albumForm = array( 'albumName' => $albumName, 'albumID' => $albumID, 'quantity' => $quantity );
                        $albums[] = $albumForm;
                    }
                    $address = getAddressByID($order['addressID']);
                    $order['items'] = $albums;
                    $order['address'] = $address;
                }
                $customer['orders'] = $orders;
            }
            $customer = json_encode($customer);
            echo $customer;
        } else {
            $customer = array("incorrectLogin" => true);
            $customer = json_encode($customer);
            echo $customer;
        }
        break;
    case 'checkUserExists':
        $email = filter_input(INPUT_POST, 'email');
        $valid = checkCustomerExists($email);
        echo $valid;
        break;
    case 'checkValidLogin':
        $customerID = filter_input(INPUT_POST, 'customerID');
        $password = filter_input(INPUT_POST, 'password');
        $email = getCustomerByID($customerID)['emailAddress'];
        $valid = checkValidCustomer($email, $password);
        echo $valid;
        break;
    case 'addUser':
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $password = sha1($email . $password);
        
        addCustomer($firstName, $lastName, $email, $password);
        break;
    case 'editUser':
        $customerID = filter_input(INPUT_POST, 'customerID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email');
        $loginPassword = filter_input(INPUT_POST, 'loginPassword');
        $newPassword = filter_input(INPUT_POST, 'newPassword');
        $updateAddress = filter_input(INPUT_POST, 'updateAddress');
        $city = filter_input(INPUT_POST, 'city');
        $country = filter_input(INPUT_POST, 'country');
        $postalCode = filter_input(INPUT_POST, 'postalCode');
        $addressOne = filter_input(INPUT_POST, 'addressOne');
        $addressTwo = filter_input(INPUT_POST, 'addressTwo');
        
        $customerOld = getCustomerByID($customerID);
        $emailOld = $customerOld['emailAddress'];
        
        //if email or password is changed then update password
        if ($email != $emailOld && strlen($newPassword) <= 0) {
            $newPassword = sha1($email . $loginPassword);
            updateCustomerPassword($customerID, $newPassword);
        } else if (strlen($newPassword) > 0) {
            $newPassword = sha1($email . $newPassword);
            updateCustomerPassword($customerID, $newPassword);
        }
        
        updateCustomerDetails($customerID, $firstName, $lastName, $email);
        
        //if edit info contains address info, update the database
        if ($updateAddress) {
            $addressID = saveAddress($customerID, $country, $city, $postalCode, $addressOne, $addressTwo);
            updateCustomerAddressID($customerID, $addressID);
        }
        
        //if edit info is blank (signified by $updateAddress) then check to see if customer
        //is currently associated with an address table and if so, break the relation
        if (!$updateAddress && $customerOld['addressID'] !== null) {
            $addressID = null;
            updateCustomerAddressID($customerID, $addressID);
        }
        
        //Retrieve new Customer Details
        $customer = array();
        $customer['details'] = getCustomerByEmail($email);
        if ($customer['details']['addressID'] != null) {
            $customer['details']['address'] = getAddressByID($customer['details']['addressID']);
        } else {
            //generate blank address array
            $customerAddress = array();
            $customerAddress['city'] = '';
            $customerAddress['country'] = '';
            $customerAddress['postalCode'] = '';
            $customerAddress['line1'] = '';
            $customerAddress['line2'] = '';
            $customer['details']['address'] = $customerAddress;
        }
        $customer = json_encode($customer);
        echo $customer;
        break;
    case 'placeOrder':
        $customerID = filter_input(INPUT_POST, 'customerID');
        $country = filter_input(INPUT_POST, 'country');
        $city = filter_input(INPUT_POST, 'city');
        $postalCode = filter_input(INPUT_POST, 'postalCode');
        $addressOne = filter_input(INPUT_POST, 'addressOne');
        $addressTwo = filter_input(INPUT_POST, 'addressTwo');
        $saveAddress = filter_input(INPUT_POST, 'saveAddress');
        $cardType = filter_input(INPUT_POST, 'cardType');
        $totalPrice = filter_input(INPUT_POST, 'totalPrice');
        $cart = json_decode(filter_input(INPUT_POST, 'cart'), true);
        $orderDate = date('Y-m-d');
        $result = array();
        
        if ($saveAddress === 'true') {
            //echo 'saveAddress test';
            $addressID = saveAddress($customerID, $country, $city, $postalCode, $addressOne, $addressTwo);
            updateCustomerAddressID($customerID, $addressID);
            $result['address'] = getAddressByID($addressID);
        } else {
            $addressID = saveAddressWithoutCustomer($country, $city, $postalCode, $addressOne, $addressTwo);
        }
        
        $orderID = newOrder($customerID, $addressID, $orderDate, $totalPrice, $cardType);
        
        foreach ($cart as $item) {
            newOrderItem($orderID, $item['albumID'], $item['price'], $item['quantity']);
        }
        
        //format and return new order
        $order = getOrderByID($orderID);
        $items = getItemsByOrderID($orderID);
        $albums = array();
        
        foreach ($items as $item) {
            $album = getAlbumByID($item['productID']);
            $albumName = $album['albumName'];
            $albumID = $item['productID'];
            $quantity = $item['quantity'];
            $albumForm = array( 'albumName' => $albumName, 'albumID' => $albumID, 'quantity' => $quantity );
            $albums[] = $albumForm;
        }
        
        $address = getAddressByID($order['addressID']);
        $order['items'] = $albums;
        $order['address'] = $address;
        
        $result['order'] = $order;
        
        $result = json_encode($result);
        
        echo $result;
        break;
    default:
        echo 'unknown action';
        break;
}