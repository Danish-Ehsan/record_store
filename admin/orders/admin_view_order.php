<div id="container">
    <?php include ('admin/view/admin_left_panel.php'); ?>

    <div id="right-panel">
        <div id="orders-list-cont">
            <h2>View Order</h2>
            <div class="order-cont">
                <div class="customer-order-number">Order ID: <?php echo $order['orderID']; ?></div>
                <div class="order-panels-cont">
                    <div class="order-left-panel">
                        <div class="customer-order-date">Order Date: <span class="color-white"><?php echo $order['orderDate']; ?></span></div>
                        <div class="customer-order-ship">Ship Date: <span class="color-white">
                            <?php 
                                if ($order['shipDate'] != null) { echo $order['shipDate']; }
                                else { echo 'Not Shipped'; }
                            ?>
                        </span></div>
                        <div class="order-items-title">Order Items:</div>
                        <ul class="order-items-list">
                            <?php foreach ($order['items'] as $item) :?>
                                <li>
                                    <?php echo $item['albumName']; ?><?php if ($item['quantity'] > 1) : ?><span class="order-item-quantity"><?php echo ' x' . $item['quantity']; ?></span><?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="order-total-price">Total Price: <span class="color-white"><?php echo '$' . $order['price']; ?></span></div>
                    </div>
                    <div class="order-right-panel">
                        <div class="customer-order-shipping">
                            <div class="order-address-title">Address:</div>
                            <?php echo $order['address']['city'] . ', ' . $order['address']['country']; ?><br>
                            <?php echo $order['address']['postalCode']; ?><br>
                            <?php echo $order['address']['line1']; ?><br>
                            <?php if (strlen($order['address']['line2']) > 0) echo $order['address']['line2'];?>
                        </div>
                    </div>
                </div>
                <?php if ($order['shipDate'] == null) : ?>
                    <form action="." method="get">
                        <input type="hidden" name="action" value="markShipped">
                        <input type="hidden" name="orderID" value="<?php echo $order['orderID']; ?>">
                        <input type="submit" value="Mark As Shipped" class="submit-button">
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div><!--#right-panel-->
</div><!--#container-->