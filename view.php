<?php
session_start();
require_once 'config.php';
include 'header.php'; 

echo "<p><a href='listcontent.php'>Go Back </a></p>";


if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// Set content ID early before it's used
$contentId = isset($_GET['content_id']) ? $_GET['content_id'] : null;

// this allows Role-based actions
if ($_SESSION['role'] == 'admin') {
    echo "<h1>Hi, " . htmlspecialchars($_SESSION['name']) . "! Admin</h1>";
    if ($contentId) {
        echo "<a href='content.php'>Create A Content</a><br><br>";
        echo "<a href='edit.php?content_id=" . $contentId . "'>Edit Content</a><br>";
    }
} elseif ($_SESSION['role'] == 'editor') {
    echo "<h1>Hi " . htmlspecialchars($_SESSION['name']) . "! Editor</h1>";
    if ($contentId) {
        echo "<a href='edit.php?content_id=" . $contentId . "'>Edit Content</a><br>";
    }
} else {
    echo "<h1>Hi, " . htmlspecialchars($_SESSION['name']) . "! </h1>";
}



// Fetch content from project_db based on content ID
if (isset($_GET['content_id'])) {
    $contentId = $_GET['content_id'];
    $query = "SELECT * FROM content WHERE id = '$contentId'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc();
    } else {
        echo "Content not found.";
        exit;
    }
} else {
    echo "No content ID provided.";
    exit;
}



// Display content
echo "<h2>" . $content['title'] . "</h2>";
echo "<p>" . $content['content'] . "</p>";


// Comment form
echo "<br><br><form action='' method='post' class='comment-form'>";
echo "<textarea name='comment' placeholder='Write a comment...' required class='comment-area'></textarea>";
echo "<input type='hidden' name='content_id' value='$contentId'>";
echo "<button type='submit' name='submit_comment'>Post Comment</button>";
echo "</form>";

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    $userId = $_SESSION['id'];
    $comment = htmlspecialchars($_POST['comment']);

    // Insert comment into database
    $insertCommentQuery = "INSERT INTO comments (content_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertCommentQuery);
    $stmt->bind_param("iis", $contentId, $userId, $comment);

    if ($stmt->execute()) {
        header("Location: view.php?content_id=" . $contentId); // Refresh page to display new comment
        exit;
    } else {
        echo "<p>Error posting comment: " . $conn->error . "</p>";
    }
}

// Display comments
$commentQuery = "SELECT c.comment, u.name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.content_id = '$contentId'";
$commentResult = $conn->query($commentQuery);

if ($commentResult->num_rows > 0) {
    echo "<h3>Comments:</h3>";
    while ($comment = $commentResult->fetch_assoc()) {
        echo "<p class='comment'><strong>" . $comment['name'] . ":</strong> " . $comment['comment'] . "</p>";
    }
} else {
    echo "<p>No comments yet.</p>";
}

// Display other content links
$query = "SELECT * FROM content WHERE id != '$contentId'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h3>Other Contents:</h3>";
    while ($otherContent = $result->fetch_assoc()) {
        echo "<h4><a href='view.php?content_id=" . $otherContent['id'] . "'>" . $otherContent['title'] . "</a></h4>";
    }
} else {
    echo "<p>No other contents available.</p>";
}
echo "<br><br><a href='logout.php'>Logout</a><br>";


?>
<?php include 'footer.php'; ?>
