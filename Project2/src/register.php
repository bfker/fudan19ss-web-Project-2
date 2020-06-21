<?php
    require_once("../config.php"); ?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <title>Register</title>
</head>

<?php
    if(isset($_POST['username'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];
    }

    function checkEmpty($username, $email, $password, $password2) {
        if ($username == null || $password == null || $password2 == null || $email == null) return false;
    }

    function checkPassword($password, $password2) {
        if ($password == $password2) return true;
        else return false;
    }

    function checkEmail($email) {
        $preg = '/^(\w{1,25})@(\w{1,16})(\.(\w{1,4})){1,3}$/';
        if (preg_match($preg, $email)) return true;
        else return false;
    }

    function insert($username, $password, $email) {
        try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `traveluser` (`UID`, `UserName`, `Pass`, `State`, `DateJoined`, `DateLastModified`) VALUES ('".$username."', '".$password."', '".$email."', NULL, NULL, NULL)";
            $result = $pdo->query($sql);
            $pdo = null;
            if($result) return true;
            else return false;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }

?>

<body>
<div class="box">
    <?php
    $str = "Register";
    echo "<h2>".$str."</h2>";
    ?>
    <form  method="post" role="form">
        <?php
            if(isset($_POST['username'])) {
                $check1 = checkEmpty($username, $email, $password, $password2);
                if(!$check1) echo "<div style='color: red;'>*Unfinished</div>";
            }
        ?>
        <div class="inputbox">
            <p>Username</p>
            <input type="text" name="username">
        </div>
        <div class="inputbox">
            <p>e-mail</p>
            <input type="text" name="email">
            <?php
            if(isset($_POST['username'])) {
                $check2 = checkEmail( $email);
                if(!$check2) echo "<div style='color: red;'>*Invalid email</div>";
            }
            ?>
        </div>

        <div class="inputbox">
            <p>Password</p>
            <input type="password" name="password">
        </div>
        <div class="inputbox">
            <p>Confirm Password</p>
            <input type="password" name="password2">
            <?php
            if(isset($_POST['username'])) {
                $check3 = checkPassword( $password,$password2);
                if(!$check3) echo "<div style='color: red;'>*Password mismatch</div>";
            }
            ?>
        </div>
        <input type="submit" Value="Register">

        <?php
            if(isset($_POST['username'])&& $check1 && $check2 && $check3) {
                if (insert($username, $password, $email))
                    header("location:http://localhost/project2/src/login.php");
            }
        ?>
    </form>
</div>

<footer>
    Copyright © 2020 XZR.All Rights Reserved. 备案号：沪WEB备19302010085
</footer>
</body>
</html>