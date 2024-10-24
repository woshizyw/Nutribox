<?php
include('../includes/db.php');
session_start();

// 检查是否已经登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 从 session 中获取购物车的详细信息
$mealkit_id = isset($_SESSION['mealkit_id']) ? (int) $_SESSION['mealkit_id'] : null;
$quantity = isset($_SESSION['quantity']) ? (int) $_SESSION['quantity'] : null;
$total_price = isset($_SESSION['total_price']) ? (float) $_SESSION['total_price'] : null;
$location = isset($_SESSION['checkout_location']) ? $_SESSION['checkout_location'] : null;

// 检查是否所有信息都存在
if (!$mealkit_id || !$quantity || !$total_price || !$location) {
    echo "Missing order information.<br>";
    echo "mealkit_id: " . ($mealkit_id ?: 'Not set') . "<br>";
    echo "quantity: " . ($quantity ?: 'Not set') . "<br>";
    echo "total_price: " . ($total_price ?: 'Not set') . "<br>";
    echo "location: " . ($location ?: 'Not set') . "<br>";
    exit();  // 输出调试信息后，停止执行
}

// 生成付款时间
$payment_time = date('Y-m-d H:i:s');


// 插入订单数据到数据库
$sql = "INSERT INTO orders (user_id, mealkit_id, quantity, total_price, order_date, pickup_location)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iiisss', $user_id, $mealkit_id, $quantity, $total_price, $payment_time, $location);

// 检查 SQL 插入是否成功
if ($stmt->execute()) {
    echo "Order placed successfully!";
} else {
    echo "Error inserting order: " . $stmt->error;
}

// 清空购物车 session 信息
unset($_SESSION['mealkit_id'], $_SESSION['quantity'], $_SESSION['total_price'], $_SESSION['checkout_location']);

// 关闭数据库连接
$stmt->close();
$conn->close();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include('../components/navbar.php'); ?>  <!-- 导航栏 -->

<div class="container mt-5">
    <h2>Payment Successful</h2>
    <p>Your payment was successful! Thank you for your order.</p>
    <p>You can view your order in your Order History.</p>
    <a href="userinfo.php" class="btn btn-primary">Go to Order History</a>
</div>

<?php include('../components/footer.php'); ?>  <!-- 页脚 -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
