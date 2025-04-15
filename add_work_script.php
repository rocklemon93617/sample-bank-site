<?php 

session_start(); 

include "db_conn.php";

if (isset($_POST['work_name']) && isset($_POST['earn_brutto']) && isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $cust_id = $_SESSION['cust_id'];

    $new_work_name = validate($_POST['work_name']);

    $new_brutto = validate($_POST['earn_brutto']);

    $is_temp = validate($_POST['is_temp']);

    if (empty($new_work_name)) {

        header("Location: new_work.php?error=Work name is required");

        exit();

    }else if(empty($new_brutto)){

        header("Location: new_work.php?error=Brutto earnings are required is required");

        exit();

    }else{

        $sql = "INSERT INTO Workplaces (cust_id, earnings_brutto, name, temporary) VALUES
        ('$cust_id', '$new_brutto', '$new_work_name', '$is_temp')";

        $result = mysqli_query($conn, $sql);

        header("Location: manage_work.php?error=Succesfully added new job");
        exit();

        

    }

}else{

    header("Location: index.php");

    exit();

}
