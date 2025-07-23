<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: signin.php");
    exit;
}

$user = $_SESSION["user"];
?>

<h2>Welcome, <?= htmlspecialchars($user["first_name"]); ?>!</h2>
<p><strong>Email:</strong> <?= htmlspecialchars($user["email"]); ?></p>
<p><strong>Gender:</strong> <?= htmlspecialchars($user["gender"]); ?></p>
<p><strong>Hobbies:</strong> <?= htmlspecialchars($user["hobbies"]); ?></p>

<?php if (!empty($user['profile_photo'])): ?>
    <p><strong>Profile Photo:</strong></p>
    <img src="uploads/<?= htmlspecialchars($user['profile_photo']); ?>" alt="Profile Photo" style="max-width:150px; border-radius:8px;">
<?php endif; ?>

<p><a href="logout.php">Logout</a></p>


<link rel="stylesheet" type="text/css" href="style.css">
