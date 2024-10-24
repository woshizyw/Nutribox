<?php
include('../includes/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];

// 插入评论
$sql = "INSERT INTO comments (user_id, post_id, comment) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('iis', $user_id, $post_id, $comment);
$stmt->execute();

header('Location: community.php');
exit();
?>
