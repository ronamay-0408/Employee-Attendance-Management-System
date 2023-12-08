<?php
session_start();
require 'db.php';

// Include your header code
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees Salary Report</title>
    <style>
        * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }

        body {
        background-color: #f5f5f5;
        }

        .header {
        background-color: #2196f3;
        color: white;
        font-size: 24px;
        padding: 15px;
        text-align: center;
        font-weight: 700;
        display: flex;
        align-items: center;
        }

        .header h3{
        margin: 0 auto; /* Center the h3 element horizontally */
        }

        .container {
        display: flex;
        }

        .main {
        width: 100%;
        height: 100vh;
        overflow-y: auto;
        padding: 20px;
        }

        .table-container {
        position: relative;
        margin-bottom: 20px;
        }

        .title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
        }

        .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        }

        th, td {
            font-size: 13.5px;
            padding: 15px;
            text-align: center;
            border: 1px solid #ccc;
        }

        th {
        background-color: #eee;
        font-weight: bold;
        }

        tr:nth-child(even) {
        background-color: #f9f9f9;
        }

        .popup-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .popup-content {
            text-align: center;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
        footer{
            text-align: center;
            margin-top: 2rem;
        }
        .report {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            padding: 30px; /* Increase padding for a bigger container */
            border: 1px solid #888;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        h2{
            text-align: center;
        }
        h4{
            font-size: 18px;
        }
        a{
            font-weight: lighter;
        }

        #printButton {
        float: right;
        margin-right: 10px;
        padding: 8px 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }
        #printButton span{
        font-size: 20px;
        }
    </style>
    <script>
        function printPayroll() {
        window.print();
        }
    </script>
</head>
<body>
    <a href="salaryreport.php" class="back float-end" style="
    text-decoration: none;
    color: black;
    font-weight: 700;
    ">BACK</a>
    <!-- Your header content goes here -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light"></nav>
    <div class="report">
    <button onclick="printPayroll()" style="float: right;margin-right: px;font-size: 25px;text-decoration: none;background: none;border: none;margin-top: -3px;"><span style="margin-left: 5px;">🖨️</span></button>
';

