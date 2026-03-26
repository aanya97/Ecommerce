
<?php
session_start();
include 'config.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty!</h2>";
    echo "<a href='index.php'>Continue Shopping</a>";
    exit;
}

// Remove item
if (isset($_POST['remove'])) {
    $remove_id = $_POST['remove_id'];
    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['id'] == $remove_id) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}

// Update quantity
if (isset($_POST['update'])) {
    $update_id = $_POST['update_id'];
    $new_quantity = (int)$_POST['new_quantity'];
    foreach ($_SESSION['cart'] as $index => $cart_item) {
        if ($cart_item['id'] == $update_id) {
            if ($new_quantity > 0) {
                $_SESSION['cart'][$index]['quantity'] = $new_quantity;
            } else {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>
    <a href="index.php">Continue Shopping</a>
    <hr>

    <?php
    $total = 0;
    foreach ($_SESSION['cart'] as $cart_item) {
        $subtotal = $cart_item['price'] * $cart_item['quantity'];
        $total += $subtotal;
    ?>
        <div>
            <h3><?php echo $cart_item['name']; ?></h3>
            <p>Price: ₹<?php echo $cart_item['price']; ?></p>
            <p>Quantity: <?php echo $cart_item['quantity']; ?></p>
            <p>Subtotal: ₹<?php echo $subtotal; ?></p>

            <!-- Remove -->
            <form method="post" style="display:inline;">
                <input type="hidden" name="remove_id" value="<?php echo $cart_item['id']; ?>">
                <button type="submit" name="remove">Remove</button>
            </form>

            <!-- Update -->
            <form method="post" style="display:inline;">
                <input type="hidden" name="update_id" value="<?php echo $cart_item['id']; ?>">
                <input type="number" name="new_quantity" value="<?php echo $cart_item['quantity']; ?>" min="0">
                <button type="submit" name="update">Update</button>
            </form>
        </div>
    <?php } ?>

    <hr>
    <h2>Total: ₹<?php echo $total; ?></h2>

    <!-- Checkout -->
    <a href="checkout.php" style="padding:10px; background:#007bff; color:white; text-decoration:none; border-radius:5px;">
        Proceed to Checkout
    </a>
</body>
</html>
