<?php
// register_process.php
include('../includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 验证密码一致性
    if ($password !== $confirm_password) {
        die('Passwords do not match!');
    }

    // 密码加密
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 插入新用户
    $sql = "INSERT INTO users (nickname, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nickname, $email, $hashed_password);

    if ($stmt->execute()) {
        // 注册成功后跳转到登录页面
        header('Location: login.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
