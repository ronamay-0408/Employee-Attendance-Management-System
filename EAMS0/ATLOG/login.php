<?php
session_start();

date_default_timezone_set('Asia/Manila'); // Set your time zone here

$host = "localhost";
$user = "root";
$password = "";
$databasename = "eams";

$conn = mysqli_connect($host, $user, $password, $databasename);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['login'])) {
    $enteredUsername = $_POST['username'];
    $enteredPassword = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM EmployeeInformation WHERE Code = ? AND IDNumber = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $enteredUsername, $enteredPassword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Store user data in session for later retrieval
        
        $_SESSION['user_data'] = $row;

        // Store the IDNumber and FullName in session variables
        $_SESSION['employee_id'] = $row['IDNumber'];
        $_SESSION['FullName'] = $row['FullName'];

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

        // Redirect to the EAM_Dashboard - Employee.html
        header("Location: EAM_Dashboard_Emp.html");
        exit();
    } else {
        // Display invalid username or password alert and stay on the same page
        echo '<script>alert("Login failed. Invalid username or password.");';
        echo 'window.location.href="../index.php";</script>';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
