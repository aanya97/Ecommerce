
<?php
session_start();
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}
include 'config.php';
// Calculate cart count
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity']; // sum all quantities
    }
}

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    $item = [
        'id' => $_POST['product_id'],
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'image' => $_POST['image'],
        'quantity' => 1
    ];

    $found = false;
    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['id'] == $_POST['product_id']) {
            $_SESSION['cart'][$index]['quantity'] += 1; // update quantity
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['cart'][] = $item;
    }

    header("Location: cart.php");
    exit;
}

// Fetch products from database
$result = mysqli_query($conn, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My E-Commerce Site</title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <h1>Welcome to My E-Commerce Site</h1>

    <!-- Navbar -->
      <nav style="background:#f8f9fa; padding:10px;">
        <a href="index.php">Home</a> |
        <a href="cart.php">Cart (<?php echo $cart_count; ?>)</a>
    </nav>
 <!-- Show login/register links if user not logged in -->
    <?php if(!isset($_SESSION['user_id'])) { ?>
        <p>
            <a href="login.php">Login</a> | 
            <a href="register.php">Register</a>
        </p>
    <?php } else { ?>
        <p>You are logged in! <a href="logout.php">Logout</a></p>
    <?php } ?>
    <!-- Product Listing -->
    <div class="products">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <div class="product">
                <img src="asset/images/<?php echo $row['image_path']; ?>" width="150">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p>₹<?php echo $row['price']; ?></p>
                <p>Stock: <?php echo $row['stock']; ?></p>
                <form method="POST" action="">
                   <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                   <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                   <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                   <input type="hidden" name="image" value="<?php echo $row['image_path']; ?>">
                   <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>
