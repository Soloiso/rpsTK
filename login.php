<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RPS Login</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-4 border rounded shadow-lg p-4">
            <h2 class="mb-4 text-center">RPS Login</h2>
            <?php if (isset($_GET['error'])): ?>
                <label class="text-danger w-100 text-center mb-3">Account does not exist or password is incorrect.</label>
            <?php endif; ?>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 text-center">
                    <input type="submit" name="submit" value="Login" class="btn btn-primary w-100">
                </div>
            </form>
            <div class="mt-3 text-center">
                <button onclick="location.href='signup.php'" class="btn btn-secondary w-100">Sign Up</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();
$host="localhost";
$dbUsername="root";
$dbPassword="";
$db="project";
$connection=mysqli_connect($host, $dbUsername, $dbPassword, $db);
if(!$connection){
    die("Connection failed: ".mysqli_connect_error());
}
if(isset($_POST['submit'])) {
    $username=$_POST['username'];
    $password=$_POST['password'];
    $sql="select * from rpsaccounts where username='$username' and password_='$password'";
    $result=mysqli_query($connection,$sql);
    if(mysqli_num_rows($result)===1){
        $user=mysqli_fetch_assoc($result);
        $_SESSION['username']=$user['username'];
        $_SESSION['typeId']=$user['typeId'];
        if($user['typeId']==2){
            header("Location: admin.php");
        }else{
            header("Location: dashboard.php");  
        }
        exit;
    }else{
        header("Location: login.php?error=1");
        exit;}
}
?>
