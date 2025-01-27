<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6bN2gB16xjz6I6d45b0klyMhuINQ9+NUqVhPlRslX3Nxe9+5pxn6rLX76Y" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 600px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control, .form-select {
            margin-bottom: 15px;
        }
        .btn-custom {
            width: 100%;
        }
    </style>
</head>
<?php
// Include database connection
$servername = "localhost";  // Change if needed (e.g., localhost or an IP address)
$username = "root";         // MySQL username
$password = "";             // MySQL password (if applicable)
$dbname = "demo"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form data
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $joining_date = mysqli_real_escape_string($conn, $_POST['joining_date']);

    // Encrypt password (using bcrypt)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
    } else {
        $profile_picture = NULL;
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO registration (fullname, email, password, gender, department, role, phone, joining_date, profile_picture) 
            VALUES ('$fullname', '$email', '$hashed_password', '$gender', '$department', '$role', '$phone', '$joining_date', '$profile_picture')";

    // Execute the query and check if insertion is successful
    if ($conn->query($sql) === TRUE) {
        // Show success message
        $success_message = "Registered successfully!";
        // Redirect after 1 seconds
        header("Refresh: 1; URL=index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


// Close the connection
$conn->close();
?>
<body>
    <div class="container">
        <?php if (!empty($success_message)): ?>
            <!-- Bootstrap Success Alert -->
            <div class="alert alert-success text-center" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <h1>Registration Form</h1>
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name:</label>
                <input type="text" id="fullname" name="fullname" class="form-control" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender:</label>
                <div>
                    <input type="radio" id="male" name="gender" value="male" required>
                    <label for="male" class="form-label">Male</label>
                    <input type="radio" id="female" name="gender" value="female" required>
                    <label for="female" class="form-label">Female</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <select id="department" name="department" class="form-select" required>
                    <option value="HR">HR</option>
                    <option value="Engineering">Engineering</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Sales">Sales</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select id="role" name="role" class="form-select" required>
                    <option value="Manager">Manager</option>
                    <option value="Employee">Employee</option>
                    <option value="Intern">Intern</option>
                    <option value="Supervisor">Supervisor</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" required>
            </div>
            <div class="mb-3">
                <label for="joining_date" class="form-label">Joining Date:</label>
                <input type="date" id="joining_date" name="joining_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary btn-custom">Register</button>
        </form>
    </div>
</body>
</html>