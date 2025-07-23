<?php
require 'config.php';

$errors = [];
$first = $last = $email = $gender = "";
$hobbies = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first = htmlspecialchars(trim($_POST["first_name"] ?? ''));
    $last = htmlspecialchars(trim($_POST["last_name"] ?? ''));
    $emailRaw = trim($_POST["email"] ?? '');
    $email = filter_var($emailRaw, FILTER_VALIDATE_EMAIL);
    $passwordRaw = $_POST["password"] ?? '';
    $confirmPasswordRaw = $_POST["confirm_password"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $hobbies = $_POST["hobbies"] ?? [];

    // Validate inputs
    if (!$first) $errors[] = "First name is required.";
    if (!$last) $errors[] = "Last name is required.";
    if (!$email) $errors[] = "A valid email is required.";
    if (strlen($passwordRaw) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($passwordRaw !== $confirmPasswordRaw) $errors[] = "Passwords do not match.";
    if (!$gender) $errors[] = "Gender is required.";
    if (empty($hobbies)) $errors[] = "Select at least one hobby.";

    // Profile photo validation
    if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Profile photo is required.";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $fileType = $_FILES['profile_photo']['type'];
        $fileSize = $_FILES['profile_photo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = "Only JPG and PNG files are allowed for profile photo.";
        }
        if ($fileSize > 2 * 1024 * 1024) {
            $errors[] = "Profile photo size must be less than 2MB.";
        }
    }

    if (empty($errors)) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered. Please use another email or sign in.";
            $stmt->close();
        } else {
            $stmt->close();

            // Upload profile photo
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('profile_', true) . "." . $ext;
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
                $password = password_hash($passwordRaw, PASSWORD_DEFAULT);

                $hobbiesStr = implode(", ", $hobbies);

                // Insert into DB with MySQLi prepared statement
                $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, gender, hobbies, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $first, $last, $email, $password, $gender, $hobbiesStr, $fileName);

                if ($stmt->execute()) {
                    header("Location: signin.php");
                    exit;
                } else {
                    $errors[] = "Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $errors[] = "Failed to upload profile photo.";
            }
        }
    }
}
?>

<h2>Sign Up</h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="first_name" placeholder="First Name" value="<?= htmlspecialchars($first) ?>" required><br>
    <input type="text" name="last_name" placeholder="Last Name" value="<?= htmlspecialchars($last) ?>" required><br>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($emailRaw ?? '') ?>" required><br>

    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>

    Gender:<br>
    <label><input type="radio" name="gender" value="Male" <?= ($gender === 'Male') ? 'checked' : '' ?> required> Male</label>
    <label><input type="radio" name="gender" value="Female" <?= ($gender === 'Female') ? 'checked' : '' ?>> Female</label><br>

    Hobbies:<br>
    <label><input type="checkbox" name="hobbies[]" value="Reading" <?= in_array("Reading", $hobbies) ? 'checked' : '' ?>> Reading</label>
    <label><input type="checkbox" name="hobbies[]" value="Gaming" <?= in_array("Gaming", $hobbies) ? 'checked' : '' ?>> Gaming</label>
    <label><input type="checkbox" name="hobbies[]" value="Traveling" <?= in_array("Traveling", $hobbies) ? 'checked' : '' ?>> Traveling</label><br>

    Profile Photo:<br>
    <input type="file" name="profile_photo" accept=".jpg,.jpeg,.png" required><br>

    <button type="submit">Sign Up</button>
</form>

<p>Already have an account? <a href="signin.php">Sign In</a></p>
<link rel="stylesheet" type="text/css" href="style.css">