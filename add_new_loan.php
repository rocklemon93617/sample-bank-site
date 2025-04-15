<?php 

session_start();
include "db_conn.php";

if (isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

     $cust_id = $_SESSION['cust_id'];

     $sql = "SELECT * FROM Workplaces WHERE cust_id ='$cust_id'";

        

          
 ?>

<!DOCTYPE html>

<html>

<head>

    <title>Add New Loan</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>



    <form action="add_new_loan_script.php" method="post">

        <h2>New Loan</h2>

            <?php if (isset($_GET['error'])) { ?>

                <p class="error"><?php echo $_GET['error']; ?></p>

            <?php } ?>

        <label>Amount of Money to Loan</label>

        <input type="number" name="money_to_loan" placeholder="Amount to loan"><br>

        <label>Amount of payments</label>

        <input type="number" name="payments_amount" placeholder="Amount of payments"><br>

        <button type="submit">Add Loan</button>

    </form>

     

     <a href="home.php">Back to Mainpage</a>
     <a href="logout.php">Logout</a>

</body>

</html>

<?php 

}else{

     header("Location: index.php");

     exit();

}

 ?>
