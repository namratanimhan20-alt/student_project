<?php

session_start();
include "includes/config.php";

// Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch User
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: login.php");
    exit();
}

// Current Values
$full_name = $user['full_name'];
$email = $user['email'];
$currency = $user['currency'];
$monthly_budget = $user['monthly_budget'];
$profile_image = $user['profile_image'];

$message = "";
$error = "";

// =======================================
// UPDATE PROFILE
// =======================================

if(isset($_POST['update_profile'])){

    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $currency = mysqli_real_escape_string($conn, $_POST['currency']);
    $monthly_budget = floatval($_POST['monthly_budget']);

    // Check Email Already Exists
    $check = mysqli_query(
        $conn,
        "SELECT id FROM users
         WHERE email='$email'
         AND id!='$user_id'"
    );

    if(mysqli_num_rows($check)>0){

        $error = "Email already exists.";

    }else{

        $imageName = $profile_image;

        // Upload Image
        if(isset($_FILES['profile_image'])
            && $_FILES['profile_image']['error']==0){

            $allowed = ['jpg','jpeg','png','webp'];

            $extension = strtolower(
                pathinfo(
                    $_FILES['profile_image']['name'],
                    PATHINFO_EXTENSION
                )
            );

            if(in_array($extension,$allowed)){

                $imageName = time()."_".uniqid().".".$extension;

                move_uploaded_file(

                    $_FILES['profile_image']['tmp_name'],

                    "uploads/profile_images/".$imageName

                );

                // Delete old image
                if(
                    $profile_image!="default.png"
                    &&
                    file_exists(
                        "uploads/profile_images/".$profile_image
                    )
                ){

                    unlink(
                        "uploads/profile_images/".$profile_image
                    );

                }

            }else{

                $error = "Only JPG, JPEG, PNG and WEBP files are allowed.";

            }

        }

        if(empty($error)){

            $update = "

            UPDATE users SET

            full_name='$full_name',

            email='$email',

            currency='$currency',

            monthly_budget='$monthly_budget',

            profile_image='$imageName'

            WHERE id='$user_id'

            ";

            if(mysqli_query($conn,$update)){

                $message="Profile updated successfully.";

                $profile_image = $imageName;

            }else{

                $error="Something went wrong.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profile | ALIGN</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/edit_profile.css">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<?php include "includes/header.php"; ?>

<div class="edit-profile-container">

<div class="edit-card">

<div class="card-header">

<h1>Edit Profile</h1>

<p>

Keep your personal information up to date.

</p>

</div>



<?php if(!empty($message)){ ?>

<div class="success-message">

<i class="fa-solid fa-circle-check"></i>

<?php echo $message; ?>

</div>

<?php } ?>



<?php if(!empty($error)){ ?>

<div class="error-message">

<i class="fa-solid fa-circle-exclamation"></i>

<?php echo $error; ?>

</div>

<?php } ?>



<form method="POST"

enctype="multipart/form-data"

class="edit-form">



<!-- Profile Image -->

<div class="profile-image-section">

<img

src="uploads/profile_images/<?php echo $profile_image; ?>"

class="preview-image"

alt="Profile Image">



<label class="upload-btn">

<i class="fa-solid fa-camera"></i>

Change Photo

<input

type="file"

name="profile_image"

accept=".jpg,.jpeg,.png,.webp"

hidden>

</label>

</div>



<!-- Name -->

<div class="form-group">

<label>

<i class="fa-solid fa-user"></i>

Full Name

</label>

<input

type="text"

name="full_name"

value="<?php echo htmlspecialchars($full_name); ?>"

required>

</div>



<!-- Email -->

<div class="form-group">

<label>

<i class="fa-solid fa-envelope"></i>

Email Address

</label>

<input

type="email"

name="email"

value="<?php echo htmlspecialchars($email); ?>"

required>

</div>



<!-- Budget -->

<div class="form-group">

<label>

<i class="fa-solid fa-wallet"></i>

Monthly Budget

</label>

<input

type="number"

step="0.01"

min="0"

name="monthly_budget"

value="<?php echo $monthly_budget; ?>"

required>

</div>



<!-- Currency -->

<div class="form-group">

<label>

<i class="fa-solid fa-money-bill-wave"></i>

Preferred Currency

</label>

<select name="currency">

<option value="₹" <?php if($currency=="₹") echo "selected"; ?>>

₹ Indian Rupee

</option>

<option value="$" <?php if($currency=="$") echo "selected"; ?>>

$ US Dollar

</option>

<option value="€" <?php if($currency=="€") echo "selected"; ?>>

€ Euro

</option>

<option value="£" <?php if($currency=="£") echo "selected"; ?>>

£ British Pound

</option>

<option value="¥" <?php if($currency=="¥") echo "selected"; ?>>

¥ Japanese Yen

</option>

</select>

</div>



<div class="button-group">

<button

type="submit"

name="update_profile"

class="save-btn">

<i class="fa-solid fa-floppy-disk"></i>

Save Changes

</button>



<a href="profile.php"

class="cancel-btn">

<i class="fa-solid fa-arrow-left"></i>

Back to Profile

</a>

</div>
 
    </form>

</div>

</div>


<script>

const imageInput = document.querySelector('input[name="profile_image"]');

const previewImage = document.querySelector('.preview-image');

imageInput.addEventListener('change', function(e){

    const file = e.target.files[0];

    if(file){

        const reader = new FileReader();

        reader.onload = function(event){

            previewImage.src = event.target.result;

        }

        reader.readAsDataURL(file);

    }

});

</script>

</body>

</html>