if (isset($_GET['employee_id'])) {
    $employeeId = $_GET['employee_id'];

    // Fetch employee information based on IDNumber
    $query = "SELECT * FROM employeeinformation WHERE Id = $employeeId";
    $employeeQuery = mysqli_query($con, $query);

    if ($employee = mysqli_fetch_assoc($employeeQuery)) {
        $idNumber = $employee['IDNumber'];

        // Get the current month and year
        $currentMonth = date('Y-m');
        $firstDayOfMonth = date('Y-m-01', strtotime($currentMonth));
        $lastDayOfMonth = date('Y-m-t', strtotime($currentMonth));

        // Fetch additional information from the atlog table for the current month
        $atlogQuery = "SELECT * FROM atlog WHERE emp_id = '$idNumber' AND atlog_date BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'";
        $atlogResult = mysqli_query($con, $atlogQuery);

        $totalHoursWorked = 0;
        $totalLateHours = 0;
        $totalUndertimeHours = 0;
        $totalOTInSeconds = 0;
        $hourlyPay = 150; // Replace this with your actual hourly pay rate
        $totalSalary = 0; // Initialize total salary outside the loop

        $addedOvertime = 30;

        // Define Night Differential Rate (replace with your actual rate)
        $nightDiffRate = 150; // Example rate in PHP
        $nightDiffRatePercentage = 20; // Example rate in percentage

        while ($atlog = mysqli_fetch_assoc($atlogResult)) {
            // Calculate hours and late/undertime for each day
            $amIn = strtotime($atlog['am_in'] ?? 'now');
            $amOut = strtotime($atlog['am_out'] ?? 'now');  
            $pmIn = strtotime($atlog['pm_in'] ?? 'now');
            $pmOut = strtotime($atlog['pm_out'] ?? 'now');

            $amInTotal = max(0, $amOut - $amIn);
            $pmInTotal = max(0, $pmOut - $pmIn);

            $totalHoursWorked += ($amInTotal + $pmInTotal);

            $amLate = strtotime($atlog['am_late'] ?? 'now');
            $pmLate = strtotime($atlog['pm_late'] ?? 'now');

            $lateHours = max(0, $amLate - strtotime('TODAY')) + max(0, $pmLate - strtotime('TODAY'));
            $totalLateHours += $lateHours;

            // Calculate undertime for each day
            $amUndertime = strtotime($atlog['am_undertime'] ?? 'now');
            $pmUndertime = strtotime($atlog['pm_undertime'] ?? 'now');

            $undertimeHours = max(0, $amUndertime - strtotime('TODAY')) + max(0, $pmUndertime - strtotime('TODAY'));
            $totalUndertimeHours += $undertimeHours;

            // Calculate the total overtime for each day and convert to seconds
            $overtime = $atlog['overtime'] ?? '00:00:00';
            list($overtimeHours, $overtimeMinutes, $overtimeSeconds) = explode(':', $overtime);
            
            $totalOTInSeconds += $overtimeHours * 3600 + $overtimeMinutes * 60 + $overtimeSeconds;


            // Convert total overtime seconds to hours
            $totalOTInHours = $totalOTInSeconds / 3600;
            // Calculate the Night Differential amount as 10% of the hourly pay
            //$nightDiffAmount = $totalOTInHours * ($hourlyPay * ($nightDiffRatePercentage / 100));
            $nightDiffAmount = floor($totalOTInHours) * $addedOvertime; // Divide by 3600 to convert seconds to hours

            // Calculate the total salary based on the formula: Total Salary = floor(Total Hours Worked) * Hourly Pay
            $totalSalary = floor($totalHoursWorked / 3600) * $hourlyPay; // Divide by 3600 to convert seconds to hours

            // Add Night Differential to the total salary
            $totalSalary += $nightDiffAmount;


        }
        
        $currentMonth = date('F Y');
        // Convert total seconds to hours:minutes:seconds format
        $formattedTotalHours = floor($totalHoursWorked / 3600); // Convert seconds to hours

        $formattedTotalLateHours = gmdate('H:i:s', $totalLateHours);

        // Convert total seconds to hours:minutes:seconds format for undertime
        $formattedTotalUndertimeHours = gmdate('H:i:s', $totalUndertimeHours);

        // Convert total seconds to hours:minutes:seconds format for overtime
        //$formattedTotalOT = gmdate('H:i:s', $totalOTInSeconds);
        $formattedTotalOT = floor($totalOTInSeconds / 3600);

        // Display employee information
        echo "<h2>SALARY REPORT!</h2>";
        echo "<h4>Name: <a>{$employee['FullName']}</a></h4>";
        echo "<h4>Department: <a>{$employee['Department']}</a></h4>";
        echo "<h4>Phone: <a>{$employee['PhoneNumber']}</a></h4>";

        // Display payroll information
        echo "<h2>PAYROLL</h2>";
        echo "<table border='1'>
                <tr>
                    <th>Current_Month</th>
                    <th>Total Hours Worked</th>
                    <th>Total Late Hours</th>
                    <th>Total Undertime Hours</th>
                    <th>Total OverTime</th>
                    <th>Hourly Pay</th>
                    <th>Night_Diff_Rate(%)</th>
                    <th>Night Diff Amount</th>
                    <th>Total Salary</th>
                </tr>";

        echo "<tr>
                <td>{$currentMonth}</td>
                <td>{$formattedTotalHours}</td>
                <td>{$formattedTotalLateHours}</td>
                <td>{$formattedTotalUndertimeHours}</td>
                <td>{$formattedTotalOT}</td>
                <td>₱{$hourlyPay}</td>
                <td>{$nightDiffRatePercentage}%</td>
                <td>₱{$nightDiffAmount}</td>
                <td>₱{$totalSalary}</td>
            </tr>";

        echo "</table>";

        // Include your footer code
        echo '
        <!-- Your footer content goes here -->
        <footer class="bg-light text-center py-3">
            <p>&copy; ' . date("Y") . ' PENNY All rights reserved.</p>
        </footer>
        </div>
        </body>
        </html>';
    } else {
        echo "Employee not found.";
    }
} else {
  echo "Employee ID not provided.";
}
?>
