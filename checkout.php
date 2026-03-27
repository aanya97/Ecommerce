<?php
session_start();
include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<h2>Please log in to place an order.</h2>";
    exit;
}

// Ensure cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty!</h2>";
    echo "<a href='index.php'>Continue Shopping</a>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $phone   = $_POST['phone'];

    // Calculate total securely from cart
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_address, customer_phone, total) 
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $user_id, $address, $phone, $total);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // get auto-generated order_id

        // Insert order items
        $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                                      VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $stmt_items->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt_items->execute();
        }

        unset($_SESSION['cart']); // clear cart

        echo "<h2>Order placed successfully! Your Order ID is #$order_id</h2>";
        echo "<a href='index.php'>Continue Shopping</a>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
     <script>
        function validateForm() {
            let phone = document.forms["checkoutForm"]["phone"].value;
            let phonePattern = /^[0-9]{10}$/; // exactly 10 digits

            if (!phonePattern.test(phone)) {
                alert("Please enter a valid 10-digit phone number.");
                return false; // prevent form submission
            }
            return true; // allow submission
        }
    </script>

</head>
<body>
    <h1>Checkout</h1>
    <form name="checkoutForm" method="post" onsubmit="return validateForm()">
        <label>Address:</label><br>
        <textarea name="address" required></textarea><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" required><br><br>

        <button type="submit" name="place_order">Place Order</button>
    </form>
</body>
</html>
