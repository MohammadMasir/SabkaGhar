<?php
// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "sabkaghar";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Print out the POST data
    echo "<pre>"; print_r($_POST); echo "</pre>";

    // Get the input from the form
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $re_password = mysqli_real_escape_string($conn, $_POST['re_password']);
    
    // Check if passwords match
    if ($password === $re_password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert into the database
        $sql = "INSERT INTO user (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
        
        if ($conn->query($sql) === TRUE) {
            echo "User registered successfully! User ID: " . $conn->insert_id . "<br>";
            echo "Redirecting to login page in 5 seconds...";
            echo "<script>setTimeout(function(){ window.location.href = 'login.html'; }, 5000);</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Passwords do not match!";
    }
}

// Close the connection
$conn->close();
?>