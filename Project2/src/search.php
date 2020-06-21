<?php
    require_once("../config.php");
    session_start();
    function getImages($sql) {
        try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $resultAll = $pdo->query($sql);
            $rows = $resultAll->fetchAll();
            $rowCount = count($rows);
            if ( isset($_GET['page']) && $_GET['page'] >1) {
                $curPage = $_GET['page'];
            }else {
                $curPage = 1;
            }
            $limitPage = ($curPage - 1)*6;
            $sql = $sql." limit ".$limitPage.",6";
            $result = $pdo->query($sql);
            $num = 0;
            while ($row = $result->fetch()) {
                outputSingleImage($row);
                $num++;
            }
            $pdo = null;
            return $rowCount;
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
                        ."</div>
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
    <link href="css/search.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="js/radio.js"></script>
    <title>Search</title>
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
                    <li><a href="browse.php"><i class="fa fa-eye"></i> Browse</a></li>
                    <li class="active"><a href=""><i class="fa fa-search"></i> Search</a></li>
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
        <div class="sider flexbox-search"> 
            <div class="sider-title">Saerch</div>
            <form class="search-bar " method="get">
                <input id="radio1" type="radio" checked="checked" name="search" value="title" onclick="checkRadio(this)">
                <label for="radio1">Fliter by Title</label>
                <input class="text" name="keyword" id="text" type="text">
                <br>
                <input id="radio2" type="radio" name="search" value="description" onclick="checkRadio(this)">
                <label for="radio2">Fliter by Description</label>
                <textarea class="textarea" id="textarea" name="keyword" disabled="disabled" ></textarea>

                <div><button type="submit">Search <i class="fa fa-search"></i> </button></div>
            </form>
        </div> 
        
        <div class="sider flexbox-result"> 
            <div class="sider-title">Result</div>
            <?php
                $getList = "?";
                $sql = "select * from travelimage";
                if(isset($_GET['keyword'])) {
                    $str = str_replace(array("/r/n", "/r", "/n"), " ", $_GET['keyword']);
                    $str = str_replace('　', ' ', $str);
                    $str = preg_replace('/\s\s+/', ' ', $str);
                    $str = str_replace(' ', '%', $str);
                    /** @var TYPE_NAME $str */
                    if(strcmp($_GET['search'],"title")!=0) $sql = $sql." where Description like '%".$str."%'";
                    else $sql = $sql." where Title like '%".$str."%'";
                    $getList = $getList."&search=".$_GET['search'];
                    $getList = $getList."&keyword=".$_GET['keyword'];
                }
                $rowCount = getImages($sql);
                $pageCount = ceil(($rowCount + 1)/6);
                if($pageCount > 5) $pageCount = 5;
                if ( isset($_GET['page']) && $_GET['page'] >1) {
                    $curPage = $_GET['page'];
                }else {
                    $curPage = 1;
                }
            ?>
            <div class="page-number">
                <?php
                $prePage = $curPage - 1;
                $nxtPage = $curPage + 1;
                if($curPage > 1) echo "<li><a href=\"".$getList."&page=".$prePage."\"><i class='fa fa-angle-double-left'></i></a></li>";
                for($i = 0; $i < $pageCount; $i++) {
                    $page = $i+1;
                    echo "<li";
                    if($page == $curPage) echo " class = \"active\" ";
                    echo"><a href=\"".$getList."&page=".$page."\">".$page."</a></li>";
                }
                if($curPage < $pageCount) echo "<li><a href=\"".$getList."&page=".$nxtPage."\"><i class='fa fa-angle-double-right'></i></a></li>";
                ?>
                共<?php echo $pageCount; ?>页,当前在第<?php echo $curPage;?>页
                <!--< <span class="active">1</span> 2 3 4 5 6 ... 9 > </div>-->
            </div>
        </div>


    <div class="icon-button">
        <a href="#topanchor">
            <button> <i class="fa fa-angle-double-up"></i></button>
        </a>
    </div>

    <footer class="footer">
        <p> Copyright © 2020 XZR.All Rights Reserved.</p>
        <p>备案号：沪WEB备19302010085</p>
    </footer>
</body>
</html>