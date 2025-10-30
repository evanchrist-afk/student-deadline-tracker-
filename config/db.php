<?php
$host = "127.0.0.1:3307"; // pakai port MySQL
$user = "root";           // default user
$pass = "";               // kosongin kalau gak pakai password
$db   = "student_deadline_tracker";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
