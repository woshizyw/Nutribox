<?php
include('../includes/db.php');
session_start();

// 获取所有标签
$tags_query = "SELECT * FROM tags";
$tags_result = $conn->query($tags_query);

// 获取所有 MealKits，初始默认显示
$mealkits_query = "SELECT * FROM mealkits";
$mealkits_result = $conn->query($mealkits_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/nutribox/assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="/nutribox/assets/images/favicon.png" type="image/png">
</head>
<body>

<!-- 固定在顶部的导航栏 -->
<?php include('../components/navbar.php'); ?>  <!-- 导航栏 -->

<div class="container-fluid mt-5">
    <div class="row">
        <!-- 包裹左侧功能区和右侧展示区的框架 -->
        <div class="d-flex" id="main-content">
            <!-- 左侧功能区 -->
            <div class="col-md-3" id="left-sidebar">
                <div class="sticky-top">
                    <div id="sort-buttons" class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-primary sort-button" data-sort="price_asc">👆</button>
                        <button class="btn btn-outline-primary sort-button" data-sort="price_desc">👇</button>
                        <button class="btn btn-outline-primary sort-button" data-sort="date_asc">Old</button>
                        <button class="btn btn-outline-primary sort-button" data-sort="date_desc">New</button>
                        <button class="btn btn-outline-primary sort-button" data-sort="likes_desc">♥</button>
                    </div>
                    <br>
                    <h3 class="mb-3">Select your Tag</h3>
                    <div id="tags-list" class="mb-4">
                        <?php while ($tag = $tags_result->fetch_assoc()) : ?>
                            <button type="button" class="btn btn-outline-primary rounded-pill tag-button me-2 mb-2" data-tag-id="<?php echo $tag['id']; ?>">
                                <?php echo $tag['name']; ?>
                            </button>
                        <?php endwhile; ?>
                    </div>

                    <!-- Add custom tag input -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="custom-tag-input" placeholder="Create your own Tags">
                        <button class="btn btn-secondary" id="add-custom-tag">+</button>
                    </div>
                </div>
            </div>

            <!-- 右侧菜单展示区 -->
            <div class="col-md-9" id="menu-content">
                <div class="row" id="mealkit-grid">
                    <?php while ($mealkit = $mealkits_result->fetch_assoc()) : ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo $mealkit['image_path']; ?>" class="card-img-top" alt="<?php echo $mealkit['name']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $mealkit['name']; ?></h5>
                                    <p class="card-text">$<?php echo $mealkit['price']; ?></p>
                                    <p class="card-text">❤ <?php echo $mealkit['likes']; ?> Likes</p>

                                        <!-- 额外添加的描述信息 -->
                                    <p class="card-description"><?php echo $mealkit['description']; ?></p>
                                    <!-- 加入购物车按钮 -->
                                    <button class="btn btn-primary add-to-cart" data-id="<?php echo $mealkit['id']; ?>" data-name="<?php echo $mealkit['name']; ?>" data-price="<?php echo $mealkit['price']; ?>">Add to Cart</button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 固定的购物车按钮 -->
<div id="cart-button" class="fixed-bottom text-end p-3">
    <button class="btn btn-primary" id="open-cart">🛒 Cart</button>
</div>


<!-- 购物车侧边栏 -->
<div id="cart-panel" class="cart-panel">
    <div class="cart-header">
        <h5 class="cart-title">Shopping Cart</h5>
        <button type="button" class="btn-close" id="close-cart" aria-label="Close">×</button> <!-- 添加关闭符号 -->
    </div>
    <div class="cart-body">
        <ul id="cart-items" class="list-group mb-3">
            <!-- 动态购物车内容 -->
        </ul>
    </div>
    <div class="cart-footer">
        <div>
            <strong>Total: $<span id="cart-total">0.00</span></strong>
        </div>
        <button type="button" class="btn btn-danger" id="clear-cart">Clear Cart</button>
        <button type="button" class="btn btn-primary" id="checkout-btn">Checkout</button>
    </div>
</div>



<?php include('../components/footer.php'); ?>  <!-- 页脚 -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/nutribox/assets/js/menu.js"></script>

</body>
</html>
