<?php
    session_start();
    require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Logout Report</title>
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
    .header h3{
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
      transition: width 0.3s; /* Added transition for smooth animation */
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

    .search-bar {
      display: flex;
      align-items: center;
      margin-right: 10px;
    }

    .search-bar input {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 200px;
      margin-right: 5px;
    }

    .search-bar button {
      padding: 8px 12px;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .add-user-button {
      padding: 8px 12px;
      background-color: #2196f3;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      margin-bottom: 60px;
    }

    th, td {
        font-size: 13.5px;
        padding: 15px;
        text-align: left;
        border: 1px solid #ccc;
    }

    th {
      background-color: #eee;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .operation-buttons button {
      margin-right: 5px;
      padding: 6px 6px;
      background-color: #2196f3;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .operation-buttons button.see-details {
      background-color: #2196f3;
      color: white;
    }

    .operation-buttons button.edit {
      background-color: #4caf50;
      color: white;
    }

    .operation-buttons button.delete {
      background-color: #f44336;
      color: white;
    }

     /* ... (your existing styles) ... */
     .sidebar {
      width: 0;
      /* other styles... */
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
    </style>
</head>
<body>
    <div class="header"> <i class="fa-solid fa-bars" id="toggleSidebar" style="cursor: pointer;"></i> <h3>LOGIN & LOGOUT REPORT</h3></div>
    <div class='logout_btn'><i class="fa-solid fa-right-from-bracket"></i></div>
    <div class="container">
        <ul class="sidebar" id="sidebar">
            <li><a href="EAM_Dashboard.php"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="EMP_Maintenance.php"><i class="fa-solid fa-screwdriver-wrench"></i> Employee Maintenance</a></li>
            <li><a href="login.php"><i class="fa-solid fa-clipboard-list"></i> Log-in Report</a></li>
            <li><a href="atdaily.php"><i class="fa-solid fa-clipboard-user"></i> Daily Attendance Report</a></li>
            <li><a href="atmonth.php"><i class="fa-solid fa-calendar-days"></i> Monthly Attendance Report</a></li>
            <li><a href="salaryreport.php"><i class="fa-solid fa-file-invoice"></i> Salary Management</a></li>
        </ul>
        <div class="main">
            <div class="table-container">
                <div class="action-bar">
                    <div class="title">EMPLOYEES LOGIN & LOGOUT REPORT</div>
                    <div class="search-bar">
                        <input type="text" id="search-input" placeholder="Search...">
                        <button onclick="search()">Search</button>
                    </div>
                </div>
                <table id="user-table">
                <!-- Inside the <thead> section, add columns for image paths -->
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Login Date</th>
                    <th>Login Time</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Logout Date</th>
                    <th>logout Time</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = "SELECT * FROM logreport";
                        $query_run = mysqli_query($con, $query);

                        if(mysqli_num_rows($query_run) > 0)
                        {
                        foreach($query_run as $employees)
                        {
                            ?>
                            <tr>
                            <td><?= $employees['emp_name']; ?></td>
                            <td><?= $employees['login_date']; ?></td>
                            <td><?= $employees['login_time']; ?></td>
                            <td><?= $employees['department']; ?></td>
                            <td><?= $employees['email']; ?></td>
                            <td><?= $employees['position']; ?></td>
                            <td><?= $employees['status']; ?></td>
                            <td><?= $employees['logout_date']; ?></td>
                            <td><?= $employees['logout_time']; ?></td>
                            </tr>
                            <?php
                        }
                        }
                        else
                        {
                        echo "<h5> No Record Found </h5>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
    <script>
        function search() {
        var input = document.getElementById("search-input");
        var table = document.getElementById("user-table");

        var value = input.value.toLowerCase();
        var rows = table.getElementsByTagName("tr");

        for (var i = 1; i < rows.length; i++) {
            var row = rows[i];
            var cells = row.getElementsByTagName("td");
            var match = false;

            for (var j = 0; j < cells.length; j++) {
            var cell = cells[j];
            var text = cell.textContent.toLowerCase();

            if (text.includes(value)) {
                match = true;
                break;
            }
            }

            if (match) {
            row.style.display = "";
            } else {
            row.style.display = "none";
            }
        }
        }

        function addUser() {
        // Add your logic for adding a user here
        alert("Add User functionality to be implemented.");
        }

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-visible');
        }
    
        // Add click event listener to the toggleSidebar button
        document.getElementById('toggleSidebar').addEventListener('click', toggleSidebar);
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
