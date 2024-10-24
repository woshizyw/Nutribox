<?php
include('../includes/db.php');
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 检查请求是否为 POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // 更新订单状态的 SQL 语句
    $sql = "UPDATE orders SET status = ? WHERE id = ? AND EXISTS (SELECT 1 FROM mealkits WHERE mealkits.id = orders.mealkit_id AND mealkits.user_id = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $new_status, $order_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Order status updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update order status.";
    }

    header('Location: userinfo.php');
    exit();
}
?>
