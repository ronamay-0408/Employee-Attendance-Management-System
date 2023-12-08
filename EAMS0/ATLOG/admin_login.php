<?php
session_name("admin_session");
session_start();

date_default_timezone_set('Asia/Manila'); // Set your correct time zone here

$host = "localhost";
$user = "root";
$password = "";
$databasename = "eams";

$conn = mysqli_connect($host, $user, $password, $databasename);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['admin_login'])) {
    $enteredUsername = $_POST['username'];
    $enteredPassword = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM EmployeeInformation WHERE Code = ? AND IDNumber = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $enteredUsername, $enteredPassword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check if the user's department is 'ADMIN'
        if ($row['Department'] === 'ADMIN') {
            // Store admin user data in session for later retrieval
            $_SESSION['admin_employee_id'] = $row['IDNumber'];
            $_SESSION['admin_FullName'] = $row['FullName'];

            // Log the login information into the logreport table
            $empId = $row['IDNumber']; // Use IDNumber for emp_id
            $empName = $row['FullName'];
            $loginDate = date("Y-m-d");
            $loginTime = date("H:i:s"); // Format time in 24-hour format
            $department = $row['Department'];
            $email = $row['Email'];
            $position = $row['EmployeePosition'];
            $status = 'Logged In'; // You can define other statuses as needed

            // Insert login information into logreport table
            $insertSql = "INSERT INTO logreport (emp_id, emp_name, login_date, login_time, department, email, position, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertSql);
            mysqli_stmt_bind_param($insertStmt, "ssssssss", $empId, $empName, $loginDate, $loginTime, $department, $email, $position, $status);
            mysqli_stmt_execute($insertStmt);
            mysqli_stmt_close($insertStmt);

            // Redirect to the appropriate dashboard
            header("Location: ../ADMIN/EAM_Dashboard.php");
            exit();
        } else {
            // Log unauthorized access attempt
            $errorLog = "Unauthorized access attempt by user: " . $enteredUsername;
            error_log($errorLog, 0);

            // Display unauthorized access alert and stay on the same page
            echo '<script>alert("Login failed. You do not have permission to access this dashboard.");';
            echo 'window.location.href="../index.php";</script>';
        }
    } else {
        // Display invalid username or password alert and stay on the same page
        echo '<script>alert("Login failed. Invalid username or password.");';
        echo 'window.location.href="../index.php";</script>';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
