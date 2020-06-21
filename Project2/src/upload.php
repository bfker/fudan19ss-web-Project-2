<?php
    require_once("../config.php");
    session_start();
    if(isset($_GET['id'])) {
        $row = getRow($_GET['id']);
        $imageID = $_GET['id'];
        $title = $row['Title'];
        $description = $row['Description'];
        $cityCode = $row['CityCode'];
        $countryCodeISO = $row['CountryCodeISO'];
        $path = $row['PATH'];
        $content = $row['Content'];
    }
    else  {
        $newName= date( "YmdHis" ).rand(0,100000000);
        $imageID = getNewID();
    }
    $uid = getUID($_SESSION['Username']);


    function getRow($id) {
        try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "select * from travelimage where ImageID=:id";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->execute();
            $row = $statement->fetch();
            $pdo = null;
            return $row;
        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }


    }

    function getNewID() {
        try {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "select max(ImageID) from travelimage";
            $result = $pdo->query($sql);
            $row = $result->fetch();
            $id = $row['max(ImageID)']+1;
            return $id;

        }
        catch (PDOException $e) {
            die( $e->getMessage() );
        }
    }

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




?>


<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/upload.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/uploadImage.js"></script>
    <title>Upload</title>
</head>
<body>
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
                                            <li><a href=\"\"><i class=\" fa fa-cloud-upload\"></i> Upload</a></li>
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
            <div class="sider-title">
                <?php
                if(isset($_GET['id'])) echo "Modify";
                else echo "Upload"
                ?>
            </div>
            <form action="" class="search-bar " id="upload-form" method="post" enctype="multipart/form-data">
                <div id="image-upload">
                    <div id="upload-box">
                        <i class=" fa fa-cloud-upload"></i>
                        <?php
                            if(isset($_GET['id'])) echo "Click here to modify";
                            else echo "Click here to upload"
                        ?>

                        <input  class="file-input"  type="file" name="file" id="file" accept="image/jpg,image/jpeg,image/png,image/PNG" onchange="changePicture()">
                    </div>
                    <img src="#" id="img"/>
                    <br>
                </div>
                <label >Title</label>
                <input class="text" type="text" id="title"
                       <?php
                        if(isset($_GET['id'])) {
                            echo "value=\"".$title."\"";
                        }
                       ?>
                       >
                <br>
                <label >Description</label>
                <textarea class="textarea" id="description" name="Description"><?php
                     if(isset($_GET['id'])) {
                         echo $description;
                     }
                     ?></textarea>
                <br>
                <label > Content</label>
                    <select name="content" id="content" class="content-selector">

                        <option value="0">Filter by Content</option>
                        <option value="Scenery"
                            <?php
                            if(isset($_GET['id'])&&(strcmp($content,"Scenery")==0)) echo " selected";
                            ?>
                        >Scenery</option>
                        <option value="City"
                            <?php
                            if(isset($_GET['id'])&&(strcmp($content,"City")==0)) echo " selected";
                            ?>
                        >City</option>
                        <option value="Building"
                            <?php
                            if(isset($_GET['id'])&&(strcmp($content,"Building")==0)) echo "selected";
                            ?>
                        >Building</option>
                        <option value="People"
                            <?php
                            if(isset($_GET['id'])&&(strcmp($content,"People")==0)) echo "selected";
                            ?>
                        >People</option>
                        <option value="Animal"
                            <?php
                            if(isset($_GET['id'])&&(strcmp($content,"Animal")==0)) echo "selected";
                            ?>
                        >Animal</option>
                        <option value="Wonder"
                            <?php
                            if(isset($_GET['id'])&&(strcmp($content,"Wonder")==0)) echo "selected";
                            ?>
                        >Wonder</option>
                        <option value="Other" <?php
                        if(isset($_GET['id'])&&(strcmp($content,"Other")==0)) echo "selected";
                        ?>
                        >Other</option>
                    </select>
                <br>
                <label > Country</label>
                    <select name="CountryCodeISO" id="country" onchange="getSelectVal()">
                        <option value="0">Filter by Country</option>
                        <?php
                                try {
                                    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select * from geocountries order by CountryName";
                                    $result = $pdo->query($sql);
                                    while ($row = $result->fetch()) {
                                        if(isset($_GET['id']) &&(strcmp($countryCodeISO,$row['CountryName'])==0))
                                            echo "<option value=\"".$row['ISO']." selected\">".$row['CountryName']."</option>";
                                        else echo "<option value=\"".$row['ISO']."\">".$row['CountryName']."</option>";
                                     }
                                }
                                catch (PDOException $e) {
                                    die( $e->getMessage() );
                                }
                        ?>
                    </select>
                <br>
                <label > City</label>
                    <select name="CityCode" id="city">
                        <option value="0">Filter by City</option>
                    </select>
                <br>
                <div><button type="submit" id="submit"">Submit <i class="fa fa-search"></i> </button></div>
            </form>
            <?php
            if(isset($_GET['content'])) echo $_GET['content'];
            if(isset($_GET['Title'])) echo $_GET['Title'];

            ?>

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