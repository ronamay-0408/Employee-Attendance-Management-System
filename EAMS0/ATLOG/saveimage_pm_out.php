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

// Assuming you have a way to identify the specific atlog entry
$atlogID = $_SESSION['current_atlog_id']; // You need to set this value when processing

$filename = time() . '.jpg';
$filepath = 'cam/';

if (!is_dir($filepath)) {
    mkdir($filepath);
}

// Determine timeout_period
$currentHour = date('H');
$timeoutPeriod = ($currentHour < 12) ? 'AM' : 'PM';

// Fetch the pm_in and pm_out values from the database
$sql_pm_in_out = "SELECT pm_in, pm_out FROM atlog WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
$result_pm_in_out = mysqli_query($con, $sql_pm_in_out);
$row_pm_in_out = mysqli_fetch_assoc($result_pm_in_out);
$pm_in = strtotime($row_pm_in_out['pm_in']);
$pm_out = strtotime($row_pm_in_out['pm_out']);

echo "pm_in: " . date("H:i:s", $pm_in) . "<br>";
echo "pm_out: " . date("H:i:s", $pm_out) . "<br>";


//CHECK IF THIS CODE IS NOT USEFUL, IF YES THEN DELETE
// Corrected calculation for semi_undertime
$semiUndertime_seconds = $pm_out - $pm_in;
$semiUndertime = gmdate("H:i:s", $semiUndertime_seconds);
echo "semi_undertime: " . $semiUndertime . "<br>";
// Set the scheduled hours as 4 hours
$scheduledHours_seconds = strtotime('1970-01-01 05:00:00'); //05:00:00 because the time of employee work is from 1pm to 5pm
// Calculate pm_undertime in seconds
$new = gmdate("H:i:s", $scheduledHours_seconds);
echo "hours: " . $new . "<br>";
$pm_undertime_seconds = $scheduledHours_seconds - $semiUndertime_seconds;
// Format pm_undertime as H:i:s
$pm_undertime = gmdate("H:i:s", $pm_undertime_seconds);
echo "pm_undertime: " . $pm_undertime . "<br>";



// Check if a record with the same emp_id and date exists
$sql_check = "SELECT * FROM atlog WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
$result_check = mysqli_query($con, $sql_check);
// ...
if ($result_check) {
    if (mysqli_num_rows($result_check) > 0) {
        // Update existing record
        $pm_out_column = 'pm_out'; // Assuming pm_out column name
        
        // For UPDATE statement FIXING THE PM_UNDERTIME CODE
        $sql = "UPDATE atlog SET 
            $pm_out_column = CURRENT_TIME, 
            pm_out_cam = '$filename', 
            pm_undertime = 
                CASE 
                    WHEN $pm_out_column > '17:00:00'
                    THEN '00:00:00'
                    ELSE SEC_TO_TIME((4 * 3600) - TIME_TO_SEC(TIMEDIFF(pm_out, pm_in)))
                END,
            OVERTIME = 
                CASE 
                    WHEN $pm_out_column > '17:00:00' AND $pm_out_column <= '23:00:00' 
                    THEN SEC_TO_TIME(TIME_TO_SEC($pm_out_column) - TIME_TO_SEC('18:00:00'))
                    ELSE '00:00:00'
                END
            WHERE emp_id = '$employeeID' AND atlog_date = CURRENT_DATE";
    } else {
        // Insert a new record (if needed)
        $sql = "INSERT INTO atlog (emp_id, atlog_date, pm_out, pm_out_cam, pm_undertime, overtime) 
            VALUES ('$employeeID', CURRENT_DATE, CURRENT_TIME, '$filename', 
                CASE 
                    WHEN CURRENT_TIME > '17:00:00' 
                    THEN '00:00:00'
                    ELSE SEC_TO_TIME((4 * 3600) - TIME_TO_SEC(TIMEDIFF(pm_out, pm_in)))
                END,
                CASE 
                    WHEN CURRENT_TIME > '17:00:00' 
                    THEN SEC_TO_TIME(TIME_TO_SEC(CURRENT_TIME) - TIME_TO_SEC('18:00:00'))
                    ELSE '00:00:00'
                END)";
    }
    

    // Execute the SQL query
    $result = mysqli_query($con, $sql);

    if ($result) {
        // Save image to folder
        move_uploaded_file($_FILES['webcam']['tmp_name'], $filepath . $filename);
        echo "Time-OUT updated successfully. Image uploaded successfully at " . $filepath . $filename;
    } else {
        echo "Error updating Time-OUT: " . mysqli_error($con);
    }
} else {
    echo "Error checking for existing records: " . mysqli_error($con);
}

// Additional checks or actions as needed
?>
