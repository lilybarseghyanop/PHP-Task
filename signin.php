<?php
require 'config.php';

session_start();

$error = "";
$emailRaw = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailRaw = trim($_POST["email"] ?? '');
    $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"] ?? '';

    if (!$email) {
        $error = "Please enter a valid email.";
    } elseif (!$password) {
        $error = "Please enter your password.";
    } else {
        // Prepare statement with MySQLi
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user;
            header("Location: home.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    }
}
?>

<h2>Sign In</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" autocomplete="off">
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($emailRaw) ?>" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Sign In</button>
</form>

<p>Don't have an account? <a href="signup.php">Sign Up</a></p>


<link rel="stylesheet" type="text/css" href="style.css">