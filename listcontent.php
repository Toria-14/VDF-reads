<style>
    .cards {
    margin-top: 100px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}
</style>
<?php
session_start();
require_once 'config.php';
include 'header.php'; 


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Fetches all uploaded content and displays them
$query = "SELECT * FROM content";
$result = $conn->query($query);

echo "<div class='cards'>";

if ($result->num_rows > 0) {
    while ($content = $result->fetch_assoc()) {
        echo "<div class='card'>";

        echo "<div class='content-preview'>";
        
        // makes titles clickable for each different roles
        if ($_SESSION['role'] == 'user') {
            echo "<a href='view.php?content_id=" . $content['id'] . "'>";
            echo "<h2>" . htmlspecialchars($content['title']) . "</h2>";
            echo "</a>";
        } else {
            echo "<h2>" . htmlspecialchars($content['title']) . "</h2>";
        }
        
        echo "<p>" . substr(nl2br(htmlspecialchars($content['content'])), 0, 200) . "...</p>";

        // allows edit for only admin and editor
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor') {
            echo "<a href='edit.php?content_id=" . $content['id'] . "' class='edit-link'>Edit</a>";
        }

        echo "</div></div>";
    }
} else {
    echo "No content available.";
}
echo "</div>";
echo "</br>";
echo "<p><a href='dashboard.php'>Back to Dashboard</a></p>";

?>
<?php include 'footer.php'; ?>