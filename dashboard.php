
<?php
session_start();

require_once "config/database.php";

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Get the user's wallet balance
$stmt = $conn->prepare(
    "SELECT balance FROM wallets WHERE user_id = ?"
);

$stmt->execute([$_SESSION["user_id"]]);

$wallet = $stmt->fetch(PDO::FETCH_ASSOC);

$balance = $wallet ? $wallet["balance"] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Lottery Simulation</title>

    <link rel="stylesheet" href="dash.css">
</head>

<body>

    <div class="dashboard">

        <header class="dashboard-header">

            <h2>LUCKY DRAW</h2>

            <a href="logout.php" class="logout">
                Logout
            </a>

        </header>

        <main>

            <h1>
                Welcome,
                <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!
            </h1>

            <p class="welcome-text">
                Welcome to your Lottery Simulation Dashboard.
            </p>

            <div class="dashboard-cards">

                <div class="dashboard-card">
                    <h3>My Wallet</h3>

                    <h2>
                        <?php echo number_format((float)$balance, 2); ?>
                    </h2>

                    <p>Simulated balance</p>
                </div>

                <div class="dashboard-card">
                    <h3>My Numbers</h3>
                    <h2>0</h2>
                    <p>Selected numbers</p>
                </div>

                <div class="dashboard-card">
                    <h3>Draw Status</h3>
                    <h2>Open</h2>
                    <p>Simulation status</p>
                </div>

            </div>

            <div class="dashboard-section">

                <h2>Lottery Activities</h2>

                <div class="activity-list">

                    <div class="activity">
                        <h3>Choose Number</h3>
                        <p>Select one available number from 1,000 numbers.</p>
                        <span>Coming next</span>
                    </div>

                    <div class="activity">
                        <h3>Transaction History</h3>
                        <p>View your simulated wallet transactions.</p>
                        <span>Coming next</span>
                    </div>

                    <div class="activity">
                        <h3>Draw Results</h3>
                        <p>View the automatic lottery simulation result.</p>
                        <span>Coming next</span>
                    </div>

                </div>

            </div>

        </main>

    </div>

</body>
</html>
