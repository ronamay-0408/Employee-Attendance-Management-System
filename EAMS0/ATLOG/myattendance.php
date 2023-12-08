<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'>
    <title>My Attendance Report</title>
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
    }

    .container {
      display: flex;
    }

    .sidebar {
      width: 25%;
      height: 100vh;
      overflow-y: auto;
      background-color: #333;
      color: white;
      list-style: none;
      font-weight: 500;
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
    
    .sidebar a{
      text-decoration: none;
      color: white;
      display: flex;
      margin-left: 15px;
    }

    .sidebar i, .dropdown-btn i {
        margin-right: 8px;
    }

    .dropdown-btn {
      padding: 15px;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      outline: none;
      transition: background-color 0.3s;
    }

    .dropdown-btn:hover {
      background-color: #555;
    }

    .dropdown-container {
      display: none;
      background-color: #262626;
    }

    .dropdown-container button{
      margin-left: 15px;
    }

    .dropdown-container a {
      text-decoration: none;
      color: white;
      display: block;
      transition: background-color 0.3s;
    }

    .dropdown-container a:hover {
      background-color: #555;
    }
    .dropdown-btn .fa{
      margin-left: 1.5rem;
    }
    .main {
      width: 100%;
      height: 100vh;
      overflow-y: auto;
      padding: 20px;
      background-color: #fff;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }
    .title {
      font-size: 25px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #f5f5f596;
    }

    .action-bar {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
    }

    .time {
      font-size: 6rem;
      text-align: center;
      font-weight: 500;
      padding: 10px;
      margin-top: 5rem;
    }

    .date {
      font-size: 3.5rem;
      text-align: center;
      font-weight: 500;
      padding: 10px;
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
    }

    th, td {
        font-size: 13.5px;
        padding: 12px;
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
    .sidebar-visible {
      width: 17%;
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
    <div class="header">MY ATTENDANCE</div>
    <div class='logout_btn'><i class="fa-solid fa-right-from-bracket"></i></div>
    <div class="container">
        <ul class='sidebar'>
            <li><a href='EAM_Dashboard_Emp.html'><i class='fa-solid fa-house'></i> Dashboard</a></li>
            <li><a href='myattendance.php'><i class="fa-solid fa-clipboard-user"></i> My Attendance</a></li>
            <li><a href='account.php'><i class='fa-solid fa-user'></i> Account Information</a></li>
            <li class='dropdown-btn'><a><i class='fa-solid fa-camera'></i> Capture Attendance <i class='fa fa-caret-down'></i></a></li>
            <div class='dropdown-container'>
                <li><a href='am_timein.php'><i class="fa-solid fa-sun"></i>AM Time-in</a></li>
                <li><a href='am_timeout.php'><i class="fa-solid fa-sun"></i>AM Time-out</a></li>
                <li><a href='pm_timein.php'><i class="fa-solid fa-cloud-sun"></i>PM Time-in</a></li>
                <li><a href='pm_timeout.php'><i class="fa-solid fa-cloud-sun"></i>PM Time-out</a></li>
            </div>
        </ul>
        <div class="main">
            <div class="table-container">
                <div class="action-bar">
                    <div class="search-bar">
                        <input type="text" id="search-input" placeholder="Search...">
                        <button onclick="search()">Search</button>
                    </div>
                </div>
                <table id="user-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>AM IN</th>
                            <th>AM OUT</th>
                            <th>PM IN</th>
                            <th>PM OUT</th>
                            <th>AM_LATE</th>
                            <th>AM_UNDERTIME</th>
                            <th>PM_LATE</th>
                            <th>PM_UNDERTIME</th>
                            <th>OVERTIME</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Fetch only the attendance records for the currently logged-in employee
                        $employeeId = $_SESSION['user_data']['Id'];
                        $query = "SELECT * FROM atlog WHERE emp_id = (SELECT IDNumber FROM employeeinformation WHERE Id = $employeeId)";
                        $query_run = mysqli_query($con, $query);

                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $employees)
                            {
                                ?>
                                <tr>
                                    <td><?= $employees['atlog_date']; ?></td>
                                    <td><?= $employees['am_in']; ?></td>
                                    
                                    <td><?= $employees['am_out']; ?></td>
                                    
                                    <td><?= $employees['pm_in']; ?></td>
                                    
                                    <td><?= $employees['pm_out']; ?></td>
                                    <td><?= $employees['am_late']; ?></td>
                                    <td><?= $employees['am_undertime']; ?></td>
                                    <td><?= $employees['pm_late']; ?></td>
                                    <td><?= $employees['pm_undertime']; ?></td>
                                    <td><?= $employees['overtime']; ?></td>
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

        //* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
        var dropdown = document.getElementsByClassName('dropdown-btn');
        var i;

        for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener('click', function() {
            this.classList.toggle('active');
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
            } else {
            dropdownContent.style.display = 'block';
            }
        });
        }
  </script>
  <script>
    function logout() {
            window.location.href = '../ATLOG/logout.php';
        }

        // Attach the logout function to the click event of the logout_btn
        document.querySelector('.logout_btn').addEventListener('click', logout);
  </script>
</body>
</html>
