
<?php

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {

        $message = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters.";

    } else {

        try {

            $conn->beginTransaction();

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO users
                    (full_name, email, password)
                    VALUES (?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $name,
                $email,
                $hashedPassword
            ]);

            $userId = $conn->lastInsertId();

            $sql = "INSERT INTO wallets
                    (user_id, balance)
                    VALUES (?, 0.00)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);

            $conn->commit();

            header("Location: login.php?registered=1");
            exit();

        } catch (PDOException $e) {

            if ($conn->inTransaction()) {
                $conn->rollBack();
            }

            if ($e->getCode() === "23000") {
                $message = "This email is already registered.";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Error</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="register-box">

        <h2>Registration</h2>

        <p class="description">
            <?php echo htmlspecialchars($message); ?>
        </p>

        <a href="register.html">Back to Registration</a>

    </div>

</body>
</html>
