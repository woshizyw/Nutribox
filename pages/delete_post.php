<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$post_id = $_POST['post_id'];
$user_id = $_SESSION['user_id'];

// 删除帖子
$sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();

header('Location: community.php');
exit();
?>
