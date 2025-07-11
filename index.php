<?php
 include("db.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Employee Skill Database</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <!-- Custom Styles -->
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header class="header">
    <a href="#" class="logo"> <i class="fas fa-user"></i> EMPLOYEE-SKILL DATABASE </a>
    <nav class="navbar">
      <a href="#home">HOME</a>
      <a href="searchbox.php">ADD</a>
      <a href="editbox.php">EDIT</a>
      <a href="searchbox.php">SEARCH</a>
    </nav>
  </header>

  <div class="container" id="home">
    <div class="left-section">
      <h1>Employee Skill Database: Track Records Faster Than Ever</h1>
      <p>Know More About Us...<br>From Anywhere, Anytime</p>
    </div>
    <div class="right-section">
      <div class="illustration">
        <img src="ppt.png" alt="Illustration" />
      </div>
    </div>
  </div>
</body>
</html>
