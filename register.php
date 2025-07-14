<?php
session_start();
require_once 'config.php';
include 'header.php'; 

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // checks is details are inputed 
    if (empty($name) || empty($email) || empty($password)) {
        echo "Please fill all fields.";
        exit;
    }

    // It Checks if email already exists and avoids duplicate
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "Email already exists.";
        exit;
    }

    // passwords are hidden with hashes
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // when user is created it is inserted into database
    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashedPassword', '$role')";
    if ($conn->query($query) === TRUE) {
        echo "User created successfully.";
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

?>

<div class="main-content">
<a href="index.php">Go back</a>
<h2>Registeration</h2>
<form action="" method="post">
    <input type="text" name="name" placeholder="Username"><br><br>
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    Choose Your Role:
    <select name="role">
        <option value="admin">Admin</option>
        <option value="editor">Editor</option>
        <option value="user">User</option>
    </select><br><br>
    <button type="submit" name="register">Register</button>
</form>
</div>

<p>Already have an account? <a href="login.php">Back to login page</a></p>
<!-- <?php include 'footer.php'; ?> -->
