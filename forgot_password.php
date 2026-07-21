<?php

include "includes/config.php";

$message = "";
$messageType = "";

if(isset($_POST['reset_password'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check)==0){

        $message="Email not found.";
        $messageType="error";

    }
    elseif($password != $confirm_password){

        $message="Passwords do not match.";
        $messageType="error";

    }
    else{

        $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

        mysqli_query($conn,"UPDATE users
                            SET password='$hashedPassword'
                            WHERE email='$email'");

        $message="Password updated successfully.";
        $messageType="success";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Forgot Password</title>

<link rel="stylesheet" href="css/forgot_password.css">

</head>

<body>

<div class="forgot-container">

<h2>Reset Password</h2>

<?php
if($message!=""){
    echo "<div class='$messageType'>$message</div>";
}
?>

<form method="POST">

<input
type="email"
name="email"
placeholder="Enter your Email"
required>

<input
type="password"
name="password"
placeholder="New Password"
required>

<input
type="password"
name="confirm_password"
placeholder="Confirm Password"
required>

<button
type="submit"
name="reset_password">

Reset Password

</button>

<a href="login.php">
Back to Login
</a>

</form>

</div>

</body>

</html>