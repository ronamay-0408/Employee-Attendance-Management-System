<?php
    session_start();
    require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Overview</title>
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
      margin-bottom: 60px;
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
      background-color: #ad0e0e;
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
      background-color: green;
      color: white;
    }
    .operation-buttons button a{
      text-decoration: none;
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
      top: 15px;
      right: 20px;
      cursor: pointer;
      color: white;
    }
    .add-user-button a{
      text-decoration: none;
      color: white;
      font-weight: 700;
    }

    .popup-container {
        display: none;
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
    .deletebtn{
        text-align: center;
    }
    #nobtn{
        font-size: 14px;
        padding: 8px 15px;
    }
    #yesbtn{
        font-size: 14px;
        padding: 8px 15px;
    }
    input{
        width: 65%;
        margin-left: 5px;
        border-color: #333;
    }
    </style>
</head>
<body>
    <div class="header"> <i class="fa-solid fa-bars" id="toggleSidebar" style="cursor: pointer;"></i> <h3>EMPLOYEE MAINTENANCE</h3></div>
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
                    <div class="title">List of Employees</div>
                        <div class="search-bar">
                            <input type="text" id="search-input" placeholder="Search...">
                            <button onclick="search()">Search</button>
                        </div>
                        <button class="add-user-button"><a href="../Empform.html">ADD EMPLOYEE</a></button>
                    </div>
                    <table id="user-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Avatar</th>
                                <th>ID Number</th>
                                <th>Full Name</th>
                                <th>Sex</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $query = "SELECT * FROM employeeinformation";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                foreach($query_run as $employees)
                                {
                                    ?>
                                    <form action="db.php" method="POST" class="d-inline">
                                    <tr>
                                        <td><?= $employees['Id']; ?></td>
                                        <td><?= $employees['Code']; ?></td>
                                        <!-- Modify this line to display the avatar image -->
                                        <td><img src="../Photo/<?= $employees['Avatar']; ?>" alt="Avatar" style="width: 50px; height: 50px;"></td>
                                        <!-- End of modification -->
                                        <td><?= $employees['IDNumber']; ?></td>
                                        <td><?= $employees['FullName']; ?></td>
                                        <td><?= $employees['Sex']; ?></td>
                                        <td><?= $employees['DateOfBirth']; ?></td>
                                        <td><?= $employees['Address']; ?></td>
                                        <td class="operation-buttons">
                                            <button class="see-details"><a href="details.php?IDNumber=<?= $employees['IDNumber']; ?>" class="edit">Details</a></button>
                                            <button class="edit"><a href="EmpEdit.php?IDNumber=<?= $employees['IDNumber']; ?>" class="edit">Edit</a></button>
                                            
                                            <!--- <button type="submit" name="delete_emp" value="<?=$employees['Id'];?>" class="btn btn-danger btn-sm">Delete</button> --->
                                            <button type="button" class="btn btn-danger btn-sm" onclick="openPopup('reportPopup<?= $employees['IDNumber']; ?>')">Delete</button>

                                            <div id="reportPopup<?= $employees['IDNumber']; ?>" class="popup-container">
                                                <div class="ask">
                                                    <h3>Are you sure you want to delete this Record?</h3><br>

                                                    <form method="post" action="process_delete.php">
                                                        <label for="inputPassword">Enter Password:</label>
                                                        <input type="password" name="password" required>
                                                        <!-- Correct the name of the hidden input field to match in the PHP code -->
                                                        <input type="hidden" name="employee_id" value="<?= $employees['IDNumber']; ?>"><br><br>
                                                        <div class="deletebtn">
                                                            <button id="nobtn" type="button" onclick="cancelDelete()" class="edit">No</button>
                                                            <button id="yesbtn" type="submit" name="delete_emp" class="btn btn-danger btn-sm">Yes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </form>
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
    </div>
    <script>
        function cancelDelete() {
            window.location.href = "EMP_Maintenance.php";
        }
    </script>
    <script>
        function openPopup(popupId) {
            var popup = document.getElementById(popupId);
            console.log('Opening popup:', popupId);
            popup.style.display = 'block';
        }

        function closePopup() {
            var popup = document.getElementById('reportPopup');
            console.log('Closing popup');
            popup.style.display = 'none';
        }
    </script>
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
