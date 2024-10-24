<?php
include('../includes/db.php');
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// 获取用户信息
$sql_user = "SELECT * FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();

// 获取用户的 MealKits
$sql_mealkits = "SELECT * FROM mealkits WHERE user_id = ?";
$stmt_mealkits = $conn->prepare($sql_mealkits);
$stmt_mealkits->bind_param('i', $user_id);
$stmt_mealkits->execute();
$mealkits = $stmt_mealkits->get_result();

// 更新查询，使用 JOIN 连接 orders 和 mealkits 表，获取 mealkit 的名称和价格
$sql_orders = "
    SELECT orders.*, mealkits.name AS mealkit_name, mealkits.price AS mealkit_price 
    FROM orders 
    JOIN mealkits ON orders.mealkit_id = mealkits.id 
    WHERE orders.user_id = ?
";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param('i', $user_id);
$stmt_orders->execute();
$orders = $stmt_orders->get_result();


// 设置头像路径，使用默认头像或者自定义头像
$avatar = !empty($user['avatar']) ? $user['avatar'] : '/nutribox/assets/images/DefaultUser.png';
$_SESSION['user_avatar'] = $avatar; // 更新导航栏中的头像
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Info</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/nutribox/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
</head>
<body>

<?php include('../components/navbar.php'); ?>  <!-- 导航栏 -->

<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($user['nickname']); ?></h2>

    <!-- 折叠功能 -->
    <div class="accordion" id="userAccordion">
        <!-- Profile Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingProfile">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfile" aria-expanded="false" aria-controls="collapseProfile">
                    Profile
                </button>
            </h2>
            <div id="collapseProfile" class="accordion-collapse collapse" aria-labelledby="headingProfile" data-bs-parent="#userAccordion">
                <div class="accordion-body">
                    <img src="<?php echo $avatar; ?>" alt="User Avatar" width="100" height="100" class="rounded-circle mb-3">
                    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nickname" class="form-label">Nickname</label>
                            <input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo htmlspecialchars($user['nickname']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Upload Avatar</label>
                            <input type="file" class="form-control" id="avatar" name="avatar">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order History Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOrders">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrders" aria-expanded="false" aria-controls="collapseOrders">
                    Order History
                </button>
            </h2>
            <div id="collapseOrders" class="accordion-collapse collapse" aria-labelledby="headingOrders" data-bs-parent="#userAccordion">
                <div class="accordion-body">
                    <?php if ($orders->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($order = $orders->fetch_assoc()) : ?>
                                <li class="list-group-item">
                                    <div><strong>MealKit:</strong> <?php echo htmlspecialchars($order['mealkit_name']); ?></div>
                                    <div><strong>Price:</strong> $<?php echo number_format($order['mealkit_price'], 2); ?></div>
                                    <div><strong>Extras:</strong> <?php echo htmlspecialchars($order['extras']); ?></div>
                                    <div><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></div>
                                    <div><strong>Pickup Time:</strong> <?php echo htmlspecialchars($order['pickup_time']); ?></div>
                                    <div><strong>Pickup Location:</strong> <?php echo htmlspecialchars($order['pickup_location']); ?></div>
                                    <div><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></div>
                                    
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <!-- My MealKits Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingMealKits">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMealKits" aria-expanded="false" aria-controls="collapseMealKits">
                    My MealKits
                </button>
            </h2>
            <div id="collapseMealKits" class="accordion-collapse collapse" aria-labelledby="headingMealKits" data-bs-parent="#userAccordion">
                <div class="accordion-body">
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addMealKitModal">+ Add New MealKit</button>
                    <ul class="list-group">
                        <?php while ($mealkit = $mealkits->fetch_assoc()) : ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($mealkit['name']); ?> - Likes: <?php echo htmlspecialchars($mealkit['likes']); ?>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editMealKitModal_<?php echo $mealkit['id']; ?>">Edit</button>
                                <form action="delete_mealkit.php" method="POST" class="float-end" onsubmit="return confirmDelete();">
                                    <input type="hidden" name="mealkit_id" value="<?php echo htmlspecialchars($mealkit['id']); ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </li>
                            <!-- Edit MealKit Modal -->
                            <div class="modal fade" id="editMealKitModal_<?php echo $mealkit['id']; ?>" tabindex="-1" aria-labelledby="editMealKitModalLabel_<?php echo $mealkit['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editMealKitModalLabel_<?php echo $mealkit['id']; ?>">Edit MealKit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="edit_mealkit_process.php" method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="mealkit_name_<?php echo $mealkit['id']; ?>" class="form-label">MealKit Name</label>
                                                    <input type="text" class="form-control" id="mealkit_name_<?php echo $mealkit['id']; ?>" name="mealkit_name" value="<?php echo htmlspecialchars($mealkit['name']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mealkit_price_<?php echo $mealkit['id']; ?>" class="form-label">Price</label>
                                                    <input type="number" step="0.01" class="form-control" id="mealkit_price_<?php echo $mealkit['id']; ?>" name="mealkit_price" value="<?php echo htmlspecialchars($mealkit['price']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mealkit_image_<?php echo $mealkit['id']; ?>" class="form-label">Upload Image</label>
                                                    <input type="file" class="form-control" id="mealkit_image_<?php echo $mealkit['id']; ?>" name="mealkit_image">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mealkit_description_<?php echo $mealkit['id']; ?>" class="form-label">Description</label>
                                                    <textarea class="form-control" id="mealkit_description_<?php echo $mealkit['id']; ?>" name="mealkit_description" rows="3" required><?php echo htmlspecialchars($mealkit['description']); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="mealkit_tags" class="form-label">Tags</label>
                                                    <select multiple class="form-control" id="mealkit_tags" name="mealkit_tags[]">
                                                        <?php
                                                        // 获取所有标签
                                                        $sql_tags = "SELECT * FROM tags";
                                                        $tags_result = $conn->query($sql_tags);
                                                        while ($tag = $tags_result->fetch_assoc()) {
                                                            echo "<option value='" . htmlspecialchars($tag['id']) . "'>" . htmlspecialchars($tag['name']) . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="mealkit_id" value="<?php echo $mealkit['id']; ?>">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- 添加MealKit的模态框 -->
<div class="modal fade" id="addMealKitModal" tabindex="-1" aria-labelledby="addMealKitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMealKitModalLabel">Add New MealKit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="add_mealkit_process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="mealkit_name" class="form-label">MealKit Name</label>
                        <input type="text" class="form-control" id="mealkit_name" name="mealkit_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mealkit_price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="mealkit_price" name="mealkit_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="mealkit_image" class="form-label">Upload Image</label>
                        <input type="file" class="form-control" id="mealkit_image" name="mealkit_image" required>
                    </div>
                    <div class="mb-3">
                        <label for="mealkit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="mealkit_description" name="mealkit_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="mealkit_tags" class="form-label">Tags</label>
                        <select multiple class="form-control" id="mealkit_tags" name="mealkit_tags[]">
                            <?php
                            // 获取所有标签
                            $sql_tags = "SELECT * FROM tags";
                            $tags_result = $conn->query($sql_tags);
                            while ($tag = $tags_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($tag['id']) . "'>" . htmlspecialchars($tag['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save MealKit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- 登出按钮 -->
<div class="container mt-5 text-center">
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<?php include('../components/footer.php'); ?>  <!-- 页脚 -->

<!-- Bootstrap JS -->
<script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete and unpublish this meal kit?");
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
