<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
     * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      margin: 0; /* Remove default body margin */
      overflow: hidden; /* Hide body overflow */
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

    .header h3 {
      margin: 0 auto; /* Center the h3 element horizontally */
    }

    .container {
      display: flex;
    }

    .sidebar {
      width: 0;
      height: 100vh;
      overflow-y: auto;
      background-color: #333;
      color: white;
      list-style: none;
      font-weight: 500;
      transition: width 0.5s; /* Adjusted transition for smooth animation */
    }

    .sidebar li {
      display: flex;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #555;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .sidebar li:hover {
      background-color: #555;
    }

    .sidebar a {
      text-decoration: none;
      color: white;
      margin-left: 15px;
    }

    .sidebar i {
      margin-right: 8px;
    }

    .main {
      width: 100%;
      height: 100vh;
      overflow-y: auto;
      padding: 20px;
    }

    .table-container {
      position: relative;
      margin-bottom: 60px;
    }

    .action-bar {
      margin-bottom: 20px;
      text-align: center;
    }

    #date {
      font-size: 20px;
    }

    .card-container {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
    }

    .card {
      width: 350px;
      height: 250px;
      margin: 10px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .card-header {
      height: 25%;
      background-color: #2196f3;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
    }

    .card-header h3 {
      text-align: center;
      font-size: 1.3rem;
      width: 100%;
    }

    .card h2{
      font-size: 35px;
      margin-top: 1.5rem;
      text-align: center;
    }

    .card i{
        color: #2196f3;
        align-items: center;
        font-size: 25px;
        margin-top: 1rem;
        background-color: antiquewhite;
        border-radius: 50%;
        padding: 20px 20px;
    }
    .card a{
      text-decoration: none;
      color: white;
    }
    .card a:hover{
      color: #333;
    }
    .dashicon{
        text-align: center;
        width: 100%;
    }

    .sidebar-visible {
      width: 20%;
    }

    .logout_btn {
      font-size: 25px;
      position: absolute;
      top: 18px;
      right: 20px;
      cursor: pointer;
      color: white;
    }
    .secret i{
        width: 100%;
        text-decoration: none;
        background-color: whitesmoke;
        font-size: 70px;
        color: black;
        text-align: center;
        margin-top: 40px;
    }
    </style>
</head>
<body>
    <?php
        // Establish a database connection (replace these details with your actual connection details)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "eams"; // your actual database name

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to get the total number of records from a table
    function getTotalRecords($conn, $table) {
        $sql = "SELECT COUNT(*) as total FROM $table";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }

    // Get total records for each card
    $totalEmployees = getTotalRecords($conn, 'employeeinformation');
    $totalLoginReports = getTotalRecords($conn, 'logreport');
    $totalDailyAttendanceReports = getTotalRecords($conn, 'atlog');

    // Get total monthly attendance reports for the current month
    $currentMonth = date('F');
    $sql = "SELECT COUNT(*) as total FROM atlog WHERE MONTH(atlog_date) = MONTH(CURRENT_DATE())";
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        $totalMonthlyAttendanceReports = $row['total'];
    } else {
        $totalMonthlyAttendanceReports = 0;
    }

    mysqli_close($conn);
    ?>

    <div class="header">
        <i class="fa-solid fa-bars" id="toggleSidebar" style="cursor: pointer;"></i>
        <h3>ADMIN DASHBOARD</h3>
    </div>
    <div class='logout_btn'><i class="fa-solid fa-right-from-bracket"></i></div>
        <div class="container">
            <ul class="sidebar" id="sidebar">
                <li><a href="EAM_Dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                <li><a href="EMP_Maintenance.php"><i class="fa-solid fa-screwdriver-wrench"></i> Employee Maintenance</a></li>
                <li><a href="login_report.php"><i class="fa-solid fa-clipboard-list"></i> Log-in Report</a></li>
                <li><a href="atdaily.php"><i class="fa-solid fa-clipboard-user"></i> Daily Attendance Report</a></li>
                <li><a href="atmonth.php"><i class="fa-solid fa-calendar-days"></i> Monthly Attendance Report</a></li>
                <li><a href="salaryreport.php"><i class="fa-solid fa-file-invoice"></i> Salary Management</a></li>
            </ul>
            <div class="main">
                <div class="table-container">
                    <div class="action-bar">
                        <div id="date"></div>
                    </div>
                    <div class="card-container">
                        <div class="card">
                            <div class="card-header">
                                <h3><a href="EMP_Maintenance.php">Employee Maintenance</a></h3>
                            </div>
                            <div class="dashicon">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                            </div>
                            
                            <h2><?php echo $totalEmployees; ?></h2>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3><a href="login_report.php">Log-in Report</a></h3>
                            </div>
                            <div class="dashicon">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <h2><?php echo $totalLoginReports; ?></h2>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3><a href="atdaily.php">Daily Attendance Report</a></h3>
                            </div>
                            <div class="dashicon">
                                <i class="fa-solid fa-clipboard-user"></i>
                            </div>
                            <h2><?php echo $totalDailyAttendanceReports; ?></h2>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3><a href="atmonth.php">Monthly Attendance Report</a></h3>
                            </div>
                            <div class="dashicon">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <h2><?php echo $currentMonth; ?>: <?php echo $totalMonthlyAttendanceReports; ?></h2>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3><a href="EmployeeOfMonth.php">Employee of the Month</a></h3>
                            </div>
                            <p class="secret"><i class="fa-solid fa-user-secret"></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
            function updateDate() {
                var currentDate = new Date();
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'};
                var formattedDate = currentDate.toLocaleDateString('en-US', options);
                document.getElementById('date').innerHTML = formattedDate;
            }
        
            function toggleSidebar() {
                var sidebar = document.getElementById('sidebar');
                sidebar.classList.toggle('sidebar-visible');
            }
        
            // Update the date every second
            setInterval(updateDate, 1000);
        
            // Initial call to display the date immediately
            updateDate();
        
            // Add click event listener to the toggleSidebar button
            document.getElementById('toggleSidebar').addEventListener('click', toggleSidebar);
            });
        </script>
        <script>
        function logout() {
                window.location.href = '../ATLOG/admin_logout.php';
            }

            // Attach the logout function to the click event of the logout_btn
            document.querySelector('.logout_btn').addEventListener('click', logout);
    </script>
</body>
</html>
