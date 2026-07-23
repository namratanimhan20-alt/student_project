<?php

session_start();

include "includes/config.php";


if(!isset($_SESSION['user_id'])){

    header("Location: login.php");
    exit();

}


$user_id = $_SESSION['user_id'];


$query = "SELECT * FROM transactions 
          WHERE user_id='$user_id'
          ORDER BY transaction_date DESC";


$result = mysqli_query($conn,$query);


?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Transactions</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="css/header.css">
<link rel="stylesheet" href="css/style2.css">

</head>


<body>


<?php include "includes/header.php"; ?>



<div class="container">


<div class="page-header">

<h1>
Transaction History
</h1>


<a href="add_transaction.php" class="add-btn">
+ Add Transaction
</a>


</div>




<div class="table-container">

<table>


<thead>

<tr>

<th>Type</th>
<th>Category</th>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>



<tbody>


<?php


if(mysqli_num_rows($result)>0){


while($row=mysqli_fetch_assoc($result)){


?>


<tr>

<td>
<?php echo $row['type']; ?>
</td>


<td>
<?php echo $row['category']; ?>
</td>


<td>
<?php echo $row['description']; ?>
</td>


<td>
₹ <?php echo $row['amount']; ?>
</td>


<td>
<?php echo $row['transaction_date']; ?>
</td>

<td>

<a href="edit_transaction.php?id=<?php echo $row['id']; ?>" class="edit-btn">
    Edit
</a>

<a href="delete_transaction.php?id=<?php echo $row['id']; ?>"
   class="delete-btn"
   onclick="return confirm('Are you sure you want to delete this transaction?');">
    Delete
</a>

</td>

</tr>

<?php

}

}
else{

?>


<tr>

<td colspan="6">

No transactions found

</td>

</tr>


<?php

}


?>


</tbody>


</table>
</div>



</div>



</body>

</html>