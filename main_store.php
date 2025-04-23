<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $poster_id = $_POST['poster_id'];
    $poster_title = $_POST['poster_title'];
    
    if (!isset($_SESSION['cart'][$poster_id])) {
        $_SESSION['cart'][$poster_id] = [
            'title' => $poster_title,
            'price' => 150,
            'quantity' => 1
        ];
    } else {
        $_SESSION['cart'][$poster_id]['quantity']++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage-Movie Posters</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="store_style.css">
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

    <section class="hero">
        <div class="container">
            <h1>Vintage Movie Posters Collection</h1>
        </div>
    </section>

    <section class="posters-section">
        <div class="container">
            <h2 class="section-title">Available Collection</h2>
            <div class="posters-grid">
                <?php
                $posters = [
                    ['id' => 1, 'title' => 'Kira We El Ginn', 'image' => 'posters\arabic new\kira.png'],
                    ['id' => 2, 'title' => 'Beit El Robbi', 'image' => 'posters\arabic new\robbi.png'],
                    ['id' => 3, 'title' => 'El Zoga 13', 'image' => 'posters\arabic old\13.jpg'],
                    ['id' => 4, 'title' => 'El Erhabi', 'image' => 'posters\arabic old\erhabi.jpg'],
                    ['id' => 5, 'title' => 'Ibn Hamedo', 'image' => 'posters\arabic old\hamido.jpg'],
                    ['id' => 6, 'title' => 'El KitKat', 'image' => 'posters\arabic old\kitkat.jpg'],
                    ['id' => 7, 'title' => 'Budapest Grand Hotel', 'image' => 'posters\english\budapest.jpg'],
                    ['id' => 8, 'title' => 'Fight Club', 'image' => 'posters\english\club.jpg'],
                    ['id' => 9, 'title' => 'Night Crawler', 'image' => 'posters\english\crawler.jpg'],
                    ['id' => 10, 'title' => 'Pulp Fiction', 'image' => 'posters\english\pulp.jpg'],
                    ['id' => 11, 'title' => 'The Revenant', 'image' => 'posters\english\rev.jpg'],
                    ['id' => 12, 'title' => 'Whiplash', 'image' => 'posters\english\whip.jpg'],
                    ['id' => 13, 'title' => 'Palnet of the Apes', 'image' => 'posters\sifi\apes.jpg'],
                    ['id' => 14, 'title' => 'Batman', 'image' => 'posters\sifi\batman.webp'],
                    ['id' => 15, 'title' => 'How to Train your Dragon', 'image' => 'posters\sifi\httyd.jpg'],
                    ['id' => 16, 'title' => 'Ironman', 'image' => 'posters\sifi\ironman.jpg']

                ];

                foreach ($posters as $poster) {
                ?>
                <div class="poster-card">
                    <a href="poster_details.php?id=<?php echo $poster['id']; ?>" class="poster-link">
                        <img src="<?php echo $poster['image']; ?>" alt="<?php echo $poster['title']; ?>" class="poster-img">
                        <div class="poster-info">
                            <h3 class="poster-title"><?php echo $poster['title']; ?></h3>
                            <p class="poster-price">150 EGP</p>
                        </div>
                    </a>
                    <form method="POST" action="">
                        <input type="hidden" name="poster_id" value="<?php echo $poster['id']; ?>">
                        <input type="hidden" name="poster_title" value="<?php echo $poster['title']; ?>">
                        <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</body>
</html>