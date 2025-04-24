<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_index'])) {
    $orderIndex = intval($_POST['order_index']);

    $orders = [];
    if (file_exists('orders.json')) {
        $orders = json_decode(file_get_contents('orders.json'), true);

        if (isset($orders[$orderIndex])) {
            $orders[$orderIndex]['status'] = 'done';
            file_put_contents('orders.json', json_encode($orders, JSON_PRETTY_PRINT));
        }
    }
}

header("Location: admin.php");
exit();
