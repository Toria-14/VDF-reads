<?php
session_start();
require_once 'config.php';
include 'header.php'; // 

if (!isset($_SESSION['id']) || ($_SESSION['role'] != 'editor' && $_SESSION['role'] != 'admin')) {
    header("Location: login.php");
    exit;
}

// used for fetching content from my project_db based on content ID
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

// Updates content if i submitted an edited content
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $contentText = $_POST['content'];

    // Updating content in my project_db
    $updateQuery = "UPDATE content SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $title, $contentText, $contentId);

    if ($stmt->execute()) {
        echo "Content updated successfully.";
        header("Location: listcontent.php"); 
        exit;
    } else {
        echo "Error updating content: " . $conn->error;
    }
}

?>

<form action="" method="post">
    <input type="text" name="title" value="<?php echo $content['title']; ?>" placeholder="Title">
    <textarea name="content" placeholder="Content"><?php echo $content['content']; ?></textarea>
    <button type="submit" name="update">Update Content</button>
</form>

<p><a href="listcontent.php">Back to Contents</a></p>

<?php include 'footer.php'; ?>
