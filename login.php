<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Sanitize input
    $user = stripslashes($user);
    $user = mysqli_real_escape_string($conn, $user);
    $pass = stripslashes($pass);
    $pass = mysqli_real_escape_string($conn, $pass);

    // Fetch user from the database
    $sql = "SELECT id, username, password FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Check if the password matches
        $row = $result->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            // Password is correct, start a session
            $_SESSION['username'] = $user;
            header("Location: welcome.php");
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}

$conn->close();
?>