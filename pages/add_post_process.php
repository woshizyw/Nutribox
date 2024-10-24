<?php
include('../includes/db.php');
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$mealkit_description = $_POST['mealkit_description'];
$mealkit_price = $_POST['mealkit_price'];

// 处理图片上传
$target_dir = "../uploads/";
$image_filename = time() . '_' . basename($_FILES["mealkit_image"]["name"]);
$image_path = $target_dir . $image_filename;
if (move_uploaded_file($_FILES["mealkit_image"]["tmp_name"], $image_path)) {
    $image_url = "/nutribox/uploads/" . $image_filename;
} else {
    die("Image upload failed.");
}

// 插入帖子数据
$sql = "INSERT INTO posts (user_id, mealkit_image, mealkit_description, price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('isss', $user_id, $image_url, $mealkit_description, $mealkit_price);
$stmt->execute();

header('Location: community.php');
exit();
?>
