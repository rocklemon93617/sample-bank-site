<?php 

session_start(); 

include "db_conn.php";

if (isset($_POST['pin']) && isset($_POST['password'])) {

    function validate($data){

       $data = trim($data);

       $data = stripslashes($data);

       $data = htmlspecialchars($data);

       return $data;

    }

    $pin = validate($_POST['pin']);

    $pass = validate($_POST['password']);

    if (empty($pin)) {

        header("Location: index.php?error=User Name is required");

        exit();

    }else if(empty($pass)){

        header("Location: index.php?error=Password is required");

        exit();

    }else{

        $sql = "SELECT * FROM Account WHERE pin='$pin' AND password='$pass'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            if ($row['pin'] === $pin && $row['password'] === $pass) {

                echo "Logged in!";

                $_SESSION['iban'] = $row['iban'];

                $_SESSION['pin'] = $row['pin'];

                $_SESSION['money'] = $row['money'];
                $_SESSION['cust_id'] = $row['cust_id'];

                header("Location: home.php");

                exit();

            }else{

                header("Location: index.php?error=Incorect pin or password");

                exit();

            }

        }else{

            header("Location: index.php?error=Incorect pin or password");

            exit();

        }

    }

}else{

    header("Location: index.php");

    exit();

}
