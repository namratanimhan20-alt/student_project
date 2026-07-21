<?php

session_start();
include "includes/config.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";
$messageType = "";

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: transaction.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch only the logged-in user's transaction
$query = "SELECT * FROM transactions
          WHERE id='$id' AND user_id='$user_id'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: transaction.php");
    exit();
}

$transaction = mysqli_fetch_assoc($result);

// Update transaction
if (isset($_POST['update_transaction'])) {

    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $transaction_date = mysqli_real_escape_string($conn, $_POST['transaction_date']);

    $update = "UPDATE transactions SET
                type='$type',
                category='$category',
                amount='$amount',
                description='$description',
                transaction_date='$transaction_date'
               WHERE id='$id' AND user_id='$user_id'";

    if (mysqli_query($conn, $update)) {

        header("Location: transaction.php");
        exit();

    } else {

        $message = "Something went wrong.";
        $messageType = "error";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Transaction</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/edit_transaction.css">

</head>

<body>
<?php include "includes/header.php"; ?>
<div class="form-container">

    <h2>Edit Transaction</h2>

    <?php
    if (!empty($message)) {
        echo "<div class='$messageType'>$message</div>";
    }
    ?>

    <form method="POST">

        <div class="input-group">
            <label>Transaction Type</label>

            <select name="type" required>
                <option value="Income" <?php if($transaction['type']=="Income") echo "selected"; ?>>
                    Income
                </option>

                <option value="Expense" <?php if($transaction['type']=="Expense") echo "selected"; ?>>
                    Expense
                </option>
            </select>
        </div>

        <div class="input-group">
            <label>Category</label>

            <input
                type="text"
                name="category"
                value="<?php echo htmlspecialchars($transaction['category']); ?>"
                required>
        </div>

        <div class="input-group">
            <label>Amount</label>

            <input
                type="number"
                step="0.01"
                name="amount"
                value="<?php echo $transaction['amount']; ?>"
                required>
        </div>

        <div class="input-group">
            <label>Description</label>

            <textarea
                name="description"
                rows="4"><?php echo htmlspecialchars($transaction['description']); ?></textarea>
        </div>

        <div class="input-group">
            <label>Date</label>

            <input
                type="date"
                name="transaction_date"
                value="<?php echo $transaction['transaction_date']; ?>"
                required>
        </div>

        <button type="submit" name="update_transaction" class="btn">
            Update Transaction
        </button>

        <br><br>

        <a href="transaction.php" class="back-link">
            ← Back to Transactions
        </a>

    </form>

</div>

</body>

</html>