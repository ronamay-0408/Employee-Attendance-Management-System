<?php
// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eams';

// Connect to MySQL
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the previous month and year
$previousMonth = date('m', strtotime('last month'));
$previousYear = date('Y', strtotime('last month'));

$query = "SELECT emp_id, emp_name, am_late, pm_late FROM atlog WHERE DATE_FORMAT(atlog_date, '%m/%Y') = '$previousMonth/$previousYear'";

// Execute the query
$result = $mysqli->query($query);

// Check if there is an error
if (!$result) {
    die("Error in query: " . $mysqli->error);
}

// Check if there are any results
if ($result->num_rows > 0) {
    $employeeOfMonth = null;
    $minLateCount = PHP_INT_MAX;

    // Loop through the results
    while ($row = $result->fetch_assoc()) {
        $empId = $row['emp_id'];
        $empName = $row['emp_name'];

        // Count the number of days with no am_late for each employee
        $countQuery = "SELECT COUNT(*) AS late_count FROM atlog WHERE emp_id = $empId AND am_late IS NULL";
        $countResult = $mysqli->query($countQuery);

        // Check if there is an error in the count query
        if (!$countResult) {
            die("Error in count query: " . $mysqli->error);
        }

        $countRow = $countResult->fetch_assoc();
        $lateCount = $countRow['late_count'];

        // Update the employee of the month if applicable
        if ($lateCount < $minLateCount) {
            $minLateCount = $lateCount;
            $employeeOfMonth = $empId;
            $name = $empName;
        }
    }
    
    $previousMonth = date('F');

    // Output the employee of the month
    if ($employeeOfMonth !== null) {
        // Fetch additional information from employeeinformation table
        $employeeInfoQuery = "SELECT IDNumber, Avatar, Department FROM employeeinformation WHERE IDNumber = $employeeOfMonth";
        $employeeInfoResult = $mysqli->query($employeeInfoQuery);

        // Check if there is an error in the employeeInfo query
        if (!$employeeInfoResult) {
            die("Error in employeeInfo query: " . $mysqli->error);
        }

        $employeeInfoRow = $employeeInfoResult->fetch_assoc();
        $employeeIDNumber = $employeeInfoRow['IDNumber'];
        $avatarFileName = $employeeInfoRow['Avatar'];
        $department = $employeeInfoRow['Department'];

        // Assuming avatars are stored in the "Photo" folder
        $avatarPath = "../Photo/" . $avatarFileName;

        // HTML and CSS for the congratulations page
        echo "<html>
            <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
            <title>Employee Details</title>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f0f0f0;
                        text-align: center;
                    }
                    .congratulations {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        max-width: 600px;
                        margin: 20px auto;
                    }
                    .avatar {
                        max-width: 200px;
                        border-radius: 5%;
                    }
                    .department {
                        font-style: italic;
                        color: #555;
                    }
                    .congratulations h3{
                        font-weight: bold;
                    }

                    .header {
                        display: flex;
                        justify-content: space-between;
                        background-color: #2196f3;
                        color: white;
                        font-size: 24px;
                        padding: 15px;
                        text-align: center;
                        font-weight: 700;
                    }
                    .header h3{
                        margin: 0 auto; /* Center the h3 element horizontally */
                    }
            
                    .header i{
                        color: white;
                    }
                </style>
            </head>

            <body>
                <div class='header'>
                    <a href='EAM_Dashboard.php' class='back float-end'><i class='fa-solid fa-backward'></i></a>
                    <h3>EMPLOYEE DETAILS</h3>
                    <div class='logout_btn'><i class='fa-solid fa-right-from-bracket'></i></div>
                </div>
                <div class='congratulations'>
                    <h2>Congratulations!</h2>
                    <p>Employee of the Month for $previousMonth $previousYear:</p>
                    <p>Emp ID: $employeeIDNumber</p>
                    <img src='$avatarPath' alt='Avatar' class='avatar'>
                    <h3>$name</h2>
                    <p class='department'>Department: $department</p>
                </div>
            </body>
        </html>";
    } else {
        echo "No eligible employee found for Employee of the Month.";
    }
} else {
    echo "No data found for $previousMonth/$previousYear.";
}

// Close the database connection
$mysqli->close();
?>
