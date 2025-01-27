<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch new employee requests
$sql_pending = "SELECT * FROM registration WHERE status='pending'";
$result_pending = $conn->query($sql_pending);

// Fetch all accepted users
$sql_users = "SELECT * FROM registration WHERE status='accepted'";
$result_users = $conn->query($sql_users);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar and Layout Styles */
        body {
            display: flex;
        }

        .sidebar {
            background-color:rgb(58, 69, 80);
            color: white;
            height: 100vh;
            padding-top: 30px;
            width: 250px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #575d63;
        }

        .main-content {
            flex: 1;
            padding: 30px;
        }

        /* Navbar Style */
        nav {
            background-color:rgb(72, 100, 106);
        }

        nav .navbar-brand {
            color: white;
        }

        /* Dashboard Section Styles */
        .notification-bar {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .btn-logout {
            color: white;
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 10px 20px;
        }
        
        .btn-logout:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white">Admin Panel</h3>
        <a href="#">Dashboard</a>
        <a href="#">Manage Users</a>
        <a href="#">Settings</a>
        <a href="index.php" class="btn-logout">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Admin Dashboard</a>
            </div>
        </nav>

       

            <!-- Notifications Section -->
            <div class="notification-bar">
                <h5>New Employee Requests</h5>
                <?php if ($result_pending->num_rows > 0): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Joining Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_pending->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo ucfirst($row['gender']); ?></td>
                                    <td><?php echo $row['department']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td><?php echo $row['joining_date']; ?></td>
                                    <td>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="fullname" value="<?php echo $row['fullname']; ?>">
                                            <input type="hidden" name="phone" value="<?php echo $row['phone']; ?>">
                                            <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No new employee requests.</p>
                <?php endif; ?>
            </div>

            <!-- Users List Section -->
            <h5>Accepted Users List</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Joining Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_users->num_rows > 0): ?>
                        <?php while ($row = $result_users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['fullname']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo ucfirst($row['gender']); ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['joining_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
