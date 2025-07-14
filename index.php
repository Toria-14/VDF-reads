<!DOCTYPE html>
<style>
    .login-btn{
    color: white;
    background-color: #6c5ce7;
    padding: 10px 15px;
    margin-top: 20px;
    border-radius: 8px;
    font-weight: bold;
    max-width: 100%;
}
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

<div class="hero">

<h1>VDF</h1>
Step into the realm of words, where emotions unfold like a canvas of the soul, and let the poetic whispers transport you to a world of depth, passion, and timeless beauty.
<br><br>
</div>

<?php
session_start();
require_once 'config.php';
include 'header.php';

// Fetches only 3 content items from the database based on the recent uploade
$query = "SELECT * FROM content LIMIT 3";
$result = $conn->query($query);
echo "<div class='cards'>";
if ($result->num_rows > 0) {
    while ($content = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<h2>" . $content['title'] . "</h2>";
        echo "<p>" . substr($content['content'], 0, 200) . "...</p>"; 

        if (!isset($_SESSION['id'])) {
            // here takes you back to login.php with content_id in URL
            echo "<a href='login.php?content_id=" . $content['id'] . "'>Login to view full content</a>";
            echo " | <a href='register.php'>Register here</a>";
        } else {
            
            echo "<a href='view.php?content_id=" . $content['id'] . "'>View Full Content</a>";
        }
        echo "</div>";
        
    }
} else {
    echo "No content available.";
}
echo "</div>";
echo "<br>";
echo "<a href='login.php' class='login-btn'> Login</a>";

include 'footer.php';

?>
</html>
