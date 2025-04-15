
<?php 

session_start(); 

include "db_conn.php";

if (isset($_POST['cust_id']) && isset($_POST['password']) && isset($_POST['repeat_password'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $cust_id = validate($_POST['cust_id']);

    $pass = validate($_POST['password']);
    $rep_pass = validate($_POST['repeat_password']);

    if (empty($cust_id)) {

        header("Location: registration_old_customer.php?error=User ID is required");

        exit();

    }else if(empty($pass)){

        header("Location: registration_old_customer.php?error=Password is required");

        exit();

    }
    else if(empty($rep_pass)){

        header("Location: registration_old_customer.php?error=Repeat Password is required");

        exit();

    }
    else if($pass != $rep_pass){

        header("Location: registration_old_customer.php?error=Password and Repeat Password don't match");

        exit();

    }
    else{

      $sql = "INSERT INTO Account (password, cust_id) VALUES ('$pass', '$cust_id')";

        
      $result = mysqli_query($conn, $sql);
  #login newly registered User
    
      $sql = "SELECT * FROM Account WHERE cust_id = '$cust_id' ORDER BY acc_id DESC LIMIT 1";
      $result = mysqli_query($conn, $sql);


        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);


                echo "Registration Succesful!";

                $_SESSION['iban'] = $row['iban'];

                $_SESSION['pin'] = $row['pin'];

                $_SESSION['money'] = $row['money'];
                $_SESSION['cust_id'] = $row['cust_id'];

                header("Location: home.php");

                exit();

        }
    }

}else{

    header("Location: registration_old_customer.php");

    exit();

}
