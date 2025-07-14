
<?php
session_start();
require_once 'config.php';
include 'header.php';


$content_id = isset($_GET['content_id']) ? $_GET['content_id'] : null;

// Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $content_id = $_POST['content_id']; // 

    if (empty($email) || empty($password)) {
        echo "Please fill all fields.";
    } else {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // you can either log in to content or dashboard
                if (!empty($content_id)) {
                    header("Location: view.php?content_id=" . $content_id);
                } else {
                    header("Location: dashboard.php");
                }
                exit;
            } else {
                echo '<div class="error-msg">Incorrect password.</div>';

            }
        } else {
            echo '<div class="error-msg">Incorrect Email.</div>';

        }
    }
}
?>
<br><br>

<div class="main-content">
  <h1>VDF!</h1>
  <p>Step into the realm of words, where emotions unfold like a canvas of the soul...</p>
    

<a href="index.php">Go back</a>
<!-- Login Form -->
<h2>Login</h2>
<form action="login.php<?php echo ($content_id ? '?content_id=' . $content_id : ''); ?>" method="post">
<input type="hidden" name="content_id" value="<?= htmlspecialchars($content_id ?? '') ?>">
<input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button type="submit" name="login">Login</button>
    <a href="forgot-password.php">Forgot Password?</a>
</form>
</div>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include 'footer.php'; ?>
