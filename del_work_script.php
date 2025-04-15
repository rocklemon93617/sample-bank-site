<?php 

session_start(); 

include "db_conn.php";

if (isset($_POST['work_id']) && isset($_SESSION['iban']) && isset($_SESSION['cust_id'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $cust_id = $_SESSION['cust_id'];

    $work_id = validate($_POST['work_id']);


    if (empty($work_id)) {

        header("Location: new_work.php?error=Work ID is required");

        exit();

    }else{

        $sql = "DELETE FROM Workplaces WHERE work_id = '$work_id' AND cust_id = '$cust_id'";

        $result = mysqli_query($conn, $sql);

        header("Location: manage_work.php?error=Succesfully deleted a job");
        exit();

        

    }

}else{

    header("Location: index.php");

    exit();

}
