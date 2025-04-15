

<!DOCTYPE html>

<html>

<head>

    <title>Create new Account</title>

    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>




    <form action="registration_new_customer_script.php" method="post">

        <h2>Create an account</h2>

            <?php if (isset($_GET['error'])) { ?>

                <p class="error"><?php echo $_GET['error']; ?></p>

            <?php } ?>

        <label>Name</label>

        <input type="text" name="cust_name" placeholder="Your Name"><br>

        <label>Last Name</label>

        <input type="text" name="cust_last_name" placeholder="Your Last Name"><br>
        

        <label>Password</label>

        <input type="password" name="password" placeholder="Password"><br>

        <label>Repeat Password</label>

        <input type="password" name="repeat_password" placeholder="Repeat Password"><br>

        <button type="submit">Register</button>

    </form>

</body>

</html>

