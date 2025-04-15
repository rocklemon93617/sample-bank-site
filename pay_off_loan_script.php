
<?php 

session_start(); 
include "db_conn.php";

if (isset($_POST['loan_id']) && isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $cust_id = $_SESSION['cust_id'];
    $iban = $_SESSION['iban'];

    $loan_id = validate($_POST['loan_id']);


    if (empty($loan_id)) {

        header("Location: pay_off_loan.php?error=loan id is required");

        exit();

    }else{


        $sql = "SELECT * FROM Account WHERE cust_id ='$cust_id' AND iban = '$iban'";
  
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

          $row = mysqli_fetch_assoc($result);
          $acc_id = $row['acc_id'];
          $money = $row['money'];
        }

        $sql = "SELECT * FROM loan_history WHERE acc_id ='$acc_id' AND is_active = 1 AND loan_id = '$loan_id'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

          $row = mysqli_fetch_assoc($result);
          $payment_value = $row['payment_value'];
          $left_to_pay_off = $row['left_to_pay_off'];
          $payments_left = $row['payments_left'];
        }
        else {

          header("Location: pay_off_loan.php?error=Invalid loan ID");
          exit();
        }
        if($money >= $payment_value){
          $new_money = $money - $payment_value;
          $_SESSION['money'] = $new_money;
          $new_left_to_pay_off = $left_to_pay_off - $payment_value;
          $new_payments_left = $payments_left - 1;
          $sql = "UPDATE Account SET money = '$new_money' WHERE acc_id = '$acc_id'";
     
        $result = mysqli_query($conn, $sql);


        $sql = "UPDATE loan_history SET left_to_pay_off = '$new_left_to_pay_off', payments_left = '$new_payments_left' WHERE acc_id = '$acc_id' AND loan_id = '$loan_id'";

        $result = mysqli_query($conn, $sql);

        header("Location: home.php?error=Succesfully paid");
        exit();

        //echo $new_money." ".$new_left_to_pay_off." ".$new_payments_left;
        }
        else{
            echo "not enough money";
        }
    }

}else{

    header("Location: index.php");

    exit();

}
