<?php
include('../includes/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nickname = $_POST['nickname'];

    // 检查用户是否上传了头像
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        // 设置上传路径
        $target_dir = "../uploads/";
        // 给文件生成唯一名称，避免重名问题
        $target_file = $target_dir . time() . basename($_FILES["avatar"]["name"]);
        
        // 保存文件到服务器
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $avatar_path = "/nutribox/uploads/" . time() . basename($_FILES["avatar"]["name"]);
            // 更新数据库中的头像路径
            $sql = "UPDATE users SET nickname = ?, avatar = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $nickname, $avatar_path, $user_id);
        } else {
            echo "头像上传失败。";
        }
    } else {
        // 更新昵称
        $sql = "UPDATE users SET nickname = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $nickname, $user_id);
    }

    if ($stmt->execute()) {
        // 更新成功，跳转到用户信息页面
        $_SESSION['user_nickname'] = $nickname;
        if (isset($avatar_path)) {
            $_SESSION['user_avatar'] = $avatar_path;
        }
        header('Location: userinfo.php');
    } else {
        echo "更新用户信息时出错：" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
