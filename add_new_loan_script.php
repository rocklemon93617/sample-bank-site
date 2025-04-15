<?php 

session_start(); 
include "db_conn.php";

if (isset($_POST['money_to_loan']) && isset($_POST['payments_amount']) && isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $cust_id = $_SESSION['cust_id'];
    $iban = $_SESSION['iban'];

    $money_to_loan = validate($_POST['money_to_loan']);
    $payments_amount = validate($_POST['payments_amount']);

    $payment_value = $money_to_loan/$payments_amount + $money_to_loan*$payments_amount/100;

    if (empty($money_to_loan)) {

        header("Location: add_new_loan.php?error=Money to loan is required");

        exit();

    }
    else if (empty($payments_amount)) {

        header("Location: add_new_loan.php?error=Amount of payments is required");

        exit();

    }
    else{


        $sql = "SELECT * FROM Account WHERE cust_id ='$cust_id' AND iban = '$iban'";
  
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

          $row = mysqli_fetch_assoc($result);
          $acc_id = $row['acc_id'];
          $money = $row['money'];
        }

        $sql = "SELECT * FROM Customer WHERE cust_id ='$cust_id'";
  
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

          $row = mysqli_fetch_assoc($result);
          $total_income = $row['total_income'];
          $spendings = $row['spendings'];
        }
        if($total_income >= $spendings+$payment_value){
          $new_money = $money + $money_to_loan;
          $_SESSION['money'] = $new_money;
          $sql = "UPDATE Account SET money = '$new_money' WHERE acc_id = '$acc_id'";
     
        $result = mysqli_query($conn, $sql);


        $sql = "INSERT INTO loan_history (acc_id, amount, total_amount_of_payments) VALUES ('$acc_id', '$money_to_loan', '$payments_amount')";

        $result = mysqli_query($conn, $sql);

        header("Location: home.php?message=Succesfully loaned new money");
        exit();

        //echo $new_money." ".$new_left_to_pay_off." ".$new_payments_left;
        }
        else{
            echo "not enough income";
        }
    }

}else{

    header("Location: index.php");

    exit();

}
