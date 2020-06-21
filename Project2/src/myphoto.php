<?php
require_once("../config.php");
session_start();

function getUID($userName) {
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from traveluser where UserName = '".$userName."'";
        $result = $pdo->query($sql);
        $row = $result->fetch();
        $pdo = null;
        return $row['UID'];
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
$uid = getUID($_SESSION['Username']);

function getImages($sql) {
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $pdo->query($sql);
        $num = 0;
        while ($row = $result->fetch()) {
            outputSingleImage($row);
            $num++;
        }
        $pdo = null;
        return $num;
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }
}

function outputSingleImage($row) {
    echo "
                <div class=\"content\" >
                    <a href=\"src/details.php?id=".$row['ImageID']."\" class=\"square\" style=\"background-image:url(img/travel-images/medium/".$row['PATH'].")\"></a>
                    <div class=\"article\">
                        <div class=\"title\">".$row['Title']."</div>
                        <div class=\"words\">".$row['Description']
        ."</div> <div class=\"button-box\">
                            <button onclick=\"window.location.href='upload.php?id=".$row['ImageID']."';\">Modify</button>
                            <button onclick=\"alert('删除图片')\">Delate</button>
                        </div>
                    </div>
                </div>
                
        ";
}

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/myphoto.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="js/filter.js"></script>
    <title>My Photos</title>
</head>
<body>
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
                    <img alt="Brand" src="img/icons/logo.png" width="45px" height="45px" style="margin-top: -12px;">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="../index.php"><i class="fa fa-home"></i> Home</a></li>
                    <li class="active"><a href=""><i class="fa fa-eye"></i> Browse</a></li>
                    <li><a href="search.php"><i class="fa fa-search"></i> Search</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <?php
                    if(isset($_SESSION['Username'])) echo "
                            <li class=\"dropdown\">
                                    <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">
                                        <i class=\"fa fa-user-circle\"></i> My&nbsp;account<b class=\"caret\"></b>
                                    </a>
                                    <ul class=\"dropdown-menu\">
                                        <li><a href=\"upload.php\"><i class=\" fa fa-cloud-upload\"></i> Upload</a></li>
                                        <li><a href=\"myphoto.php\"><i class=\"fa fa-camera\"></i> Gallery</a></li>
                                        <li><a href=\"mycollection.php\"><i class=\"fa fa-star\"></i> My&nbsp;collection</a></li>
                                        <li><a href=\"logout.php\"><i class=\"fa fa-sign-out\"></i> Log&nbsp;out</a></li>
                                    </ul>
                             </li>
                            ";
                    else  {
                        echo "
                                <li><a href=\"login.php\"><i class=\"fa fa-sign-in\"></i> Sign&nbsp;in</a></li>
                            ";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height: 50px"></div>
    <div class = "wrapper">
        <div class="sider flexbox-result"> 
            <div class="sider-title">My photo</div>
            <?php
                $sql = "select * from travelimage where UID = '".$uid."'";
                $rowCount = getImages($sql);
                $pageCount = ceil(($rowCount + 1)/6);
                if($pageCount > 5) $pageCount = 5;
                if ( isset($_GET['page']) && $_GET['page'] >1) {
                    $curPage = $_GET['page'];
                }else {
                    $curPage = 1;
                }
                if($rowCount < 1) echo "<div style='text-align: center;'><br>You have no photos<br> Click 'upload' to upload one XD<br></div>";
            ?>
            <div class="page-number">
                <?php
                $prePage = $curPage - 1;
                $nxtPage = $curPage + 1;
                if($curPage > 1) echo "<li><a href=\"?page=".$prePage."\"><i class='fa fa-angle-double-left'></i></a></li>";
                for($i = 0; $i < $pageCount; $i++) {
                    $page = $i+1;
                    echo "<li";
                    if($page == $curPage) echo " class = \"active\" ";
                    echo"><a href=\"?page=".$page."\">".$page."</a></li>";
                }
                if($curPage < $pageCount) echo "<li><a href=\"?page=".$nxtPage."\"><i class='fa fa-angle-double-right'></i></a></li>";
                ?>

                共<?php echo $pageCount; ?>页,当前在第<?php echo $curPage;?>页
            </div>
        </div>


    <div class="icon-button">
        <a href="#topanchor">
            <button> <i class="fa fa-angle-double-up"></i></button>
        </a>

        <a onclick="alert('图⽚已刷新')">
            <button> <i class="fa fa-refresh"></i></button>
        </a>
    </div>

    <footer class="footer">
        <p> Copyright © 2020 XZR.All Rights Reserved.</p>
        <p>备案号：沪WEB备19302010085</p>
    </footer>
    
    
    <script type="text/javascript">
        $(".navbar-button").click(function() {
            $(this).toggleClass("active");
            $(".navbar-collapse").toggleClass("active");
        });

        cityList = new Object();
        cityList['China'] = new Array('Shanghai','Kunming','Beijing','Yantai');
        cityList['Japan'] = new Array('Tokyo','Osaka','Kamakura');
        cityList['Italy'] = new Array('Roma','Milan','Venice','Florence');
        cityList['America'] = new Array('New York','San Francisco','Washington');
        
        function setCity(country, city) {
            city.length = 1;
            if(country.value=='0') return; 
            for(var i=0; i<cityList[country.value].length; i++) {
                city.options[i+1] = new Option();
                city.options[i+1].text = cityList[country.value][i];
                city.options[i+1].value = cityList[country.value][i];
            }
        }
        
    </script>
</body>


</html>