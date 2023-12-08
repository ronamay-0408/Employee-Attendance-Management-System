<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters (modify as needed)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "eams";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $emp_id = $_POST["emp_id"];
    $emp_name = $_POST["emp_name"];
    $atlog_date = $_POST["atlog_date"];
    $am_in = $_POST["am_in"];
    $am_out = $_POST["am_out"];
    $pm_in = $_POST["pm_in"];
    $pm_out = $_POST["pm_out"];

    // Calculate late and undertime
    $am_start_time = strtotime("08:00:00");
    $pm_start_time = strtotime("13:00:00");

    // Convert times to strtotime format for comparison
    $am_in_time = strtotime($am_in);
    $pm_in_time = strtotime($pm_in);
    $am_out_time = strtotime($am_out);
    $pm_out_time = strtotime($pm_out);

    // Calculate lateness for both AM and PM
    $am_late_seconds = max(0, $am_in_time - $am_start_time);
    $pm_late_seconds = max(0, $pm_in_time - $pm_start_time);

    // Convert seconds to HH:MM:SS format
    $am_late = gmdate("H:i:s", $am_late_seconds);
    $pm_late = gmdate("H:i:s", $pm_late_seconds);

    // Upload photo files
    $filepath = 'cam/';

    $am_in_cam = uploadFile('am_in_cam', $filepath);
    $am_out_cam = uploadFile('am_out_cam', $filepath);
    $pm_in_cam = uploadFile('pm_in_cam', $filepath);
    $pm_out_cam = uploadFile('pm_out_cam', $filepath);

    // SQL query to insert data into the table
    // ... (previous code remains unchanged)

// Calculate undertime for AM
$expected_work_hours_am = 4 * 3600; // 4 hours in seconds

$am_worked_hours = $am_out_time - $am_in_time;

$am_undertime_seconds = max(0, $expected_work_hours_am - $am_worked_hours);

// Convert seconds to HH:MM:SS format
$am_undertime = "SEC_TO_TIME(" . $am_undertime_seconds . ")";

// Calculate undertime for PM
$expected_work_hours_pm = 4 * 3600; // 4 hours in seconds

$pm_worked_hours = $pm_out_time - $pm_in_time;

$pm_undertime_seconds = max(0, $expected_work_hours_pm - $pm_worked_hours);

// Convert seconds to HH:MM:SS format
$pm_undertime = "SEC_TO_TIME(" . $pm_undertime_seconds . ")";


// Calculate overtime for PM
$semi_overtime = strtotime("18:00:00");
$overtime_seconds = max(0, $pm_out_time - $semi_overtime);

// Convert seconds to HH:MM:SS format
$overtime = "SEC_TO_TIME(" . $overtime_seconds . ")";

// SQL query to insert data into the table
$sql = "INSERT INTO atlog (emp_id, emp_name, atlog_date, am_in_cam, am_in, am_out_cam, am_out, pm_in_cam, pm_in, pm_out_cam, pm_out, am_late, am_undertime, pm_late, pm_undertime, overtime) VALUES ('$emp_id', '$emp_name', '$atlog_date', '$am_in_cam', '$am_in', '$am_out_cam', '$am_out', '$pm_in_cam', '$pm_in', '$pm_out_cam', '$pm_out', '$am_late', $am_undertime, '$pm_late', $pm_undertime, $overtime)";


    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}

function uploadFile($inputName, $targetDir)
{
    $targetFile = $targetDir . basename($_FILES[$inputName]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES[$inputName]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES[$inputName]["name"])) . " has been uploaded.";
            return basename($_FILES[$inputName]["name"]); // Return only the file name
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    return null;
}
?>
