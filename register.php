<?php
// register.php

// Database connection
$servername = "localhost";
$username = "root";  // your MySQL username
$password = "";      // your MySQL password
$dbname = "test";    // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Password validation
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $result = $conn->query($checkQuery);
    
    if ($result->num_rows > 0) {
        die("Username or Email already exists.");
    }

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! You can now <a href='login.html'>login</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
