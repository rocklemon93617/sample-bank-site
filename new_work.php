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

    <title>Add New workplace</title>

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
          } 
          
          
          
          ?>

    <form action="add_work_script.php" method="post">

        <h2>New Job</h2>

            <?php if (isset($_GET['error'])) { ?>

                <p class="error"><?php echo $_GET['error']; ?></p>

            <?php } ?>

        <label>Work name</label>

        <input type="text" name="work_name" placeholder="Work name"><br>

        <label>Brutto Earnings</label>

        <input type="number" name="earn_brutto" placeholder="Brutto earnings"><br>

        <label for="is_temp">Is your job temporary(Select if so):</label><br>
    <input type="checkbox" id="is_temp" name="is_temp" value="1"><br>
    <input type="hidden" name="is_temp" value="0">

        <button type="submit">Add Job</button>

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
