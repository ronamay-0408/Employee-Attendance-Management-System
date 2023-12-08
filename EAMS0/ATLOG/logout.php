<?php
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

// Check if the user is logged in
if (isset($_SESSION['employee_id'])) {
    $empIdSession = $_SESSION['employee_id'];

    // Log the logout information into the logreport table

    // Check if there is an open session in the logreport table
    $sqlCheck = "SELECT * FROM logreport WHERE emp_id = ? AND logout_date IS NULL AND logout_time IS NULL";
    $stmtCheck = mysqli_prepare($conn, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "s", $empIdSession);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        // There is an open session, proceed with updating logreport
        $logoutDate = date("Y-m-d");
        $logoutTime = date("H:i:s");
        $status = 'Logged Out';

        // Update the logout information in logreport table
        $updateSql = "UPDATE logreport SET logout_date=?, logout_time=?, status=? WHERE emp_id=? AND logout_date IS NULL AND logout_time IS NULL";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ssss", $logoutDate, $logoutTime, $status, $empIdSession);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);

        // Clear the session data
        session_unset();
        session_destroy();

        // Redirect to the login page
        header("Location: ../index.php");
        exit();
    } else {
        // There is no open session, redirect to the login page
        header("Location: ../index.php");
        exit();
    }

    mysqli_stmt_close($stmtCheck);
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../index.php");
    exit();
}

// Close the database connection
mysqli_close($conn);

?>
