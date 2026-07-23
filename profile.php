<?php

session_start();

include "includes/config.php";


// Check login
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}


$user_id = $_SESSION['user_id'];


// Fetch user details
$user_query = "SELECT * FROM users WHERE id='$user_id'";

$user_result = mysqli_query($conn, $user_query);

$user = mysqli_fetch_assoc($user_result);



if(!$user){

    header("Location: login.php");
    exit();

}



// User data
$full_name = $user['full_name'];
$email = $user['email'];

$profile_image = !empty($user['profile_image']) 
                ? $user['profile_image'] 
                : "default.png";

$currency = !empty($user['currency'])
            ? $user['currency']
            : "₹";

$monthly_budget = !empty($user['monthly_budget'])
                  ? $user['monthly_budget']
                  : 0;



// Fetch transaction statistics


// Total Income

$income_query = "
SELECT SUM(amount) AS total_income 
FROM transactions 
WHERE user_id='$user_id'
AND type='Income'
";


$income_result = mysqli_query($conn,$income_query);

$income_data = mysqli_fetch_assoc($income_result);


$total_income = $income_data['total_income'] ?? 0;




// Total Expense

$expense_query = "
SELECT SUM(amount) AS total_expense 
FROM transactions 
WHERE user_id='$user_id'
AND type='Expense'
";


$expense_result = mysqli_query($conn,$expense_query);

$expense_data = mysqli_fetch_assoc($expense_result);


$total_expense = $expense_data['total_expense'] ?? 0;




// Balance

$balance = $total_income - $total_expense;



// Savings Percentage

if($total_income > 0){

    $saving_percentage = ($balance / $total_income) * 100;

}
else{

    $saving_percentage = 0;

}



// Total Transactions

$count_query = "
SELECT COUNT(*) AS total_transactions
FROM transactions
WHERE user_id='$user_id'
";


$count_result = mysqli_query($conn,$count_query);

$count_data = mysqli_fetch_assoc($count_result);


$total_transactions = $count_data['total_transactions'];




// Budget Usage

if($monthly_budget > 0){

    $budget_used = ($total_expense / $monthly_budget) * 100;

}
else{

    $budget_used = 0;

}



if($budget_used > 100){

    $budget_used = 100;

}



// Member Since

$created_date = date(
    "F Y",
    strtotime($user['created_at'])
);



?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile | ALIGN</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/profile.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<?php include "includes/header.php"; ?>

<div class="profile-wrapper">

<section class="profile-hero">

    <div class="hero-left">

        <div class="profile-image">

            <img src="uploads/profile_images/<?php echo $profile_image; ?>" alt="Profile">

        </div>

        <div class="hero-text">

            <span class="premium-tag">

                <i class="fa-solid fa-crown"></i>

                Premium Member

            </span>

            <h1>

                <?php echo htmlspecialchars($full_name); ?>

            </h1>

            <p>

                <?php echo htmlspecialchars($email); ?>

            </p>

            <div class="member-date">

                <i class="fa-regular fa-calendar"></i>

                Member since

                <strong>

                    <?php echo $created_date; ?>

                </strong>

            </div>

        </div>

    </div>

    <div class="hero-right">

        <a href="edit_profile.php" class="edit-btn">

            <i class="fa-solid fa-pen"></i>

            Edit Profile

        </a>

    </div>

</section>
 
<section class="overview-grid">

<div class="overview-card">

<div class="icon balance">

<i class="fa-solid fa-wallet"></i>

</div>

<div>

<h4>Total Balance</h4>

<h2>

<?php echo $currency; ?>

<?php echo number_format($balance,2); ?>

</h2>

</div>

</div>

<div class="overview-card">

<div class="icon income">

<i class="fa-solid fa-arrow-trend-up"></i>

</div>

<div>

<h4>Total Income</h4>

<h2>

<?php echo $currency; ?>

<?php echo number_format($total_income,2); ?>

</h2>

</div>

</div>

<div class="overview-card">

<div class="icon expense">

<i class="fa-solid fa-arrow-trend-down"></i>

</div>

<div>

<h4>Total Expenses</h4>

<h2>

<?php echo $currency; ?>

<?php echo number_format($total_expense,2); ?>

</h2>

</div>

</div>

<div class="overview-card">

<div class="icon transaction">

<i class="fa-solid fa-receipt"></i>

</div>

<div>

<h4>Transactions</h4>

<h2>

<?php echo $total_transactions; ?>

</h2>

</div>

</div>

</section>
 
<!-- ======================================================
        ACCOUNT & FINANCIAL DASHBOARD
======================================================= -->

