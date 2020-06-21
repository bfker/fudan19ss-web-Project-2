<?php
require_once("../config.php");
session_start();
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
    <title>Details</title>
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
        <div class="sider flexbox-result"> 
            <div class="sider-title">Details</div>
                <div class="title">一张图片<span class="small"><small> <small>by XZRs</small></small></span></div>
                <a href="#" id="img-1" class="square" style="background-image:url(img/travel-images/normal/medium/5855735700.jpg)"></a>
                <div class="content" >
                    
                    <div class="article">
                       
                         <div class="button-box">
                            <button onclick="alert('赞')"><i class="fa fa-heart"></i> 99</button>
                        </div>
                         <div class="title-s">Content: <span> Scenery </span></div>
                         <div class="title-s">country: <span> China </span></div>
                         <div class="title-s">City: <span> Shanghai </span></div>
                        <div class="words">
                            It can be challenging to coordinate a trip and time away from work with another busy person in your life, and it’s even more difficult when you try to make plans with a group. Usually, these challenges can be overcome by simply planning far in advance, but if you find yourself with some down time and you can’t find a travel partner on short notice, it may be the perfect time to pack your bags and go anyway.Is there something you’ve been dying to try that no one is willing to try with you? Maybe you’d like to go skydiving over the Grand Canyon, see ancient Mayan ruins, or simply eat real Maine lobster. When your partner and friends don’t share every one of your interests, that’s OK, but it’s not OK to sacrifice your dreams, especially when all you have to do is get there.
                        </div>
                        
                    </div>
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