<?php
session_start();
require_once 'config.php';
include 'header.php'; 

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // This is to Insert contents into my project_db
    $query = "INSERT INTO content (title, content) VALUES ('$title', '$content')";
    if ($conn->query($query) === TRUE) {
        echo "Content created successfully.";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

?>

<form action="" method="post">
    <input type="text" name="title" placeholder="Title">
    <textarea name="content" placeholder="Content"></textarea>
    <button type="submit" name="create">Create Content</button>
</form>

<p><a href="dashboard.php">Back to Dashboard</a></p>

<?php include 'footer.php';  ?>
