<?php

$conn = mysqli_connect("localhost", "root", "", "crud_demo");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>