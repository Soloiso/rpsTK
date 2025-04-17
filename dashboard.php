<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$db = "project";
$connection = mysqli_connect($host, $dbUsername, $dbPassword, $db);
$sql = "SELECT r.username, s.wins, s.losses, s.draws, s.gamesPlayed
        FROM rpsaccounts r
        JOIN rpsstats s ON r.Id = s.userId
        ORDER BY s.wins DESC";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <script>
        function filterTable() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("#leaderboard tbody tr");
            rows.forEach(row => {
                let name = row.querySelector("td").textContent.toLowerCase();
                row.style.display = name.includes(input) ? "" : "none";
            });
        }
        function refreshPage() {
            window.location.reload();
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h3>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <div class="d-flex mb-3 gap-2">
        <input type="text" id="search" onkeyup="filterTable()" placeholder="Search by username..." class="form-control w-50">
        <button onclick="refreshPage()" class="btn btn-secondary">Refresh</button>
    </div>
    <table class="table table-bordered table-striped" id="leaderboard">
        <thead class="table-dark">
        <tr>
            <th>Username</th>
            <th>Wins</th>
            <th>Losses</th>
            <th>Draws</th>
            <th>Total Games</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['wins'] ?></td>
                <td><?= $row['losses'] ?></td>
                <td><?= $row['draws'] ?></td>
                <td><?= $row['gamesPlayed'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
