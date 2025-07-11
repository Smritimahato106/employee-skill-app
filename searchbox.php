<?php
 include("db.php");

 
if (isset($_POST['add'])) {
    $name = trim($_POST['empName']);
    $skills = explode(',', strtolower($_POST['empSkills']));
    $skills = array_map('trim', $skills);

    if (!empty($name) && count($skills)) {
        $stmt = $conn->prepare("INSERT INTO employees (emp_name) VALUES (?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $emp_id = $conn->insert_id;

        foreach ($skills as $skill) {
            if (empty($skill)) continue;

           
            $conn->query("INSERT IGNORE INTO skills (skill_name) VALUES ('$skill')");

            $res = $conn->query("SELECT skill_id FROM skills WHERE skill_name = '$skill'");
            if ($res && $res->num_rows > 0) {
                $skill_id = $res->fetch_assoc()['skill_id'];
                $conn->query("INSERT INTO employee_skills (emp_id, skill_id) VALUES ($emp_id, $skill_id)");
            }
        }

        echo "<script>alert('Employee added successfully.');</script>";
    }
}


$searchResults = [];
if (isset($_POST['search'])) {
    $searchSkills = explode(',', strtolower($_POST['searchSkills']));
    $searchSkills = array_map('trim', $searchSkills);
    $skillList = "'" . implode("','", $searchSkills) . "'";

    $sql = "
        SELECT e.emp_name, GROUP_CONCAT(s.skill_name) AS skills, COUNT(*) AS match_count
        FROM employees e
        JOIN employee_skills es ON e.emp_id = es.emp_id
        JOIN skills s ON es.skill_id = s.skill_id
        WHERE s.skill_name IN ($skillList)
        GROUP BY e.emp_id
        ORDER BY match_count DESC;
    ";
    $searchResults = $conn->query($sql);
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
  <a href="#" class="logo"><i class="fas fa-user"></i> EMPLOYEE-SKILL DATABASE</a>
  <nav class="navbar">
    <a href="index.php">HOME</a>
      <a href="searchbox.php">ADD</a>
      <a href="editbox.php">EDIT</a>
      <a href="searchbox.php">SEARCH</a>
  </nav>
</header>

<form method="post" class="add">
  <h1>ADD EMPLOYEE DETAILS</h1>
  <input type="text" name="empName" placeholder="Employee Name" required />
  <input type="text" name="empSkills" placeholder="Skills (comma-separated)" required />
  <button type="submit" name="add">ADD EMPLOYEE DETAILS</button>
</form>

<form method="post" class="search">
  <h1>SEARCH EMPLOYEE BY SKILL</h1>
  <input type="text" name="searchSkills" placeholder="Search Skills (comma-separated)" required />
  <button type="submit" name="search">Search</button>
</form>



<div class="result">
  <h1>RESULTS</h1>
  <div id="result">

   <?php
if (isset($searchResults) && $searchResults instanceof mysqli_result) {
    if ($searchResults->num_rows > 0) {
        while ($row = $searchResults->fetch_assoc()) {
            echo "<div class='employee'>";
            echo "<strong>" . htmlspecialchars($row['emp_name']) . "</strong><br/>";
            echo "Skills: ";
            foreach (explode(',', $row['skills']) as $sk) {
                echo "<span class='skill'>" . htmlspecialchars(trim($sk)) . "</span> ";
            }
            echo "<br/>Matches: " . htmlspecialchars($row['match_count']);
            echo "</div>";
        }
    } elseif (isset($_POST['search'])) {
        echo "<p>No matching employees found.</p>";
    }
} elseif (isset($_POST['search'])) {
    echo "<p>Error executing search query. Please try again later.</p>";
}
?>

  </div>
</div>


  
</body>
</html>
