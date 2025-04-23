<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Load orders from file
$orders = [];
if (file_exists('orders.json')) {
    $all_orders = json_decode(file_get_contents('orders.json'), true);
    // Filter orders for current user
    foreach ($all_orders as $order) {
        if ($order['customer'] === $_SESSION['username']) {
            $orders[] = $order;
        }
    }
    // Reverse array to show newest first
    $orders = array_reverse($orders);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - MovieStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5e9d9;
            color: #3a2c20;
            line-height: 1.6;
            background-image: url('vintage-paper-texture.jpg');
            background-attachment: fixed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation */
        .nav {
            position: fixed;
            background-color: rgba(58, 44, 32, 0.9);
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 2px solid #8b5a2b;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .nav .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }

        .nav h1 {
            color: #d4af37;
            font-size: 28px;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .user-info {
            display: flex;
            align-items: center;
            color: #f5e9d9;
        }

        .user-info i {
            margin-right: 8px;
            color: #d4af37;
        }

        .user-info a {
            color: #f5e9d9;
            text-decoration: none;
            margin-left: 15px;
            padding: 5px 10px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .user-info a:hover {
            background-color: #8b5a2b;
        }

        .cart-link {
            position: relative;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #d4af37;
            color: #3a2c20;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        /* Account Content */
        .account {
            padding-top: 100px;
            padding-bottom: 40px;
        }

        .account-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .account-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #3a2c20;
        }

        .welcome-message {
            font-size: 1.2rem;
            color: #8b5a2b;
        }

        .account-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #8b5a2b;
            color: #3a2c20;
        }

        /* Orders List */
        .orders-list {
            margin-top: 30px;
        }

        .order-card {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 1px solid #e0d5c3;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0d5c3;
        }

        .order-id {
            font-weight: 700;
            color: #8b5a2b;
        }

        .order-date {
            color: #666;
        }

        .order-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .status-processing {
            background-color: #d4af37;
            color: #3a2c20;
        }

        .status-shipped {
            background-color: #3a8b5a;
            color: white;
        }

        .status-delivered {
            background-color: #8b5a2b;
            color: white;
        }

        .order-items {
            margin: 15px 0;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e0d5c3;
        }

        .order-total {
            text-align: right;
            font-weight: 700;
            margin-top: 10px;
            font-size: 1.1rem;
            color: #8b5a2b;
        }

        .shipping-info {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e0d5c3;
        }

        .shipping-info h3 {
            margin-bottom: 10px;
            color: #3a2c20;
        }

        .shipping-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .shipping-detail {
            margin-bottom: 5px;
        }

        .shipping-detail strong {
            color: #8b5a2b;
        }

        .no-orders {
            text-align: center;
            font-size: 1.2rem;
            margin: 50px 0;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #8b5a2b;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #7a4b24;
        }

        /* Footer */
        footer {
            background-color: #3a2c20;
            padding: 30px 0;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: #d4af37;
            margin: 0 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #f5e9d9;
            text-decoration: underline;
        }

        .copyright {
            color: rgba(245, 233, 217, 0.7);
            font-size: 0.9rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav .container {
                flex-direction: column;
                padding: 15px;
            }

            .user-info {
                margin-top: 15px;
            }

            .account {
                padding-top: 120px;
                padding-bottom: 30px;
            }

            .account-title {
                font-size: 2rem;
            }

            .order-header {
                flex-direction: column;
                gap: 10px;
            }

            .shipping-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="container">
            <h1>MovieStore</h1>
            <div class="user-info">
                <i class="fas fa-user"></i>
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0; ?></span>
                </a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="account">
        <div class="container">
            <div class="account-header">
                <h1 class="account-title">My Account</h1>
                <p class="welcome-message">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>

            <div class="account-section">
                <h2 class="section-title">Order History</h2>
                
                <?php if (empty($orders)): ?>
                    <div class="no-orders">
                        <p>You haven't placed any orders yet.</p>
                        <a href="main_store.php" class="btn">Browse Posters</a>
                    </div>
                <?php else: ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $index => $order): 
                            // Simple status determination based on order age
                            $orderDate = new DateTime($order['date']);
                            $now = new DateTime();
                            $diff = $now->diff($orderDate);
                            $days = $diff->days;
                            
                            if ($days < 1) {
                                $status = 'Processing';
                                $statusClass = 'status-processing';
                            } elseif ($days < 3) {
                                $status = 'Shipped';
                                $statusClass = 'status-shipped';
                            } else {
                                $status = 'Delivered';
                                $statusClass = 'status-delivered';
                            }
                        ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">Order #<?php echo count($orders) - $index; ?></span>
                                    <span class="order-date"><?php echo $order['date']; ?></span>
                                    <span class="order-status <?php echo $statusClass; ?>"><?php echo $status; ?></span>
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
                                    <h3>Shipping Information:</h3>
                                    <div class="shipping-details">
                                        <div class="shipping-detail">
                                            <strong>Name:</strong> <?php echo $order['shipping']['name']; ?>
                                        </div>
                                        <div class="shipping-detail">
                                            <strong>Address:</strong> <?php echo $order['shipping']['address']; ?>
                                        </div>
                                        <div class="shipping-detail">
                                            <strong>City:</strong> <?php echo $order['shipping']['city']; ?>
                                        </div>
                                        <div class="shipping-detail">
                                            <strong>ZIP:</strong> <?php echo $order['shipping']['zip']; ?>
                                        </div>
                                        <div class="shipping-detail">
                                            <strong>Country:</strong> <?php echo $order['shipping']['country']; ?>
                                        </div>
                                        <div class="shipping-detail">
                                            <strong>Payment Method:</strong> 
                                            <?php 
                                            $method = $order['shipping']['payment_method'];
                                            echo ucwords(str_replace('_', ' ', $method));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Contact Us</a>
            </div>
            <p class="copyright">&copy; <?php echo date('Y'); ?> MovieStore. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>