<?php

session_start();

include "includes/config.php";


if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];

            header("Location: dashboard.php");
            exit();

        }else{

            $_SESSION['error'] = "Incorrect password";

            header("Location: login.php");
            exit();

        }

    }else{

        $_SESSION['error'] = "Account not found";

        header("Location: login.php");
        exit();

    }

}

?>



<!DOCTYPE html>
<html lang="en">

<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Login | Align</title>



<link rel="stylesheet" href="style.css">



<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


</head>



<body>



<section class="auth-section">



<div class="auth-container">



<!-- LEFT SIDE -->


<div class="auth-left fade-in">



<div class="logo">

Align<span>.</span>

</div>



<br>



<span class="badge">

Welcome Back

</span>



<h1>

Manage Your

<span>Money</span>

Smarter.

</h1>



<p>

Log in to your Align account and continue
tracking your income, expenses, budgets,
and financial goals—all in one secure place.

</p>




<img src="images/login-illustration.svg"
alt="Login Illustration">



</div>







<!-- RIGHT SIDE -->


<div class="auth-right fade-in">



<h2 class="auth-title">

Login

</h2>



<p class="auth-subtitle">

Sign in to continue to your dashboard.

</p>





<?php


if(isset($_SESSION['error'])){


echo '

<div class="alert alert-error">

'.$_SESSION['error'].'

</div>

';



unset($_SESSION['error']);


}


?>







<form action="login.php" method="POST" class="auth-form">





<div class="input-group">


<label>

Email Address

</label>


<input

type="email"

name="email"

placeholder="Enter your email"

required>


</div>








<div class="input-group">


<label>

Password

</label>



<input

type="password"

name="password"

placeholder="Enter your password"

required>



</div>








<div class="auth-options">


<label class="remember-me">


<input

type="checkbox"

name="remember">


Remember Me


</label>




<div class="forgot-password">
 <a href="forgot_password.php">Forgot Password?</a>
</div>




</div>








<button

type="submit"

name="login"

class="auth-btn">



<i class="fa-solid fa-right-to-bracket"></i>


Login


</button>








<div class="auth-divider">

OR

</div>








<div class="auth-footer">


Don't have an account?



<a href="register.php">

Create One

</a>



</div>






</form>






</div>





</div>



</section>






<script src="script.js"></script>



</body>

</html>