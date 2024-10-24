<?php
include('../includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mealkit_id = $_POST['mealkit_id'];
    $name = $_POST['mealkit_name'];
    $price = $_POST['mealkit_price'];
    $description = $_POST['mealkit_description'];
    
    // 检查是否有标签被选择，如果没有则设为空数组
    $tags = isset($_POST['mealkit_tags']) ? $_POST['mealkit_tags'] : [];
    $image_path = '';

    // 处理图片上传（如果用户上传了新图片）
    if (!empty($_FILES['mealkit_image']['name'])) {
        $target_dir = "../uploads/";
        $image_filename = time() . '_' . basename($_FILES["mealkit_image"]["name"]);
        $image_path = $target_dir . $image_filename;

        if (move_uploaded_file($_FILES["mealkit_image"]["tmp_name"], $image_path)) {
            $image_path = "/nutribox/uploads/" . $image_filename;
        }
    }

    // 更新数据库中的 MealKit 信息
    $sql = "UPDATE mealkits SET name = ?, price = ?, description = ?, image_path = IF(? != '', ?, image_path) WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdsdsi', $name, $price, $description, $image_path, $image_path, $mealkit_id);
    $stmt->execute();

    // 更新标签：先删除旧的标签再插入新的标签
    $delete_sql = "DELETE FROM mealkit_tags WHERE mealkit_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('i', $mealkit_id);
    $delete_stmt->execute();

    foreach ($tags as $tag_id) {
        $insert_sql = "INSERT INTO mealkit_tags (mealkit_id, tag_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param('ii', $mealkit_id, $tag_id);
        $insert_stmt->execute();
    }

    header('Location: userinfo.php');
    exit();
}
?>
