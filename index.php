<?php
// Start session
session_start();

// Check if the user is already logged in, if so redirect to dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit();
}

// Include the database connection file
require_once 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation
    if (empty($email) || empty($password)) {
        $errors[] = "Both fields are required.";
    } else {
        // Check email and password
        $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $email, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                // Set session variables and redirect to dashboard
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                header("Location: dashboard.php");
                exit();
            } else {
                $errors[] = "Invalid credentials.";
            }
        } else {
            $errors[] = "No user found with that email.";
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
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 300px; margin: 50px auto; }
        h2 { text-align: center; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 10px; margin: 8px 0; box-sizing: border-box;
        }
        input[type="submit"], .register-btn {
            width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;
        }
        input[type="submit"]:hover, .register-btn:hover {
            background-color: #45a049;
        }
        .error { color: red; font-size: 14px; }
        .register-btn { margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($errors)) { echo '<div class="error">' . implode('<br>', $errors) . '</div>'; } ?>
        <form action="index.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>

        <form action="register.php" method="GET">
            <button type="submit" class="register-btn">Register</button>
        </form>
    </div>
</body>
</html>
