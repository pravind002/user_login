<?php
// Include the database connection file
require_once 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already exists.";
        } else {
            // Hash password and insert user into database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                // Redirect to login after successful registration
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Error in registration.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 50px auto; }
        h2 { text-align: center; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (!empty($errors)) { echo '<div class="error">' . implode('<br>', $errors) . '</div>'; } ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" value="Register">
        </form>
        <div class="link">
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
