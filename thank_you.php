<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - MovieStore</title>
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

        /* Thank You Content */
        .thank-you {
            padding-top: 150px;
            padding-bottom: 80px;
            text-align: center;
        }

        .thank-you-icon {
            font-size: 5rem;
            color: #3a8b5a;
            margin-bottom: 30px;
        }

        .thank-you h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #3a2c20;
        }

        .thank-you p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 40px;
            line-height: 1.8;
        }

        .order-details {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 1px solid #e0d5c3;
        }

        .order-details h2 {
            margin-bottom: 20px;
            color: #8b5a2b;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #8b5a2b;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            transition: background-color 0.3s;
            margin: 10px;
        }

        .btn:hover {
            background-color: #7a4b24;
        }

        .btn-secondary {
            background-color: #3a2c20;
        }

        .btn-secondary:hover {
            background-color: #2a1c10;
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

            .thank-you {
                padding-top: 120px;
                padding-bottom: 60px;
            }

            .thank-you h1 {
                font-size: 2.2rem;
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

    <section class="thank-you">
        <div class="container">
            <div class="thank-you-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Thank You for Your Purchase!</h1>
            <p>Your order has been successfully processed. We've sent a confirmation email with your order details. Your vintage movie posters will be shipped within 3-5 business days.</p>
            
            <div class="order-details">
                <h2>Order Summary</h2>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Date:</strong> <?php echo date('F j, Y'); ?></p>
                <p>You can view your order history anytime by visiting your account.</p>
            </div>
            
            <div class="action-buttons">
                <a href="main_store.php" class="btn">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
                <a href="account.php" class="btn btn-secondary">
                    <i class="fas fa-user"></i> View Account
                </a>
            </div>
        </div>
    </section>
</body>
</html>