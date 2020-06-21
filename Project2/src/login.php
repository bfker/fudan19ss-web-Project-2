<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <title>Login</title>
</head>
<body>
<?php
    require_once("../config.php");
    session_start();
    function validLogin(){

        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        //very simple (and insecure) check of valid credentials.
        $sql = "SELECT * FROM Traveluser WHERE UserName=:user and Pass=:pass";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':user',$_POST['username']);
        $statement->bindValue(':pass',$_POST['password']);
        $statement->execute();
        if($statement->rowCount()>0){

            return true;
        }
        return false;
    }
    if(isset($_SESSION['Username'])) header("location:http://localhost/project2/index.php");
?>
    <div class="box">
        <?php
            $str = "";
            if (isset($_POST['username'])) {
                if (validLogin()) {
                    // add 1 day to the current time for expiry time
                    $_SESSION['Username']=$_POST['username'];
                    header("location:http://localhost/project2/index.php");
                }
                else {
                    $str = "Unsuccessful";
                }
            }
            else $str = "Sign in here";
            echo "<h2>".$str."</h2>";
        ?>
        <form action="http://localhost/project2/src/login.php" method="post" role="form">
            <div class="inputbox">
                <p>Username</p>
                <input type="text" name="username">

            </div>
            <div class="inputbox">
                <p>Password</p>
                <input type="password" name="password">
            </div>
            <input type="submit" Value="Login">
            <a href="register.php">Creat an account</a>
        </form>
    </div>

    <footer>
        Copyright © 2020 XZR.All Rights Reserved. 备案号：沪WEB备19302010085
    </footer>
</body>
</html>