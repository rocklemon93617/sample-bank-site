<?php 

session_start();
include "db_conn.php";

if (isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

     $cust_id = $_SESSION['cust_id'];

     $sql = "SELECT * FROM Customer WHERE cust_id ='$cust_id'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            if ($row['cust_id'] === $cust_id) {

  
                $name = $row['name'];


            }else{

                header("Location: index.php?error=Database error. try again later");

                exit();

            }
          }
 ?>

<!DOCTYPE html>

<html>

<head>

    <title>HOME</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

     <h1>Hello, <?php echo $name; ?></h1>

     <p>Account balance</p>
     <b><?php echo $_SESSION['money'] ?></b>

     <p>Customer ID</p>
     <b><?php echo $_SESSION['cust_id']?></b>

     <br>
     <p>pin</p>
     <b><?php echo $_SESSION['pin']?></b>

     <br>
     <p>iban</p>
     <b><?php echo $_SESSION['iban']?></b>


    <a href="manage_loans.php">Manage loans</a>
     <a href="manage_work.php">Manage workplaces</a>
     <a href="logout.php">Logout</a>

</body>

</html>

<?php 

}else{

     header("Location: index.php");

     exit();

}

 ?>
