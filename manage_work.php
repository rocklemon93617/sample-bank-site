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

    <title>Manage workplaces</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>



     <h2>Your Current Jobs</h2>
     <?php 
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              echo $row["name"]. "  Brutto: " . $row["earnings_brutto"]. " €        Netto:  " . $row["earnings_netto"]. " €<br>";
            }
          } else {
            echo "No current jobs";
          } ?>
     

     <a href="new_work.php">Add new job</a>
     <a href="del_work.php">Delete existing job</a>
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
