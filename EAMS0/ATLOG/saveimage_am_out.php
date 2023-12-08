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
$am_in_column = 'am_in';
$am_out_column = 'am_out';

// ...

if (isset($_FILES['webcam'])) {
    move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath . $filename);

    // Get the current hour and minute
    $currentTime = date('H:i:s');
    $currentHour = date('H');

    // Determine timein_period
    $timeinPeriod = ($currentHour < 12) ? 'AM' : 'PM';

    // Determine status
    if ($timeinPeriod == 'AM' && $currentTime <= '08:00:00') {
        $status = 'Present';
    } elseif ($timeinPeriod == 'AM' && $currentTime > '08:00:00') {
        $status = 'Late';
    } else {
        $status = 'Absent';
    }

    //ADDED CODE!!!!!!!! 
    // Fetch the am_in and am_out values from the database
    $sql_am_in_out = "SELECT am_in, am_out FROM atlog WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
    $result_am_in_out = mysqli_query($con, $sql_am_in_out);
    $row_am_in_out = mysqli_fetch_assoc($result_am_in_out);
    $am_in = strtotime($row_am_in_out['am_in']);
    $am_out = strtotime($row_am_in_out['am_out']);

    echo "am_in: " . date("H:i:s", $am_in) . "<br>";
    echo "am_out: " . date("H:i:s", $am_out) . "<br>";
    //ADDED CODE!!!!!!!!

    // Check if a record with the same emp_id and date exists
    $sql_check = "SELECT * FROM atlog WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
    $result_check = mysqli_query($con, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Update existing record
        $sql = "UPDATE atlog SET 
        $am_out_column = CURRENT_TIME, 
        am_out_cam = '$filename', 
        am_undertime = SEC_TO_TIME((4 * 3600) - TIME_TO_SEC(TIMEDIFF(am_out, am_in)))
        WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
        //$sql = "UPDATE atlog SET `$am_out_column` = CURRENT_TIME, am_out_cam = '$filename' WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
    } else {
        // Insert a new record
        $sql = "INSERT INTO atlog (emp_id, atlog_date, am_out, am_out_cam, am_undertime) 
        VALUES ('$employeeID', CURRENT_DATE, CURRENT_TIME, '$filename', 
                SEC_TO_TIME((4 * 3600) - TIME_TO_SEC(TIMEDIFF(am_out, am_in))))";

        //$sql = "INSERT INTO atlog (`emp_id`, `atlog_date`, `$am_in_column`, `$am_out_column`, am_out_cam) 
                //VALUES ('$employeeID', CURRENT_DATE, CURRENT_TIME, CURRENT_TIME, '$filename')";
    }

    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "Image uploaded successfully at " . $filepath . $filename;
    } else {
        echo "Error uploading image: " . mysqli_error($con);
    }
} else {
    echo "No file uploaded.";
}

// Additional checks or actions as needed
?>