<section class="dashboard-grid">

    <!-- ACCOUNT INFORMATION -->

    <div class="dashboard-card">

        <div class="card-title">

            <i class="fa-solid fa-user"></i>

            <h2>Account Information</h2>

        </div>

        <div class="info-list">

            <div class="info-row">

                <span>Full Name</span>

                <strong><?php echo htmlspecialchars($full_name); ?></strong>

            </div>

            <div class="info-row">

                <span>Email Address</span>

                <strong><?php echo htmlspecialchars($email); ?></strong>

            </div>

            <div class="info-row">

                <span>Preferred Currency</span>

                <strong><?php echo $currency; ?></strong>

            </div>

            <div class="info-row">

                <span>Member Since</span>

                <strong><?php echo $created_date; ?></strong>

            </div>

            <div class="info-row">

                <span>Monthly Budget</span>

                <strong>

                    <?php echo $currency; ?>

                    <?php echo number_format($monthly_budget,2); ?>

                </strong>

            </div>

        </div>

    </div>



    <!-- FINANCIAL SUMMARY -->

    <div class="dashboard-card">

        <div class="card-title">

            <i class="fa-solid fa-chart-pie"></i>

            <h2>Financial Summary</h2>

        </div>


        <div class="summary-grid">

            <div class="summary-item">

                <small>Total Income</small>

                <h3>

                    <?php echo $currency; ?>

                    <?php echo number_format($total_income,2); ?>

                </h3>

            </div>


            <div class="summary-item">

                <small>Total Expenses</small>

                <h3>

                    <?php echo $currency; ?>

                    <?php echo number_format($total_expense,2); ?>

                </h3>

            </div>


            <div class="summary-item">

                <small>Current Balance</small>

                <h3>

                    <?php echo $currency; ?>

                    <?php echo number_format($balance,2); ?>

                </h3>

            </div>


            <div class="summary-item">

                <small>Transactions</small>

                <h3>

                    <?php echo $total_transactions; ?>

                </h3>

            </div>

        </div>



        <!-- Savings -->

        <div class="progress-box">

            <div class="progress-header">

                <span>Savings Rate</span>

                <span>

                    <?php echo number_format($saving_percentage,1); ?>%

                </span>

            </div>

            <div class="progress-bar">

                <div class="progress-fill savings"

                     style="width:<?php echo min(100,max(0,$saving_percentage)); ?>%">

                </div>

            </div>

        </div>



        <!-- Budget -->

        <div class="progress-box">

            <div class="progress-header">

                <span>Monthly Budget Used</span>

                <span>

                    <?php echo number_format($budget_used,1); ?>%

                </span>

            </div>

            <div class="progress-bar">

                <div class="progress-fill budget"

                     style="width:<?php echo $budget_used; ?>%">

                </div>

            </div>

        </div>


        <!-- Financial Insight -->

        <div class="financial-tip">

            <?php

            if($saving_percentage >= 50){

                echo "<i class='fa-solid fa-star'></i> Excellent! You're saving more than half of your income.";

            }

            elseif($saving_percentage >= 20){

                echo "<i class='fa-solid fa-thumbs-up'></i> Great progress! Keep maintaining your savings habit.";

            }

            else{

                echo "<i class='fa-solid fa-lightbulb'></i> Try reducing unnecessary expenses to improve your savings.";

            }

            ?>

        </div>

    </div>

</section>
 
<!-- ======================================================
                    ACHIEVEMENTS
======================================================= -->

<section class="profile-section">

    <div class="section-heading">

        <i class="fa-solid fa-trophy"></i>

        <h2>Your Achievements</h2>

    </div>

    <div class="achievement-grid">

        <!-- First Step -->

        <div class="achievement-card">

            <div class="achievement-icon">

                💰

            </div>

            <h3>First Step</h3>

            <p>

                Successfully started your financial journey with ALIGN.

            </p>

        </div>



        <!-- Money Manager -->

        <div class="achievement-card">

            <div class="achievement-icon">

                📊

            </div>

            <h3>Money Manager</h3>

            <p>

                You have recorded

                <strong>

                    <?php echo $total_transactions; ?>

                </strong>

                transactions.

            </p>

        </div>



        <!-- Budget Keeper -->

        <div class="achievement-card">

            <div class="achievement-icon">

                🎯

            </div>

            <h3>Budget Keeper</h3>

            <p>

                Monthly Budget

                <strong>

                    <?php echo $currency; ?>
                    <?php echo number_format($monthly_budget,2); ?>

                </strong>

            </p>

        </div>



        <!-- Saver -->

        <div class="achievement-card">

            <div class="achievement-icon">

                🌸

            </div>

            <h3>Smart Saver</h3>

            <p>

                Savings Rate

                <strong>

                    <?php echo number_format($saving_percentage,1); ?>%

                </strong>

            </p>

        </div>

    </div>

</section>



<!-- ======================================================
                  QUICK ACTIONS
======================================================= -->

<section class="profile-section">

    <div class="section-heading">

        <i class="fa-solid fa-bolt"></i>

        <h2>Quick Actions</h2>

    </div>

    <div class="quick-action-grid">

        <a href="dashboard.php" class="action-card">

            <i class="fa-solid fa-house"></i>

            <span>Dashboard</span>

        </a>

        <a href="add_transaction.php" class="action-card">

            <i class="fa-solid fa-plus"></i>

            <span>Add Transaction</span>

        </a>

        <a href="transaction.php" class="action-card">

            <i class="fa-solid fa-wallet"></i>

            <span>Transactions</span>

        </a>

        <a href="edit_profile.php" class="action-card">

            <i class="fa-solid fa-user-pen"></i>

            <span>Edit Profile</span>

        </a>

        <a href="logout.php" class="action-card logout-card">

            <i class="fa-solid fa-right-from-bracket"></i>

            <span>Logout</span>

        </a>

    </div>

</section>



<!-- ======================================================
                     FOOTER
======================================================= -->

<footer class="profile-footer">

    <p>

        © <?php echo date("Y"); ?>

        ALIGN • Personal Expense Tracker

    </p>

    <span>

        Manage Smart. Save Better. Live Balanced.

    </span>

</footer>

</div>

</body>

</html>