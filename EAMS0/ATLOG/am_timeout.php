<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AM TimeOUT</title>
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
      background-image: url(https://wallpaperaccess.com/full/744706.jpg);
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .table-container {
      position: relative;
      margin-bottom: 20px;
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

    #my_camera{
      width: 320px;
      height: 240px;
      border: 1px solid black;
    }

    .logout_btn {
      font-size: 25px;
      position: absolute;
      top: 15px;
      right: 15px;
      cursor: pointer;
      color: white;
    }
  </style>
</head>
<body>
  <div class="header">Employee AM TIME-OUT</div>
  <div class='logout_btn'><i class='fa-solid fa-right-from-bracket'></i></div>
  <div class="container">
    <ul class="sidebar">
        <li><a href="EAM_Dashboard_Emp.html"><i class="fa-solid fa-house"></i> Dashboard</a></li>
        <li><a href='myattendance.php'><i class="fa-solid fa-clipboard-user"></i> My Attendance</a></li>
        <li><a href="account.php"><i class="fa-solid fa-user"></i> Account Information</a></li>
        <li class="dropdown-btn"><a><i class="fa-solid fa-camera"></i> Capture Attendance <i class="fa fa-caret-down"></i></a></li>
        <div class="dropdown-container">
            <li><a href="am_timein.php"><i class="fa-solid fa-sun"></i>AM Time-in</a></li>
            <li><a href="am_timeout.php"><i class="fa-solid fa-sun"></i>AM Time-out</a></li>
            <li><a href="pm_timein.php"><i class="fa-solid fa-cloud-sun"></i>PM Time-in</a></li>
            <li><a href="pm_timeout.php"><i class="fa-solid fa-cloud-sun"></i>PM Time-out</a></li>
        </div>
    </ul>
    <div class="main">
      <div class="table-container">
        <div class="action-bar">
        <div class="title">AM TIME-OUT</div>
            <!-- -->
            <div id="my_camera"></div>
            <input type=button value="Take Snapshot" onClick="take_snapshot()">
             
            <div id="results" ></div>
             
            <!-- Script -->
            <script type="text/javascript" src="webcam.min.js"></script>
            
            <!-- Code to handle taking the snapshot and displaying it locally -->
            <script language="JavaScript">
            
             // Configure a few settings and attach camera
             Webcam.set({
              width: 320,
              height: 240,
              image_format: 'jpeg',
              jpeg_quality: 90
             });
             Webcam.attach( '#my_camera' );
            
             // preload shutter audio clip
             var shutter = new Audio();
             shutter.autoplay = true;
             shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';
            
            function take_snapshot() {
             // play sound effect
             shutter.play();
             
             // take snapshot and get image data
             Webcam.snap( function(data_uri) {
             
            Webcam.upload( data_uri, 'saveimage_am_out.php', function(code, text,Name) {
              document.getElementById('results').innerHTML = 
              '' + 
  
             // display results in page
             //document.getElementById('results').innerHTML = 
             '<img src="'+data_uri+'"/>';
             } );

             } );
            }
            
            </script>
        </div>
      </div>
    </div>
  </div>
  <script>
    function logout() {
            window.location.href = 'logout.php';
        }

        // Attach the logout function to the click event of the logout_btn
        document.querySelector('.logout_btn').addEventListener('click', logout);

    //* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
      dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
          dropdownContent.style.display = "none";
        } else {
          dropdownContent.style.display = "block";
        }
      });
    }
  </script>
</body>
</html>