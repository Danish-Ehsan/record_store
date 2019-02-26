<?php

function checkValidCustomer($email, $password) {
    global $db;
    $password = sha1($email . $password);
    $query = 'SELECT *
              FROM customers
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
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getCustomerByEmail($email) {
    global $db;
    $query = 'SELECT customerID, emailAddress, firstName, lastName, addressID
              FROM customers
              WHERE emailAddress = :email';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getCustomerByID($customerID) {
    global $db;
    $query = 'SELECT customerID, emailAddress, firstName, lastName, addressID
              FROM customers
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':customerID', $customerID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getAddressByID($addressID) {
    global $db;
    $query = 'SELECT *
              FROM addresses
              WHERE addressID = :addressID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':addressID', $addressID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getAddressByCustomerID($customerID) {
    global $db;
    $query = 'SELECT *
              FROM addresses
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':customerID', $customerID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function checkCustomerExists($email) {
    global $db;
    $query = 'SELECT *
              FROM customers
              WHERE emailAddress = :email';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $valid = ($statement->rowCount() == 1);
        $statement->closeCursor();
        return $valid;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function addCustomer($firstName, $lastName, $email, $password) {
    global $db;
    $query = 'INSERT INTO customers (firstName, lastName, password, emailAddress)
              VALUES (:firstName, :lastName, :password, :emailAddress)';
    try {
            $statement = $db->prepare($query);
            $statement->bindValue(':firstName', $firstName);
            $statement->bindValue(':lastName', $lastName);
            $statement->bindValue(':emailAddress', $email);
            $statement->bindValue(':password', $password);
            $statement->execute();
            $statement->closeCursor();
            $customerID = $db->lastInsertID();
            return $customerID;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function saveAddress($customerID, $country, $city, $postalCode, $lineOne, $lineTwo) {
    global $db;
    $query = 'INSERT INTO addresses (customerID, country, city, postalCode, line1, line2)
              VALUES (:customerID, :country, :city, :postalCode, :lineOne, :lineTwo)';
    try {
            $statement = $db->prepare($query);
            $statement->bindValue(':customerID', $customerID);
            $statement->bindValue(':country', $country);
            $statement->bindValue(':city', $city);
            $statement->bindValue(':postalCode', $postalCode);
            $statement->bindValue(':lineOne', $lineOne);
            $statement->bindValue(':lineTwo', $lineTwo);
            $statement->execute();
            $statement->closeCursor();
            $addressID = $db->lastInsertID();
            return $addressID;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function saveAddressWithoutCustomer($country, $city, $postalCode, $lineOne, $lineTwo) {
    global $db;
    $query = 'INSERT INTO addresses (country, city, postalCode, line1, line2)
              VALUES (:country, :city, :postalCode, :lineOne, :lineTwo)';
    try {
            $statement = $db->prepare($query);
            $statement->bindValue(':country', $country);
            $statement->bindValue(':city', $city);
            $statement->bindValue(':postalCode', $postalCode);
            $statement->bindValue(':lineOne', $lineOne);
            $statement->bindValue(':lineTwo', $lineTwo);
            $statement->execute();
            $statement->closeCursor();
            $addressID = $db->lastInsertID();
            return $addressID;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getCustomerAddressID($customerID) {
    global $db;
    $query = 'SELECT addressID
              FROM addresses
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':customerID', $customerID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function updateCustomerAddressID($customerID, $addressID) {
    global $db;
    $query = 'UPDATE customers
              SET addressID = :addressID
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':customerID', $customerID);
        $statement->bindValue(':addressID', $addressID);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function updateCustomerDetails($customerID, $firstName, $lastName, $email) {
    global $db;
    $query = 'UPDATE customers
              SET 
                firstName = :firstName,
                lastName = :lastName,
                emailAddress = :emailAddress
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue('customerID', $customerID);
        $statement->bindValue('firstName', $firstName);
        $statement->bindValue('lastName', $lastName);
        $statement->bindValue('emailAddress', $email);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function updateCustomerPassword($customerID, $newPassword) {
    global $db;
    $query = 'UPDATE customers
              SET password = :password
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue('customerID', $customerID);
        $statement->bindValue('password', $newPassword);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function newOrder($customerID, $addressID, $orderDate, $price, $cardType) {
    global $db;
    $query = 'INSERT INTO orders (customerID, addressID, orderDate, price, cardType)
              VALUES (:customerID, :addressID, :orderDate, :price, :cardType)';
    try {
            $statement = $db->prepare($query);
            $statement->bindValue(':customerID', $customerID);
            $statement->bindValue(':addressID', $addressID);
            $statement->bindValue(':orderDate', $orderDate);
            $statement->bindValue(':price', $price);
            $statement->bindValue(':cardType', $cardType);
            $statement->execute();
            $statement->closeCursor();
            $orderID = $db->lastInsertID();
            return $orderID;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function newOrderItem($orderID, $productID, $itemPrice, $quantity) {
    global $db;
    $query = 'INSERT INTO orderItems (orderID, productID, itemPrice, quantity)
              VALUES (:orderID, :productID, :itemPrice, :quantity)';
    try {
            $statement = $db->prepare($query);
            $statement->bindValue(':orderID', $orderID);
            $statement->bindValue(':productID', $productID);
            $statement->bindValue(':itemPrice', $itemPrice);
            $statement->bindValue(':quantity', $quantity);
            $statement->execute();
            $statement->closeCursor();
            $itemID = $db->lastInsertID();
            return $itemID;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getAllOrders() {
    global $db;
    $query = 'SELECT *
              FROM orders';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getUnshippedOrders() {
    global $db;
    $query = 'SELECT *
              FROM orders
              WHERE shipDate IS NULL';
    try {
        $statement = $db->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getOrderByID($orderID) {
    global $db;
    $query = 'SELECT *
              FROM orders
              WHERE orderID = :orderID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':orderID', $orderID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getOrderbyCustomerID($customerID) {
    global $db;
    $query = 'SELECT *
              FROM orders
              WHERE customerID = :customerID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':customerID', $customerID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function getItemsByOrderID($orderID) {
    global $db;
    $query = 'SELECT *
              FROM orderItems
              WHERE orderID = :orderID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':orderID', $orderID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}

function markOrderShipped($orderID, $date) {
    global $db;
    $query = 'UPDATE orders
              SET shipDate = :date
              WHERE orderID = :orderID';
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':orderID', $orderID);
        $statement->bindValue(':date', $date);
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        echo $error_message;
        exit();
    }
}