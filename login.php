<?php
// Start a session
session_start();

// Database connection credentials
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sabkaghar";

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // We'll verify this against the hashed password
    
    // SQL query to check if user exists
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session and redirect to homepage or dashboard
            $_SESSION['user_id'] = $user['user_id'];  // Changed from 'id' to 'user_id'
            $_SESSION['email'] = $user['email'];
            
            // If remember me is checked, set a cookie
            if (isset($_POST['remember'])) {
                setcookie("user_login", $user['email'], time() + (30 * 24 * 60 * 60), "/");
            }
            
            header("Location: index.html"); // Redirect to another page after login
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}

// Close the database connection
$conn->close();

// If there's an error, redirect back to the login page with an error message
if (isset($error)) {
    $_SESSION['login_error'] = $error;
    header("Location: login.html");
    exit();
}
?>