<?php
    require_once("../config.php");
    try {
        $countryCodeISO = $_POST['countryCodeISO'];
        if(isset($countryCodeISO)){
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql ="select * from geocities where CountryCodeISO = '".$countryCodeISO."' order by 'AsciiName'";
            $result = $pdo->query($sql);
            $num = 0;
            while ($row = $result->fetch()) {
                $select[$num] = "<option value=\"".$row['GeoNameID']."\">". $row['AsciiName']."</option>";
               // $select[$num] = array("geoNameID"=>$row['GeoNameID'],"asciiName"=>$row['AsciiName']);
                $num++;
            }
            if($num > 0) echo json_encode(['code'=>200, 'select'=> $select]);
            else echo json_encode(['code'=>500]);
        }
    }
    catch (PDOException $e) {
        die( $e->getMessage() );
    }

?>
