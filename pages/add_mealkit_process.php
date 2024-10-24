<?php
include('../includes/db.php');
session_start();

// 确保用户已登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['mealkit_name'];
    $price = $_POST['mealkit_price'];
    $description = $_POST['mealkit_description'];
    $tags = $_POST['mealkit_tags']; // 从表单中获取标签数组

    // 处理图片上传
    $target_dir = "../uploads/";
    $image_filename = time() . '_' . basename($_FILES["mealkit_image"]["name"]);
    $image_path = $target_dir . $image_filename;
    
    if (move_uploaded_file($_FILES["mealkit_image"]["tmp_name"], $image_path)) {
        $image_url = "/nutribox/uploads/" . $image_filename; // 在数据库中存储相对URL路径
    } else {
        die("Image upload failed.");
    }

    // 插入 MealKit 数据
    $sql = "INSERT INTO mealkits (user_id, name, price, description, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isdss', $user_id, $name, $price, $description, $image_url);

    if ($stmt->execute()) {
        $mealkit_id = $stmt->insert_id; // 获取新插入的 MealKit ID

        // 插入关联的标签数据到 mealkit_tags 表
        foreach ($tags as $tag_id) {
            $tag_sql = "INSERT INTO mealkit_tags (mealkit_id, tag_id) VALUES (?, ?)";
            $tag_stmt = $conn->prepare($tag_sql);
            $tag_stmt->bind_param('ii', $mealkit_id, $tag_id);
            $tag_stmt->execute();
            $tag_stmt->close();
        }

        // 插入完成后重定向到用户信息页面
        header('Location: userinfo.php'); 
    } else {
        echo "Error: " . $stmt->error;
    }

    // 关闭连接
    $stmt->close();
    $conn->close();
}
?>
