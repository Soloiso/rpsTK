<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['typeId'] != 2) {
    header("Location: login.php");
    exit;
}
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$db = "project";
$conn = mysqli_connect($host, $dbUsername, $dbPassword, $db);
if (isset($_GET['action']) && isset($_GET['id'])) {
    $userId = $_GET['id'];
    if ($_GET['action'] === 'reset') {
        mysqli_query($conn, "UPDATE rpsstats SET wins=0, losses=0, draws=0, gamesPlayed=0 WHERE userId=$userId");
    }
    if ($_GET['action'] === 'promote') {
        mysqli_query($conn, "UPDATE rpsaccounts SET typeId=2 WHERE Id=$userId");
    }
    if ($_GET['action'] === 'demote') {
        mysqli_query($conn, "UPDATE rpsaccounts SET typeId=1 WHERE Id=$userId");
    }
    if ($_GET['action'] === 'delete') {
        mysqli_query($conn, "DELETE FROM rpsstats WHERE userId=$userId");
        mysqli_query($conn, "DELETE FROM rpsaccounts WHERE Id=$userId");
    }
    header("Location: admin.php");
    exit;
}
$sql = "SELECT r.Id, r.username, r.creationDate, r.typeId, 
               s.wins, s.losses, s.draws, s.gamesPlayed
        FROM rpsaccounts r
        LEFT JOIN rpsstats s ON r.Id = s.userId
        ORDER BY r.creationDate DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <script>
        function filterTable() {
            let input=document.getElementById("search").value.toLowerCase();
            let rows=document.querySelectorAll("#adminTable tbody tr");
            rows.forEach(row=>{
                let username=row.querySelector("td").textContent.toLowerCase();
                row.style.display=username.includes(input)?"":"none";
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
        <h3>Admin Dashboard</h3>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <div class="d-flex mb-3 gap-2">
        <input type="text" id="search" onkeyup="filterTable()" class="form-control w-50" placeholder="Search by username...">
        <button onclick="refreshPage()" class="btn btn-secondary">Refresh</button>
    </div>
    <table class="table table-bordered table-striped" id="adminTable">
        <thead class="table-dark">
        <tr>
            <th>Username</th>
            <th>Created On</th>
            <th>Role</th>
            <th>Wins</th>
            <th>Losses</th>
            <th>Draws</th>
            <th>Games</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['creationDate'] ?></td>
                <td><?= $row['typeId'] == 2 ? 'Admin' : 'User' ?></td>
                <td><?= $row['wins'] ?? 0 ?></td>
                <td><?= $row['losses'] ?? 0 ?></td>
                <td><?= $row['draws'] ?? 0 ?></td>
                <td><?= $row['gamesPlayed'] ?? 0 ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <a href="admin.php?action=reset&id=<?= $row['Id'] ?>" class="btn btn-warning btn-sm">Reset Stats</a>
                    <?php if ($row['typeId'] == 1): ?>
                        <a href="admin.php?action=promote&id=<?= $row['Id'] ?>" class="btn btn-success btn-sm">Promote</a>
                    <?php elseif ($row['Id'] != $_SESSION['user_id']): ?>
                        <a href="admin.php?action=demote&id=<?= $row['Id'] ?>" class="btn btn-secondary btn-sm">Demote</a>
                    <?php endif; ?>
                    <?php if ($row['Id'] != $_SESSION['user_id']): ?>
                        <a href="admin.php?action=delete&id=<?= $row['Id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
