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
    use services\Status as Status;
    use services\JSON as JSON;
    include_once('services/json.php');
    include_once('services/status.php');

    $method=$_SERVER['REQUEST_METHOD'];
    $path=$_SERVER['REQUEST_URI'];                        //$path=$_SERVER['REQUEST'];
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
        switch($method){
            case 'GET':
                if($path_arr[0]=='getuser')
                    getEmployee($conn,(int)$path_arr[1]);
                else if($path_arr[0]=='')
                    getEmployees($conn);
                break;
            case 'POST':
                if($path_arr[0]=='add')
                    addEmployee($conn,$data);
                break;
            case 'PATCH' :
                if($path_arr[0]='update')
                    updateEmployee($conn,(int)$path_arr[1],$data);
                break;
            case 'DELETE' :
                if($path_arr[0]=='delete')
                removeEmployee($conn,(int)$path_arr[1]);
                break;
            default :
                http_response_code(404);
                echo json_encode(["msg"=>"Not Found"]);
        }
    }
    else{
        Status::unauthorized();
        JSON::sendMessage('unauthorized access');
    }
?>