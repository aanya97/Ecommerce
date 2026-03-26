
<?php
session_start();   //  Start session so we can store user info
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {//  Check if form was submitte

    $email = $_POST['email'];// 4. Get email from form
    $password = $_POST['password'];// 4. Get password from form
   //find user in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);//create result in the form of associative array 

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];//  Store user info in session
        $_SESSION['name'] = $user['name'];
        //  Redirect to homepage
        header("Location: index.php");
    } else {
        echo "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <h2>Login</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">  <!--$_SERVER['PHP_SELF'] it will take data on the same page we can also use action="" also for that  -->

        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register here</a></p>
</body>
</html>
