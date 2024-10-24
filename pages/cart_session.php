<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 从 POST 请求中获取购物车信息
    $_SESSION['mealkit_id'] = isset($_POST['mealkit_id']) ? (int) $_POST['mealkit_id'] : null;
    $_SESSION['quantity'] = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
    $_SESSION['total_price'] = isset($_POST['total_price']) ? (float) $_POST['total_price'] : null;

    // 检查是否成功设置购物车数据
    if ($_SESSION['mealkit_id'] && $_SESSION['quantity'] && $_SESSION['total_price']) {
        echo "Cart information saved.";
    } else {
        echo "Error: Missing cart information.";
    }
}

?>
