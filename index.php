<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Light background color */
            font-size: 14px; /* Smaller font size */
        }

        .card {
            background-color: #ffffff; /* Light card background */
        }

        .card-header {
            background-color: #a3c9f1; /* Lighter blue header */
        }

        .card-footer {
            background-color: #f1f3f5; /* Light footer color */
        }

        .form-label {
            font-size: 12px; /* Smaller label font */
        }

        .form-control {
            font-size: 13px; /* Smaller input font */
        }

        .btn {
            font-size: 13px; /* Smaller button font */
        }
    </style>
</head>

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "demo";

    // Fetching input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // MySQL Connection
    $conn = new mysqli($host, $user, $pass, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Admin credentials hardcoded
    $admin_username = 'admin';
    $admin_password = '123'; // Set admin password here

    // Admin login check
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true; // Set admin session
        header("Location: dashboard.php"); // Redirect to admin dashboard
        exit();
    }

    // Employee login
    $sql = "SELECT * FROM registration WHERE fullname = ? AND password = ? AND status = 'accepted';";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debug SQL errors
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Set session for employee
        $_SESSION['employee_logged_in'] = true;
        $_SESSION['employee_name'] = $row['fullname'];
        $_SESSION['employee_password'] = $row['password'];
        
        echo "<pre>";
    print_r($_SESSION); // Check if the session is set correctly
    echo "</pre>";

        // Redirect to employee dashboard
        header("Location: employee_dashboard.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid username or password!</div>";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>


<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header text-white text-center">
                        <h3 class="mb-0">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form action="index.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Don't have an account? <a href="/task/register.php" class="text-primary">Signup</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
