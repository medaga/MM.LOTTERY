
<?php
session_start();

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT id, full_name, password
         FROM users
         WHERE email = ?"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        // Regenerate session ID
        session_regenerate_id(true);

        // Save user information
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["full_name"] = $user["full_name"];

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();

    } else {

        $message = "Incorrect email or password.";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Lottery Simulation</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="register-box">

        <h2>Login</h2>

        <p class="description">
            Login to your account
        </p>

        <form method="POST" action="login.php">

            <label>Email Address</label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                required
            >

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

            <button type="submit">Login</button>

        </form>

        <p class="description">
            <?php echo htmlspecialchars($message); ?>
        </p>

        <p class="login-link">
            Don't have an account?
            <a href="register.html">Register</a>
        </p>

    </div>

</body>
</html>
