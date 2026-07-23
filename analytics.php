<?php
session_start();
include "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===========================
   Dashboard Cards
=========================== */

// Total Income
$incomeQuery = mysqli_query($conn,"
SELECT SUM(amount) AS total_income
FROM transactions
WHERE user_id='$user_id'
AND type='Income'
");

$incomeData = mysqli_fetch_assoc($incomeQuery);
$totalIncome = $incomeData['total_income'] ?? 0;


// Total Expense
$expenseQuery = mysqli_query($conn,"
SELECT SUM(amount) AS total_expense
FROM transactions
WHERE user_id='$user_id'
AND type='Expense'
");

$expenseData = mysqli_fetch_assoc($expenseQuery);
$totalExpense = $expenseData['total_expense'] ?? 0;


// Net Savings
$netSavings = $totalIncome - $totalExpense;


// Total Transactions
$transactionQuery = mysqli_query($conn,"
SELECT COUNT(*) AS total_transactions
FROM transactions
WHERE user_id='$user_id'
");

$transactionData = mysqli_fetch_assoc($transactionQuery);
$totalTransactions = $transactionData['total_transactions'] ?? 0;


/* ===========================
   Doughnut Chart
=========================== */

$categoryQuery = mysqli_query($conn,"
SELECT category,
SUM(amount) AS total
FROM transactions
WHERE user_id='$user_id'
AND type='Expense'
GROUP BY category
");

$categoryLabels = [];
$categoryData = [];

while($row=mysqli_fetch_assoc($categoryQuery))
{
    $categoryLabels[]=$row['category'];
    $categoryData[]=(float)$row['total'];
}


/* ===========================
   Bar Chart
=========================== */

$monthlyQuery=mysqli_query($conn,"
SELECT

MONTH(transaction_date) AS month,

SUM(
CASE
WHEN type='Income'
THEN amount
ELSE 0
END
) AS income,

SUM(
CASE
WHEN type='Expense'
THEN amount
ELSE 0
END
) AS expense

FROM transactions

WHERE user_id='$user_id'

GROUP BY MONTH(transaction_date)

ORDER BY MONTH(transaction_date)
");

$months=[];
$incomeChart=[];
$expenseChart=[];

while($row=mysqli_fetch_assoc($monthlyQuery))
{

$months[]=date("M",mktime(0,0,0,$row['month'],1));

$incomeChart[]=(float)$row['income'];

$expenseChart[]=(float)$row['expense'];

}


/* ===========================
   Last 30 Days Spending Trend
=========================== */

$trendQuery = mysqli_query($conn, "
SELECT
transaction_date,
SUM(amount) AS total
FROM transactions
WHERE user_id='$user_id'
AND type='Expense'
AND transaction_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY transaction_date
ORDER BY transaction_date ASC
");

$trendMonths = [];
$trendData = [];

while($row = mysqli_fetch_assoc($trendQuery)){

    $trendMonths[] = date("d M", strtotime($row['transaction_date']));

    $trendData[] = (float)$row['total'];

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Analytics | ALIGN</title>

<link rel="stylesheet"
href="css/analytics.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include "includes/header.php"; ?>

<div class="analytics-container">

<div class="page-title">

<h1>Analytics Dashboard</h1>

<p>Visualize your income and expenses.</p>

</div>

<div class="analytics-cards">

<div class="card income-card">

<h3>Total Income</h3>

<h2>₹<?php echo number_format($totalIncome,2); ?></h2>

</div>


<div class="card expense-card">

<h3>Total Expense</h3>

<h2>₹<?php echo number_format($totalExpense,2); ?></h2>

</div>


<div class="card savings-card">

<h3>Net Savings</h3>

<h2>₹<?php echo number_format($netSavings,2); ?></h2>

</div>


<div class="card transaction-card">

<h3>Total Transactions</h3>

<h2><?php echo $totalTransactions; ?></h2>

</div>

</div>


<div class="charts-grid">

<div class="chart-card">

<h3>Monthly Income vs Expense</h3>

<canvas id="incomeExpenseChart"></canvas>

</div>


<div class="chart-card">

<h3>Expense by Category</h3>

<canvas id="categoryChart"></canvas>

</div>


<div class="chart-card full-width">

<h3>Daily Expense Trend</h3>

<canvas id="trendChart"></canvas>

</div>

</div>

</div>


<script>

const categoryLabels =
<?php echo json_encode($categoryLabels); ?>;

const categoryData =
<?php echo json_encode($categoryData); ?>;


const months =
<?php echo json_encode($months); ?>;

const incomeData =
<?php echo json_encode($incomeChart); ?>;

const expenseData =
<?php echo json_encode($expenseChart); ?>;


const trendMonths =
<?php echo json_encode($trendMonths); ?>;

const trendAmounts =
<?php echo json_encode($trendData); ?>;

</script>

<script src="js/analytics.js"></script>

</body>

</html>