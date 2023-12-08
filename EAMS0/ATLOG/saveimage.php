<?php
session_start();
require_once('db.php');

// Assuming $employeeID and $employeeFullName are set from the login page
$employeeID = $_SESSION['employee_id']; // Adjust this according to your login logic
$employeeFullName = isset($_SESSION['FullName']) ? $_SESSION['FullName'] : 'Unknown'; // Provide a default value if not set

// Assuming you have a way to identify the specific atlog entry for Time-IN
$atlogID = $_SESSION['current_atlog_id']; // You need to set this value when processing Time-IN

$filename = time() . '.jpg';
$filepath = 'cam/';

if (!is_dir($filepath)) {
    mkdir($filepath);
}

// Set default values
$am_in_column = 'am_in';

// Check the page to determine the appropriate columns
if (basename($_SERVER['PHP_SELF']) == 'am_timeout.php') {
    $am_in_column = ''; // No need to update AM In column
}

if (isset($_FILES['webcam'])) {
    move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath . $filename);

    // Get the current hour and minute
    $currentTime = date('H:i:s');
    $currentHour = date('H');

    // Determine timein_period
    $timeinPeriod = ($currentHour < 12) ? 'AM' : 'PM';

    // Check if a record with the same emp_id and date exists
    $sql_check = "SELECT * FROM atlog WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
    $result_check = mysqli_query($con, $sql_check);

    if ($result_check) {
        if (mysqli_num_rows($result_check) > 0) {
            // Update existing record
            $sql = "UPDATE atlog SET `$am_in_column` = CURRENT_TIME, am_in_cam = '$filename', emp_name = '$employeeFullName', 
                    am_late = CASE WHEN CURRENT_TIME < '08:00:00' THEN '00:00:00' ELSE TIMEDIFF(CURRENT_TIME, '08:00:00') END
                    WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
        } else {
            // Insert a new record
            $sql = "INSERT INTO atlog (`emp_id`, `atlog_date`, `$am_in_column`, am_in_cam, am_late, emp_name) 
                    VALUES ('$employeeID', CURRENT_DATE, CURRENT_TIME, '$filename', 
                    CASE WHEN CURRENT_TIME < '08:00:00' THEN '00:00:00' ELSE TIMEDIFF(CURRENT_TIME, '08:00:00') END,
                    '$employeeFullName')";
        }

        $result = mysqli_query($con, $sql);

        if ($result) {
            echo "Image uploaded successfully at " . $filepath . $filename;
        } else {
            echo "Error updating database: " . mysqli_error($con);
        }
    } else {
        echo "Error checking for existing records: " . mysqli_error($con);
    }

} else {
    echo "No file uploaded.";
}

// Assuming you have a way to identify the specific atlog entry for Time-OUT
$atlogID = $_SESSION['current_atlog_id']; // You need to set this value when processing Time-OUT

// Update the corresponding Time-OUT column based on the period
if (isset($_GET['time_out'])) {
    $pm_in_column = 'pm_in';
    $pm_out_column = 'pm_out';

    // Update PM In and PM Out columns for the relevant page
    if (basename($_SERVER['PHP_SELF']) == 'pm_timeout.php') {
        $pm_in_column = ''; // No need to update PM In column
    }

    $sql = "UPDATE atlog SET $pm_in_column = CURRENT_TIME WHERE atlog_id = '$atlogID'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Time-OUT updated successfully.";
    } else {
        echo "Error updating Time-OUT: " . mysqli_error($con);
    }
}
?>
