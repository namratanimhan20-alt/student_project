<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<header class="navbar">

    <div class="logo">
        ALIGN.
    </div>

    <nav>

        <a href="/student_project/dashboard.php">
            Dashboard
        </a>

        <a href="/student_project/transaction.php">
            Transactions
        </a>

        <a href="/student_project/add_transaction.php">
            Add Transaction
        </a>

    </nav>


    <div class="profile">

        <span>👤</span>

        <a href="/student_project/logout.php">
            Logout
        </a>

    </div>

</header>