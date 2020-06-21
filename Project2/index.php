<?php
   error_reporting(0);
    require_once("config.php");
    session_start();
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="src/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="src/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/css/reset.css" rel="stylesheet">
    <link href="src/css/home.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="src/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <title>Home</title>
</head>
<body>
<div class="big-wrapper">
    <div id="topanchor"></div>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    <img alt="Brand" src="src/img/icons/logo.png" width="45px" height="45px" style="margin-top: -12px;">
                </a>
            </div>
           <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-left">
                    <li class="active"><a href=""><i class="fa fa-home"></i> Home</a></li>
                    <li><a href="src/browse.php"><i class="fa fa-eye"></i> Browse</a></li>
                    <li><a href="src/search.php"><i class="fa fa-search"></i> Search</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                        <?php
                            if(isset($_SESSION['Username'])) echo "
                                <li class=\"dropdown\">
                                        <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
                                            <i class=\"fa fa-user-circle\"></i> My&nbsp;account<b class=\"caret\"></b>
                                        </a>
                                        <ul class=\"dropdown-menu\">
                                            <li><a href=\"src/upload.php\"><i class=\" fa fa-cloud-upload\"></i> Upload</a></li>
                                            <li><a href=\"src/myphoto.php\"><i class=\"fa fa-camera\"></i> Gallery</a></li>
                                            <li><a href=\"src/mycollection.php\"><i class=\"fa fa-star\"></i> My&nbsp;collection</a></li>
                                            <li><a href=\"src/logout.php\"><i class=\"fa fa-sign-out\"></i> Log&nbsp;out</a></li>
                                        </ul>
                                 </li>
                                ";
                            else  {
                                echo "
                                    <li><a href=\"src/login.php\"><i class=\"fa fa-sign-in\"></i> Sign&nbsp;in</a></li>
                                ";
                            }
                        ?>
                 </ul>
             </div>
        </div>
    </nav>
    <div class="large-image">
        <img src="src/img/homepage-image/show.jpg">
    </div>
    <?php
    function getImageIDs() {
        try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $queryResult = $pdo->query("select * from travelimage");
            $rowsSelect = $queryResult->fetchAll();
            $rowCount = count($rowsSelect);
            $existIDs = array();
            for($i = 0; $i <= $rowCount; $i++) $existIDs[$i] = false;
            $sql = "select ImageID,count(*) as Favor from travelimagefavor group by ImageID order by Favor desc";
            $result = $pdo->query($sql);
            $num = 0;
            while ($row = $result->fetch()) {
                $num++;
                getImages($row['ImageID']);
                $existIDs[$row['ImageID']] = true;
                if($num == 6) break;
            }
            $num = 6 - $num;
            while($num) {
                $randomID = rand(1,$rowCount);
                if(!$existIDs[$randomID]){
                    $num--;
                    getImages($randomID);
                    $existIDs[$randomID] = true;
                }
            }
            $pdo = null;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }
    function getImages($id) {
        try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "select * from travelimage where ImageID=:id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();
            while ($row = $statement->fetch()) {
                outputSingleImage($row);
            }
            $pdo = null;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }

    function outputSingleImage($row) {
        echo "<div class=\"thumbnail\"><br>";
        echo "<a href=\"src/details.php?id=".$row['ImageID'].
            "\" class=\"square\" style=\"background-image:url(src/img/travel-images/medium/".$row['PATH'].")\"></a>
                <h2>".$row['Title']."</h2>
                <p>".$row['Description']."</p>
                </div>
            ";
    }
    ?>
    <div class = "wrapper">
        <?php
            if(isset($_GET['refresh'])) {
                try {
                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $result = $pdo->query("select * from travelimage");
                    $rows = $result->fetchAll();
                    $rowCount = count($rows);
                    $existIDs = array();
                    for($i = 0; $i <= $rowCount; $i++) $existIDs[$i] = false;
                    $num = 6;
                    while($num) {
                        $randomID = rand(1,$rowCount);
                        if(!$existIDs[$randomID]){
                            $num--;
                            getImages($randomID);
                            $existIDs[$randomID] = true;
                        }
                    }
                    $pdo = null;
                }
                catch (PDOException $e) {
                    die( $e->getMessage() );
                }
            }
            else getImageIDs();
        ?>
    </div>
    <div class="icon-button">
        <a href="#topanchor">
            <button> <i class="fa fa-angle-double-up"></i></button>
        </a>

        <a href="index.php?refresh=true">
            <button> <i class="fa fa-refresh"></i></button>
        </a>
    </div>
    <div class="push"></div>
</div>
<footer>
    <div class="container">
        <div class="footer-link-box">
            <img class="QRcode" src="src/img/icons/QRcode.png">
            <div class="footer-link">
                <ul class="footer-about">
                    <li><a href="#">使用条款</a></li>
                    <li><a href="#">关于我们</a></li>
                    <li><a href="#">联系我们</a></li>
                </ul>
                <ul>
                    <li> Copyright © 2020 XZR.All Rights Reserved.</li>
                    <li>备案号：沪WEB备19302010085</li>
                </ul>
            </div>
        </div>
        <div class="right">
</footer>


<script type="text/javascript">
    $(".navbar-button").click(function() {
        $(this).toggleClass("active");
        $(".navbar-collapse").toggleClass("active");
    });
</script>
</body>


</html>