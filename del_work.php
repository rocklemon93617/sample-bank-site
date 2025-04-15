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

    <title>Delete existing workplace</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>



     <h2>Your Current Jobs</h2>
     <?php 
        $result = mysqli_query($conn, $sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              echo "Job ID: ".$row["work_id"] ."  Job name:  ".$row["name"]. "  Brutto: " . $row["earnings_brutto"]. " €        Netto:  " . $row["earnings_netto"]. " €<br>";
            }
          } else {
            echo "No current jobs";
          } 
          
          
          
          ?>

    <form action="del_work_script.php" method="post">

        <h2>Job to delete</h2>

            <?php if (isset($_GET['error'])) { ?>

                <p class="error"><?php echo $_GET['error']; ?></p>

            <?php } ?>

        <label>ID of the job you wish to delete</label>

        <input type="number" name="work_id" placeholder="Work ID"><br>

        <button type="submit">Delete Job</button>

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
