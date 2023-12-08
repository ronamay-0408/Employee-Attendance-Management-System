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

// Check if the admin user is logged in
if (isset($_SESSION['admin_employee_id'])) {
    $adminEmpIdSession = $_SESSION['admin_employee_id'];

    // Log the logout information into the logreport table
    $logoutDate = date("Y-m-d");
    $logoutTime = date("H:i:s");
    $status = 'Logged Out';

    // Update the logout information in logreport table
    $updateSql = "UPDATE logreport SET logout_date=?, logout_time=?, status=? WHERE emp_id=? AND logout_date IS NULL AND logout_time IS NULL";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "ssss", $logoutDate, $logoutTime, $status, $adminEmpIdSession);
    mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);

    // Clear the admin session data
    session_unset();
    session_destroy();

    // Redirect to the login page
    header("Location: ../index.php");
    exit();
} else {
    // If the admin user is not logged in, redirect to the login page
    header("Location: ../index.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
