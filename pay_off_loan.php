
<?php 

session_start();
include "db_conn.php";

if (isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

     $cust_id = $_SESSION['cust_id'];
     $iban = $_SESSION['iban'];

     $sql = "SELECT * FROM Account WHERE cust_id ='$cust_id' AND iban = '$iban'";
  
     $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

          $row = mysqli_fetch_assoc($result);
          $acc_id = $row['acc_id'];
        }

     $sql = "SELECT * FROM loan_history WHERE acc_id ='$acc_id' AND is_active = 1";
  
          
 ?>

<!DOCTYPE html>

<html>

<head>

    <title>Manage loans</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>



     <h2>Your Current Loans</h2>
     <?php 
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              echo "loan ID:  ".$row['loan_id']."  Amount of borrowed Money:   ".$row['amount']."  Left to payoff:   ".$row['left_to_pay_off']."   Payments left:  ".$row['payments_left']."   Single payment amount:  ".$row['payment_value']."   Expected finalizing date: ".$row['end_date']."<br>";
            }
          } else {
            echo "No current loans";
          } ?>

    <form action="pay_off_loan_script.php" method="post">

        <h2>Select debt to pay off</h2>

            <?php if (isset($_GET['error'])) { ?>

                <p class="error"><?php echo $_GET['error']; ?></p>

            <?php } ?>

        <label>Loan ID</label>

        <input type="number" name="loan_id" placeholder="Loan ID"><br>

        <button type="submit">Confirm payment</button>

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
