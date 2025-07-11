<?php
$db_server = "localhost:4306";
$db_user = "root";
$db_pass = "";
$db_name = "emp_db";
$conn = "";

$conn = mysqli_connect($db_server,
                       $db_user,
                       $db_pass, 
                       $db_name );


if($conn)
{
    echo"You are connected!";
}
else
{
    echo"Retry";
}

?>