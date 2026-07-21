<?php

session_start();

include "includes/config.php";

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Fetch user name
$user_query = "SELECT full_name FROM users WHERE id='$user_id'";
$user_result = mysqli_query($conn,$user_query);

$user = mysqli_fetch_assoc($user_result);

$name = $user['full_name'];


// Total Income
$income_query = "SELECT SUM(amount) AS total_income 
                 FROM transactions 
                 WHERE user_id='$user_id' 
                 AND type='Income'";

$income_result = mysqli_query($conn,$income_query);
$income_data = mysqli_fetch_assoc($income_result);

$total_income = $income_data['total_income'] ?? 0;


// Total Expense
$expense_query = "SELECT SUM(amount) AS total_expense 
                  FROM transactions 
                  WHERE user_id='$user_id' 
                  AND type='Expense'";

$expense_result = mysqli_query($conn,$expense_query);
$expense_data = mysqli_fetch_assoc($expense_result);

$total_expense = $expense_data['total_expense'] ?? 0;


// Balance
$balance = $total_income - $total_expense;


// Recent Transactions
$transaction_query = "SELECT * FROM transactions
                      WHERE user_id='$user_id'
                      ORDER BY id DESC
                      LIMIT 5";

$transactions = mysqli_query($conn,$transaction_query);

?>
<?php include "includes/header.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<link rel="stylesheet" href="css/dashboard.css">

</head>


<body>


<div class="dashboard-container">


<h1>
🌸 Welcome, <?php echo htmlspecialchars($name); ?>
</h1>


<div class="dashboard-cards">


<div class="card balance-card">

<h3>Total Balance</h3>

<h2>
₹<?php echo number_format($balance,2); ?>
</h2>

</div>



<div class="card income-card">

<h3>Total Income</h3>

<h2>
₹<?php echo number_format($total_income,2); ?>
</h2>

</div>



<div class="card expense-card">

<h3>Total Expense</h3>

<h2>
₹<?php echo number_format($total_expense,2); ?>
</h2>

</div>


</div>


<div class="actions">

<a href="add_transaction.php">
+ Add Transaction
</a>


<a href="transaction.php">
View Transactions
</a>

</div>



<div class="recent">


<h2>Recent Transactions</h2>


<table>


<tr>

<th>Type</th>
<th>Category</th>
<th>Amount</th>
<th>Date</th>

</tr>



<?php


if(mysqli_num_rows($transactions)>0){


while($row=mysqli_fetch_assoc($transactions)){


?>


<tr>


<td>

<?php echo $row['type']; ?>

</td>


<td>

<?php echo htmlspecialchars($row['category']); ?>

</td>


<td>

₹<?php echo number_format($row['amount'],2); ?>

</td>


<td>

<?php echo date("d M Y",strtotime($row['transaction_date'])); ?>

</td>


</tr>


<?php


}


}else{


?>


<tr>

<td colspan="4">

📭 No transactions yet.

</td>

</tr>


<?php


}

?>


</table>


</div>


</div>


</body>

</html>