<?php

require_once ('../../data/util/main.php');
require_once('data/util/valid_admin.php');
require_once ('data/model/database.php');
require_once ('data/model/admin_db.php');
require_once ('data/model/product_db.php');
require_once ('data/model/customer_db.php');

include('admin/view/header.php');

$action = filter_input(INPUT_GET, 'action');
$message = '';

if (!$action) {
    $action = filter_input(INPUT_POST, 'action');
    if (!$action) {
        $action = 'viewAllOrders';
    }
}

switch ($action) {
    case 'viewAllOrders':
        $orders = getAllOrders();
        include('admin/orders/admin_view_orders.php');
        break;
    
    case 'viewUnshipped':
        $orders = getUnshippedOrders();
        include('admin/orders/admin_view_orders.php');
        break;
    
    case 'viewOrder':
        $orderID = filter_input(INPUT_GET, 'orderID');
        $order = getOrderByID($orderID);
        $order['items'] = array();
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
        include('admin/orders/admin_view_order.php');
        break;
        
    case 'markShipped':
        $orderID = filter_input(INPUT_GET, 'orderID');
        $date = date('Y-m-d');
        markOrderShipped($orderID, $date);
        
        $order = getOrderByID($orderID);
        $order['items'] = array();
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
        include('admin/orders/admin_view_order.php');
        break;
    
    default:
        $orders = getAllOrders();
        include('admin/orders/admin_view_order.php');
        break;
}

include ('admin/view/footer.php');