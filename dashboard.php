<?php
session_start();
require_once 'config.php';
include 'header.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// This Displays a view based on each and different registered role
if ($_SESSION['role'] == 'admin') {
    echo "<h1>Hi, " . htmlspecialchars($_SESSION['name']) . "! Admin</h1>";
    echo "<a href='content.php'>Create Content</a><br><br>";
    echo "<a href='listcontent.php'>View and edit content</a>";

} elseif ($_SESSION['role'] == 'editor') {
    echo "<h1>Hi, " . htmlspecialchars($_SESSION['name']) . "! Editor</h1>";
    echo "<a href='listcontent.php'>View and Edit Content</a>";
} else {
    echo "<h1>Hi, " . htmlspecialchars($_SESSION['name']) . "! User</h1>";
    echo "<a href='listcontent.php'>View Content</a>";
}

echo "<br><br><a href='logout.php'>Logout</a>";

?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<?php include 'footer.php'; ?>
