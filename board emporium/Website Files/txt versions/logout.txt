<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Logout</title>
    </head>
    <body>
        <?php
        //starts session
        session_start();
        //Clears session variables
        session_destroy();
        //Closes any open database connections
        mysqli_close();
        //Redirects user to the login page
        header("Location: home.php");
        ?>
    </body>
</html>