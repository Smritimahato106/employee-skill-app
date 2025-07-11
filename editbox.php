<?php
include("db.php");

$deletionMessage = ""; 

if (isset($_POST['delete'])) {
    $empName = trim($_POST['empName']);
    $skills = explode(',', strtolower($_POST['searchSkills']));
    $skills = array_map('trim', $skills);

    if (!empty($empName) && count($skills) > 0) {
        $stmt = $conn->prepare("SELECT emp_id FROM employees WHERE emp_name = ?");
        $stmt->bind_param("s", $empName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $emp_id = $result->fetch_assoc()['emp_id'];
            $deletedSkills = [];

            foreach ($skills as $skill) {
                $skillStmt = $conn->prepare("SELECT skill_id FROM skills WHERE skill_name = ?");
                $skillStmt->bind_param("s", $skill);
                $skillStmt->execute();
                $skillRes = $skillStmt->get_result();

                if ($skillRes && $skillRes->num_rows > 0) {
                    $skill_id = $skillRes->fetch_assoc()['skill_id'];
                    $conn->query("DELETE FROM employee_skills WHERE emp_id=$emp_id AND skill_id=$skill_id");
                    $deletedSkills[] = htmlspecialchars($skill);
                }
            }

            if (!empty($deletedSkills)) {
                $deletionMessage = "Deleted skills for <strong>" . htmlspecialchars($empName) . "</strong>: " . implode(", ", $deletedSkills);
            } else {
                $deletionMessage = "No matching skills found to delete for <strong>" . htmlspecialchars($empName) . "</strong>.";
            }
        } else {
            $deletionMessage = "Employee <strong>" . htmlspecialchars($empName) . "</strong> not found.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Skill Manager</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="styles.css" />
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    .add, .search, .result { margin-left: 200px; }
    .add { margin-top: 200px; }
    input, button { margin: 30px; padding: 15px; }
    .employee { border: 5px solid grey; padding: 5px; border-radius: 5px; }
    .skill { background: #def; padding: 3px 6px; border-radius: 4px; display: inline-block; margin: 2px; }
  </style>
</head>
<body>
<header class="header">
  <a href="#" class="logo"><i class="fas fa-play"></i> EMPLOYEE-SKILL DATABASE</a>
  <nav class="navbar">
     <a href="index.php">HOME</a>
      <a href="searchbox.php">ADD</a>
      <a href="editbox.php">EDIT</a>
      <a href="searchbox.php">SEARCH</a>
  </nav>
</header>


<form method="post" class="add">
  <h1> DELETE EMPLOYEE'S SKILL</h1>
  <input type="text" name="empName" placeholder="Employee Name" required />
  <input type="text" name="searchSkills" placeholder="Skills to Delete (comma-separated)" required />
  <button type="submit" name="delete">DELETE</button>
</form>


<div class="result">
  <h1>DELETION STATUS</h1>
  <?php
  if (!empty($deletionMessage)) {
      echo "<div class='employee'>";
      echo "<strong>Deleted Skills For " . htmlspecialchars($empName) . ".</strong><br/>";
      
      $deletedSkillsArray = explode(", ", strip_tags($deletionMessage));
      if (count($deletedSkillsArray) > 1) {
        
          $skillsPart = array_slice($deletedSkillsArray, 1);
          foreach ($skillsPart as $skill) {
              echo "<span class='skill'>" . htmlspecialchars(trim($skill)) . "</span> ";
          }
      } 

      echo "</div>";
  }
  ?>
</div>


 
</body>
</html>
