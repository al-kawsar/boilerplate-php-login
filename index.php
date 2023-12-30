<?php
require_once __DIR__ . './config.php';
session_start();

isLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

<?php if(isset($_SESSION['login'])): ?>
<h1>Hallo <?= ($_SESSION['nama']) ?></h1>
<h1><a href="logout.php">Logout</a></h1>
<?php else: ?>
<h1><a href="sign-in.php">Login</a></h1>
<h1><a href="sign-up.php">Register</a></h1>
<?php endif; ?>


</body>
</html>