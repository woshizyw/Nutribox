<?php
include('../includes/db.php');
session_start();

// 检查是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 获取用户帖子
$sql_posts = "SELECT posts.*, users.nickname, users.avatar, users.email 
              FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$posts_result = $conn->query($sql_posts);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/nutribox/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
</head>
<body>

<?php include('../components/navbar.php'); ?> <!-- 导航栏 -->

<div class="container mt-5">
    <h2>Community Posts</h2>

    <!-- 按钮用于展开发帖表单 -->
    <button class="btn btn-secondary mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#postForm" aria-expanded="false" aria-controls="postForm">
        Create a New Post
    </button>

    <!-- 发帖表单，默认折叠 -->
    <div class="collapse" id="postForm">
        <form action="add_post_process.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="mealkit_image" class="form-label">Upload MealKit Image</label>
                <input type="file" class="form-control" id="mealkit_image" name="mealkit_image" required>
            </div>
            <div class="mb-3">
                <label for="mealkit_description" class="form-label">MealKit Description</label>
                <textarea class="form-control" id="mealkit_description" name="mealkit_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="mealkit_price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="mealkit_price" name="mealkit_price" required>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>

    <hr>
    <!-- 展示所有帖子 -->
    <div class="d-flex flex-wrap justify-content-between">
        <?php while ($post = $posts_result->fetch_assoc()) : ?>
            <div class="card mb-3" style="width: 48%;"> <!-- 设置宽度为48%，为了在两列之间留一些空隙 -->
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <img src="<?php echo $post['avatar']; ?>" alt="User Avatar" class="rounded-circle" width="40" height="40">
                        <strong><?php echo htmlspecialchars($post['nickname']); ?></strong> (<?php echo htmlspecialchars($post['email']); ?>)
                    </div>
                    <!-- 删除按钮 -->
                    <?php if ($post['user_id'] == $user_id) : ?>
                        <form action="delete_post.php" method="POST" class="float-end" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <img src="<?php echo $post['mealkit_image']; ?>" class="img-fluid mb-3" alt="MealKit Image">
                    <p><?php echo htmlspecialchars($post['mealkit_description']); ?></p>
                    <p><strong>Price: $<?php echo htmlspecialchars($post['price']); ?></strong></p>
                    <!-- 点赞功能 -->
                    <button class="btn btn-outline-primary like-btn" data-post-id="<?php echo $post['id']; ?>">
                        ❤️ Like <span id="like-count-<?php echo $post['id']; ?>">0</span>
                    </button>
                </div>

                <!-- 评论区 -->
                <div class="card-footer">
                    <form action="add_comment.php" method="POST">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" placeholder="Add a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">Comment</button>
                    </form>

                    <!-- 展示评论 -->
                    <div id="comments-section-<?php echo $post['id']; ?>">
                        <?php
                        $sql_comments = "SELECT comments.*, users.nickname, users.avatar 
                                        FROM comments JOIN users ON comments.user_id = users.id 
                                        WHERE comments.post_id = ? ORDER BY comments.created_at ASC";
                        $stmt_comments = $conn->prepare($sql_comments);
                        $stmt_comments->bind_param('i', $post['id']);
                        $stmt_comments->execute();
                        $comments_result = $stmt_comments->get_result();
                        while ($comment = $comments_result->fetch_assoc()) :
                        ?>
                            <div class="d-flex mt-2">
                                <img src="<?php echo $comment['avatar']; ?>" alt="Avatar" class="rounded-circle" width="30" height="30">
                                <div class="ms-2">
                                    <strong><?php echo htmlspecialchars($comment['nickname']); ?></strong>: 
                                    <?php echo htmlspecialchars($comment['comment']); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>


<?php include('../components/footer.php'); ?>  <!-- 页脚 -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/nutribox/assets/js/menu.js"></script>

<!-- 包含点赞功能的 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/nutribox/assets/js/community.js"></script>
</body>
</html>
