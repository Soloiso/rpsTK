<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RPS Sign Up</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-4 border rounded shadow-lg p-4">
            <h2 class="mb-4 text-center">RPS Sign Up</h2>
            <?php if (isset($_GET['error'])): ?>
                <label class="text-danger w-100 text-center mb-3">Error: Please try again.</label>
            <?php elseif (isset($_GET['success'])): ?>
                <label class="text-success w-100 text-center mb-3">Account created successfully!</label>
            <?php endif; ?>
            <form action="signup.php" method="post">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="mb-3 text-center">
                    <input type="submit" name="submit" value="Sign Up" class="btn btn-primary w-100">
                </div>
            </form>
            <div class="mt-3 text-center">
                <button onclick="window.location.href='login.php'" class="btn btn-secondary w-100">Go Back</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$db = "project";
$connection = mysqli_connect($host, $dbUsername, $dbPassword, $db);
if (!$connection) {
    die("Connection failed: ".mysqli_connect_error());
}
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password!==$confirm_password) {
        header("Location: signup.php?error=1");
        exit;
    }
    $checkUserQuery="SELECT * FROM rpsaccounts WHERE username='$username'";
    $checkUserResult=mysqli_query($connection, $checkUserQuery);
    if (mysqli_num_rows($checkUserResult) > 0) {
        header("Location: signup.php?error=2");
        exit;
    }
    $insertAccountQuery = "INSERT INTO rpsaccounts (username, password_) VALUES ('$username', '$password')";
    $insertAccountResult = mysqli_query($connection, $insertAccountQuery);
    if ($insertAccountResult) {
        $userIdQuery = "SELECT id FROM rpsaccounts WHERE username='$username'";
        $userIdResult = mysqli_query($connection, $userIdQuery);
        if (mysqli_num_rows($userIdResult) === 1) {
            $userId = mysqli_fetch_assoc($userIdResult)['id'];
            $insertStatsQuery = "INSERT INTO rpsstats (userId) VALUES ('$userId')";
            $insertStatsResult = mysqli_query($connection, $insertStatsQuery);
            if($insertStatsResult) {
                header("Location: signup.php?success=1");
                exit;
            }else{
                header("Location: signup.php?error=3");
                exit;
            }
        }else{
            header("Location: signup.php?error=3");
            exit;
        }
    }else{
        header("Location: signup.php?error=4");
        exit;
    }
}
?>
