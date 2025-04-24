<?php
session_start();

// Simple admin authentication (in a real app, use proper authentication)
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Load orders from file
$orders = [];
if (file_exists('orders.json')) {
    $orders = json_decode(file_get_contents('orders.json'), true);
}

$originalOrders = $orders; // Save original order for indexing
$orders = array_reverse($orders); // Reverse for display
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStore - Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <nav class="nav">
        <div class="container">
            <h1>MovieStore Admin</h1>
            <div class="admin-actions">
                <a href="main_store.php">View Store</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="main">
        <div class="container">
            <h1 class="page-title">Order Management</h1>
            
            <?php if (empty($orders)): ?>
                <div class="no-orders">
                    <p>No orders have been placed yet.</p>
                </div>
            <?php else: ?>
                <div class="orders-list">
                    <?php foreach ($orders as $index => $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">Order #<?php echo count($orders) - $index; ?></span>
                                <span class="order-date"><?php echo $order['date']; ?></span>
                            </div>
                            
                            <div class="order-customer">
                                <strong>Customer:</strong> <?php echo $order['customer']; ?>
                            </div>
                            
                            <div class="order-items">
                                <h3>Items Ordered:</h3>
                                <?php foreach ($order['items'] as $poster_id => $item): ?>
                                    <div class="order-item">
                                        <span><?php echo $item['title']; ?> (x<?php echo $item['quantity']; ?>)</span>
                                        <span><?php echo $item['price'] * $item['quantity']; ?> EGP</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="order-total">
                                Total: <?php echo $order['total']; ?> EGP
                            </div>
                            
                            <div class="shipping-info">
                                <h3>Shipping & Payment Information:</h3>
                                <div class="shipping-details">
                                    <div class="shipping-detail"><strong>Name:</strong> <?php echo $order['shipping']['name']; ?></div>
                                    <div class="shipping-detail"><strong>Email:</strong> <?php echo $order['shipping']['email']; ?></div>
                                    <div class="shipping-detail"><strong>Phone:</strong> <?php echo $order['shipping']['phone']; ?></div>
                                    <div class="shipping-detail"><strong>Address:</strong> <?php echo $order['shipping']['address']; ?></div>
                                    <div class="shipping-detail"><strong>City:</strong> <?php echo $order['shipping']['city']; ?></div>
                                    <div class="shipping-detail"><strong>ZIP:</strong> <?php echo $order['shipping']['zip']; ?></div>
                                    <div class="shipping-detail"><strong>Country:</strong> <?php echo $order['shipping']['country']; ?></div>
                                    <div class="shipping-detail"><strong>Payment Method:</strong> 
                                        <?php 
                                            $method = $order['shipping']['payment_method'];
                                            echo ucwords(str_replace('_', ' ', $method));
                                        ?>
                                    </div>
                                    <div class="shipping-detail"><strong>Status:</strong> 
                                        <?php echo isset($order['status']) ? ucfirst($order['status']) : 'Pending'; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (!isset($order['status']) || $order['status'] !== 'done'): ?>
                                <form method="POST" action="mark_done.php">
                                    <input type="hidden" name="order_index" value="<?php echo count($originalOrders) - $index - 1; ?>">
                                    <button type="submit" class="mark-done-button">Mark as Done</button>
                                </form>
                            <?php else: ?>
                                <p style="color: green; font-weight: bold; margin-top: 10px;">✅ Order Completed</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
