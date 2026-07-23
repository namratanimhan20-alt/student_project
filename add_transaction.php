<?php

session_start();
include "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";
$messageType = "";

if(isset($_POST['save_transaction'])){

    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $transaction_date = $_POST['transaction_date'];
    $description = $_POST['description'];

    $query = "INSERT INTO transactions
    (user_id, type, category, amount, description, transaction_date)
    VALUES
    ('$user_id','$type','$category','$amount','$description','$transaction_date')";

    if(mysqli_query($conn, $query)){

        $message = "Transaction added successfully!";
        $messageType = "success";

    }else{

       $message = "Error: " . mysqli_error($conn);
        $messageType = "error";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Transaction | Align</title>
   
    <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="css/header.css">
   <link rel="stylesheet" href="add_style1.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<div class="dashboard-container">

    <header class="dashboard-header">

        <div class="logo">
            Align<span>.</span>
        </div>

        <a href="dashboard.php" class="logout-btn">
            <i class="fa-solid fa-arrow-left"></i>
            Back
        </a>

    </header>

    <section class="transaction-form-section">

        <div class="transaction-card">
            <?php if($message!=""){ ?>

<div class="alert <?php echo ($messageType=="success") ? "alert-success" : "alert-error"; ?>">

    <?php echo $message; ?>

</div>

<?php } ?>

            <h2>Add New Transaction</h2>

            <form method="POST">

                <div class="input-group">

                    <label>Transaction Type</label>

                    <select name="type" required>

                        <option value="">Select Type</option>
                        <option value="Income">Income</option>
                        <option value="Expense">Expense</option>

                    </select>

                </div>

                <div class="input-group">

                    <label>Category</label>

                    <input
                        type="text"
                        name="category"
                        placeholder="Example: Salary, Food, Travel"
                        required>

                </div>

                <div class="input-group">

                    <label>Amount</label>

                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        placeholder="Enter Amount"
                        required>

                </div>

                <div class="input-group">

                    <label>Date</label>

                    <input
                        type="date"
                        name="transaction_date"
                        required>

                </div>

                <div class="input-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Optional"></textarea>

                </div>

                <button type="submit" name="save_transaction" class="auth-btn">

                    <i class="fa-solid fa-floppy-disk"></i>

                    Save Transaction

                </button>

            </form>

        </div>

    </section>

</div>

</body>

</html>