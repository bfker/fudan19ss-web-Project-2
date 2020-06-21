<?php
require_once("../config.php");

session_start();


function getPath($id)
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from travelimage where ImageID=:id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $row = $statement->fetch();
        $path = $row['PATH'];
        $pdo = null;
        return $path;
    } catch (PDOException $e) {
        die($e->getMessage());
    }
}

$upFile = $_FILES['file'];

if ($upFile['error']==0 && !empty($upFile)) {
    $uploadDir = 'img'.DIRECTORY_SEPARATOR.'travel-images'.DIRECTORY_SEPARATOR."medium".DIRECTORY_SEPARATOR;
    $filename = $_FILES['file']['name'];
    $dir = $uploadDir.DIRECTORY_SEPARATOR.$filename;


    if(move_uploaded_file($_FILES['file']['tmp_name'],$dir)){
        echo $dir;
    }
}

?>
