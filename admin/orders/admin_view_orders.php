<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="orders-list-cont">
            <h2><?php 
                    if ($action == 'viewAllOrders') { echo 'All Orders'; }
                    else if ($action =='viewUnshipped') { echo 'Unshipped Orders'; }
            ?></h2>
            <ul id="orders-list">
                <?php 
                    foreach ($orders as $order) :?>
                        <li><a href="<?php echo '?action=viewOrder&orderID=' . $order['orderID']; ?>">Order Numer: <?php echo $order['orderID']; ?></a></li>
                    <?php endforeach; ?>
            </ul>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->