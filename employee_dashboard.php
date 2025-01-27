<?php
session_start();

// Check if employee is logged in
if (!isset($_SESSION['employee_logged_in'])) {
    header("Location: index.php");
    exit();
}

$employee_name = $_SESSION['employee_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo htmlspecialchars($employee_name); ?>!</h1>
        <p>This is your dashboard.</p>
        <a href="index.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
