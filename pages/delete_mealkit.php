<?php
include('../includes/db.php');
session_start();

// 确保用户已登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 检查是否传递了 MealKit ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mealkit_id'])) {
    $mealkit_id = $_POST['mealkit_id'];
    
    // 删除与该 MealKit 相关的标签
    $delete_tags_sql = "DELETE FROM mealkit_tags WHERE mealkit_id = ?";
    $delete_tags_stmt = $conn->prepare($delete_tags_sql);
    $delete_tags_stmt->bind_param('i', $mealkit_id);
    $delete_tags_stmt->execute();
    
    // 删除 MealKit
    $delete_mealkit_sql = "DELETE FROM mealkits WHERE id = ?";
    $delete_mealkit_stmt = $conn->prepare($delete_mealkit_sql);
    $delete_mealkit_stmt->bind_param('i', $mealkit_id);
    $delete_mealkit_stmt->execute();
    
    // 删除后重定向到用户信息页面
    header('Location: userinfo.php');
    exit();
} else {
    // 如果没有传递 MealKit ID，返回错误页面或重定向
    header('Location: userinfo.php');
    exit();
}
?>
