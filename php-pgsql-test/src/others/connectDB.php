<?php
    include_once('check_token.php');
    include_once('login.php');
    header('Content-Type:Application/json');
    $req=file_get_contents('php://input');
    $data=json_decode($req,true);
    try{
        $headers=getallheaders();
        $jwt=$headers['Authorization'];
        $conn=new PDO('pgsql:host=db;dbname=mydb','saikirankanchi','saikirankanchi');
        checkToken($jwt);
    }
    catch(PDOException $err){
        echo $err->getMessage();
    }
?>
