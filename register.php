
<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // secure hashing

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name','$email','$password')";
    if (mysqli_query($conn, $sql)) {
        echo "Signup successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
        <script>
        function validateregisterForm() {
            let name = document.forms["registerForm"]["name"].value.trim();
            let email = document.forms["registerForm"]["email"].value.trim();
            let password = document.forms["registerForm"]["password"].value.trim();

             // Regex patterns
            let namePattern = /^[A-Za-z ]+$/;   // only letters and spaces
            let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/; // basic email format

            if (name === "" || !namePattern.test(name)) {
                alert("Please enter a valid name (letters and spaces only).");
                return false;
            }
            if (email === "" || !emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            return true; // allow submission
        }
    </script>
</head>
<body>
    <h2>Register</h2>
    <form name="registerForm" method="POST" onsubmit="return validateForm()">
        <input type="text" name="name" placeholder="Name" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
