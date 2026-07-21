<?php

session_start();
include "includes/config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if transaction ID is provided
if (!isset($_GET['id'])) {
    header("Location: transaction.php");
    exit();
}

$id = (int)$_GET['id'];

// Delete only if the transaction belongs to the logged-in user
$query = "DELETE FROM transactions
          WHERE id='$id' AND user_id='$user_id'";

if(mysqli_query($conn, $query)){
    header("Location: transaction.php");
    exit();
}else{
    echo "Unable to delete transaction.";
}
?>