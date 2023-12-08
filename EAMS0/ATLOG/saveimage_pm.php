<?php
session_start();
require_once('db.php');

// Function to calculate time difference in seconds
function calculateTimeDifference($startTime, $endTime) {
    $startTimestamp = strtotime($startTime);
    $endTimestamp = strtotime($endTime);

    $difference = $endTimestamp - $startTimestamp;

    return $difference;
}

// Assuming $employeeID is set from the login page
$employeeID = $_SESSION['employee_id']; // Adjust this according to your login logic

// Assuming you have a way to identify the specific atlog entry for Time-IN
$atlogID = $_SESSION['current_atlog_id']; // You need to set this value when processing Time-IN

$filename = time() . '.jpg';
$filepath = 'cam/';

if (!is_dir($filepath)) {
    mkdir($filepath);
}

// Set default values
$pm_in_column = 'pm_in';

// Check if a record with the same emp_id and date exists
$sql_check = "SELECT * FROM atlog WHERE emp_id = ? AND atlog_date = CURRENT_DATE";
$stmt_check = mysqli_prepare($con, $sql_check);
mysqli_stmt_bind_param($stmt_check, 's', $employeeID);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if (mysqli_num_rows($result_check) > 0) {
    // Update existing record
    $sql = "UPDATE atlog SET `$pm_in_column` = CURRENT_TIME, pm_in_cam = '$filename', pm_late = CASE WHEN CURRENT_TIME < '13:00:00' THEN '00:00:00' ELSE TIMEDIFF(CURRENT_TIME, '13:00:00') END WHERE emp_id = ? AND atlog_date = CURRENT_DATE";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $employeeID);
} else {
    // Insert a new record
    $sql = "INSERT INTO atlog (`emp_id`, `atlog_date`, `$pm_in_column`, pm_in_cam, pm_late) 
            VALUES (?, CURRENT_DATE, CURRENT_TIME, '$filename', CASE WHEN CURRENT_TIME < '13:00:00' THEN '00:00:00' ELSE TIMEDIFF(CURRENT_TIME, '13:00:00') END)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $employeeID);
}

// Move the file upload logic outside of the condition to update the database
if (isset($_FILES['webcam'])) {
    move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath . $filename);
}

// Execute the pm_in update
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "Image uploaded successfully at " . $filepath . $filename . ". PM_IN column, pm_in_cam, and pm_late updated.";
} else {
    echo " Error updating PM_IN column: " . mysqli_error($con);
}
?>
