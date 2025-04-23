<?php
// poster_details.php
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
    header("Location: cart.php");
    exit();
}

// Database of poster details
$posters = [
    1 => [
        'title' => 'Kira We El Ginn',
        'image' => 'posters/arabic new/kira.png',
        'year' => 2023,
        'director' => 'Marwan Hamed',
        'description' => 'A psychological thriller that explores the supernatural elements in Egyptian folklore.',
        'size' => '24x36 inches'
    ],
    2 => [
        'title' => 'Beit El Robbi',
        'image' => 'posters/arabic new/robbi.png',
        'year' => 2022,
        'director' => 'Tamer Mohsen',
        'description' => 'A comedy-drama about family relationships and social issues in modern Egypt.',
        'size' => '24x36 inches'
    ],
    3 => [
        'title' => 'El Zoga 13',
        'image' => 'posters/arabic old/13.jpg',
        'year' => 1987,
        'director' => 'Said Marzouk',
        'description' => 'Classic Egyptian comedy about marital troubles and societal expectations.',
        'size' => '27x40 inches'
    ],
    4 => [
        'title' => 'El Erhabi',
        'image' => 'posters/arabic old/erhabi.jpg',
        'year' => 1994,
        'director' => 'Nader Galal',
        'description' => 'Action-packed film about a man fighting against corruption and terrorism.',
        'size' => '27x40 inches'
    ],
    5 => [
        'title' => 'Ibn Hamedo',
        'image' => 'posters/arabic old/hamido.jpg',
        'year' => 1987,
        'director' => 'Nader Galal',
        'description' => 'Comedy about a simple man who becomes unexpectedly wealthy and the troubles that follow.',
        'size' => '27x40 inches'
    ],
    6 => [
        'title' => 'El KitKat',
        'image' => 'posters/arabic old/kitkat.jpg',
        'year' => 1991,
        'director' => 'Henri Barakat',
        'description' => 'Drama about life in Cairo\'s KitKat neighborhood, exploring social issues and relationships.',
        'size' => '27x40 inches'
    ],
    7 => [
        'title' => 'Budapest Grand Hotel',
        'image' => 'posters/english/budapest.jpg',
        'year' => 2014,
        'director' => 'Wes Anderson',
        'description' => 'A whimsical comedy about a legendary concierge and his protege at a famous European hotel.',
        'size' => '24x36 inches'
    ],
    8 => [
        'title' => 'Fight Club',
        'image' => 'posters/english/club.jpg',
        'year' => 1999,
        'director' => 'David Fincher',
        'description' => 'A cult classic about an insomniac office worker and a soap salesman forming an underground fight club.',
        'size' => '27x40 inches'
    ],
    9 => [
        'title' => 'Night Crawler',
        'image' => 'posters/english/crawler.jpg',
        'year' => 2014,
        'director' => 'Dan Gilroy',
        'description' => 'A thriller about a man who records violent events late at night for a local TV station.',
        'size' => '24x36 inches'
    ],
    10 => [
        'title' => 'Pulp Fiction',
        'image' => 'posters/english/pulp.jpg',
        'year' => 1994,
        'director' => 'Quentin Tarantino',
        'description' => 'The lives of two mob hitmen, a boxer, a gangster\'s wife, and a pair of diner bandits intertwine.',
        'size' => '27x40 inches'
    ],
    11 => [
        'title' => 'The Revenant',
        'image' => 'posters/english/rev.jpg',
        'year' => 2015,
        'director' => 'Alejandro González Iñárritu',
        'description' => 'A frontiersman on a fur trading expedition fights for survival after being mauled by a bear.',
        'size' => '24x36 inches'
    ],
    12 => [
        'title' => 'Whiplash',
        'image' => 'posters/english/whip.jpg',
        'year' => 2014,
        'director' => 'Damien Chazelle',
        'description' => 'A promising young drummer enrolls at a cut-throat music conservatory.',
        'size' => '24x36 inches'
    ],
    13 => [
        'title' => 'Planet of the Apes',
        'image' => 'posters/sifi/apes.jpg',
        'year' => 1968,
        'director' => 'Franklin J. Schaffner',
        'description' => 'An astronaut crew crash-lands on a planet where intelligent apes dominate humans.',
        'size' => '27x40 inches'
    ],
    14 => [
        'title' => 'Batman',
        'image' => 'posters/sifi/batman.webp',
        'year' => 1989,
        'director' => 'Tim Burton',
        'description' => 'The Dark Knight of Gotham City begins his war on crime with his first major enemy being the clownishly homicidal Joker.',
        'size' => '27x40 inches'
    ],
    15 => [
        'title' => 'How to Train your Dragon',
        'image' => 'posters/sifi/httyd.jpg',
        'year' => 2010,
        'director' => 'Dean DeBlois, Chris Sanders',
        'description' => 'A hapless young Viking who aspires to hunt dragons becomes the unlikely friend of a young dragon himself.',
        'size' => '24x36 inches'
    ],
    16 => [
        'title' => 'Ironman',
        'image' => 'posters/sifi/ironman.jpg',
        'year' => 2008,
        'director' => 'Jon Favreau',
        'description' => 'After being held captive in an Afghan cave, billionaire engineer Tony Stark creates a unique weaponized suit of armor to fight evil.',
        'size' => '27x40 inches'
    ]
];

// Get the requested poster ID
$poster_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if poster exists
if (!isset($posters[$poster_id])) {
    header("Location: main_store.php");
    exit();
}

$poster = $posters[$poster_id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $poster['title']; ?> - MovieStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="detail_style.css">
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
            <div class="poster-detail">
                <div class="poster-image">
                    <img src="<?php echo $poster['image']; ?>" alt="<?php echo $poster['title']; ?>">
                </div>
                <div class="poster-info">
                    <h1 class="poster-title"><?php echo $poster['title']; ?></h1>
                    <div class="poster-meta">
                        <span><?php echo $poster['year']; ?></span>
                        <span><?php echo $poster['director']; ?></span>
                        <span><?php echo $poster['size']; ?></span>
                    </div>
                    <p class="poster-description"><?php echo $poster['description']; ?></p>
                    <div class="poster-price">150 EGP</div>
                    
                    <div class="action-buttons">
                        <a href="main_store.php" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Back to Collection
                        </a>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="poster_id" value="<?php echo $poster_id; ?>">
                            <input type="hidden" name="poster_title" value="<?php echo $poster['title']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-add-to-cart">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>