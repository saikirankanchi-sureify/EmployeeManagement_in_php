<?php
    include_once('db_operations/add.php');
    include_once('db_operations/edit.php');
    include_once('db_operations/get.php');
    include_once('db_operations/remove.php');
    include_once('db_operations/connect.php');
    include_once('authentication/login.php');
    include_once('authentication/signup.php');
    include_once('authentication/check_token.php');
    include_once('vendor/autoload.php');

    $method=$_SERVER['REQUEST_METHOD'];
    $path=$_SERVER['REQUEST_URI'];
    $req=file_get_contents('php://input');
    $data=json_decode($req,true);
    $conn=getConn();
    $path_arr=explode('/',trim($path,'/'));
    $headers=getallheaders();
    if(isset($headers['Authorization']))
        $jwt=$headers['Authorization'];

    if($method=='POST' && $path_arr[0]=='login'){
        login($conn,$data);
        return;
    }

    if($method=='POST' && $path_arr[0]=='signup'){
        signup($conn,$data);
        return;
    }        

    if(isset($jwt) && checkToken($jwt)){

        if($method=='GET' && $path_arr[0]=='getuser'){
            getEmployee($conn,(int)$path_arr[1]);
        }

        if($method=='GET' && $path_arr[0]==''){
            getEmployees($conn);
        }

        if($method=='POST' && $path_arr[0]=='add'){
            addEmployee($conn,$data);
        }

        if($method=='PATCH' && $path_arr[0]='update'){
            updateEmployee($conn,(int)$path_arr[1],$data);
        }

        if($method=='DELETE' && $path_arr[0]=='delete'){
            removeEmployee($conn,(int)$path_arr[1]);
        }
        
    }
    else{
        http_response_code(401);
        echo json_encode(["msg"=>"unauthorized access"]);
    }
?>