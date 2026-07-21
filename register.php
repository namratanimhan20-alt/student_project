<?php

include "includes/config.php";

$message = "";
$messageType = "";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
   $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    // Check existing email
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);


    if(mysqli_num_rows($result) > 0){

        $message = "Email already registered!";
        $messageType = "error";

    }
    else{

       $sql = "INSERT INTO users (full_name, email, password)
        VALUES ('$name','$email','$password')";

        if(mysqli_query($conn,$sql)){

            $message = "Registration successful! You can login now.";
            $messageType = "success";

        }
        else{

            $message = "Registration failed!";
            $messageType = "error";

        }

    }

}

?>


<!DOCTYPE html>
<html>

<head>

<title>Register - Align</title>

<link rel="stylesheet" href="style.css">

</head>


<body>


<section class="auth-section">


<div class="auth-container">



    <div class="auth-left">

        <h1>
            Join <span>Align</span>
        </h1>


        <p>
            Create your account and start managing
            your expenses in a smarter way.
        </p>


    </div>




    <div class="auth-right">


        <h2 class="auth-title">
            Create Account
        </h2>


        <p class="auth-subtitle">
            Register to start your financial journey.
        </p>



        <?php

        if($message != ""){

            if($messageType == "success"){

                echo "<div class='alert alert-success'>$message</div>";

            }
            else{

                echo "<div class='alert alert-error'>$message</div>";

            }

        }

        ?>




        <form method="POST" class="auth-form">



            <div class="input-group">

                <label>Name</label>

                <input 
                type="text"
                name="name"
                placeholder="Enter your name"
                required>

            </div>




            <div class="input-group">

                <label>Email</label>

                <input 
                type="email"
                name="email"
                placeholder="Enter your email"
                required>

            </div>




            <div class="input-group">

                <label>Password</label>

                <input 
                type="password"
                name="password"
                placeholder="Create password"
                required>

            </div>




            <button 
            type="submit"
            name="register"
            class="auth-btn">

                Register

            </button>



        </form>




        <div class="auth-footer">

            Already have an account?

            <a href="login.php">
                Login
            </a>


        </div>



    </div>



</div>


</section>



</body>

</html>