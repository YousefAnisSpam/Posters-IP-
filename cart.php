<?php
// cart.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_cart'])) {
        foreach ($_POST['quantity'] as $poster_id => $quantity) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$poster_id]);
            } else {
                $_SESSION['cart'][$poster_id]['quantity'] = $quantity;
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $poster_id = $_POST['poster_id'];
        unset($_SESSION['cart'][$poster_id]);
    } elseif (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
    } elseif (isset($_POST['checkout'])) {
        // Process checkout and save order
        $order = [
            'customer' => $_SESSION['username'],
            'items' => $_SESSION['cart'],
            'shipping' => [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'zip' => $_POST['zip'],
                'country' => $_POST['country'],
                'payment_method' => $_POST['payment_method']
            ],
            'total' => array_sum(array_map(function($item) {
                return $item['price'] * $item['quantity'];
            }, $_SESSION['cart'])),
            'date' => date('Y-m-d H:i:s')
        ];
        
        // Save order to file (in a real app, you'd use a database)
        $orders = [];
        if (file_exists('orders.json')) {
            $orders = json_decode(file_get_contents('orders.json'), true);
        }
        $orders[] = $order;
        file_put_contents('orders.json', json_encode($orders, JSON_PRETTY_PRINT));
        
        // Clear cart and redirect to thank you page
        $_SESSION['cart'] = [];
        header("Location: thank_you.php");
        exit();
    }
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStore - Your Cart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="cart_style.css">
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
                    <span class="cart-count"><?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?></span>
                </a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <main class="main">
        <div class="container">
            <h1 class="cart-title">Your Shopping Cart</h1>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="cart-empty">
                    <p>Your cart is currently empty.</p>
                    <a href="main_store.php" class="btn btn-continue">Continue Shopping</a>
                </div>
            <?php else: ?>
                <form method="POST" action="cart.php">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Poster</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $poster_id => $item): ?>
                                <tr>
                                    <td>
                                        <img src="poster<?php echo $poster_id; ?>.jpg" alt="<?php echo $item['title']; ?>" class="cart-item-img">
                                    </td>
                                    <td><?php echo $item['title']; ?></td>
                                    <td>150 EGP</td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $poster_id; ?>]" 
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" 
                                               class="quantity-input">
                                    </td>
                                    <td><?php echo 150 * $item['quantity']; ?> EGP</td>
                                    <td>
                                        <button type="submit" name="remove_item" class="remove-btn">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                        <input type="hidden" name="poster_id" value="<?php echo $poster_id; ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="cart-total">
                        <span>Total: </span>
                        <span class="total-amount"><?php echo $total; ?> EGP</span>
                    </div>

                    <div class="cart-actions">
                        <a href="main_store.php" class="btn btn-continue">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                        <div>
                            <button type="submit" name="clear_cart" class="btn btn-clear">
                                <i class="fas fa-broom"></i> Clear Cart
                            </button>
                            <button type="submit" name="update_cart" class="btn btn-update">
                                <i class="fas fa-sync-alt"></i> Update Cart
                            </button>
                        </div>
                    </div>

                    <!-- Shipping and Payment Information -->
                    <div class="checkout-form">
                        <h2 class="form-title">Shipping & Payment Information</h2>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Shipping Address</label>
                            <textarea id="address" name="address" rows="3" required></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="zip">ZIP/Postal Code</label>
                                    <input type="text" id="zip" name="zip" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" id="country" name="country" required>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" name="checkout" class="btn-submit">
                            <i class="fas fa-check-circle"></i> Complete Order
                        </button>
                    </div>
                </form>
            <?php endif; ?>
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