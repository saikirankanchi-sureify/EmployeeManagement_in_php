<?php
    require 'vendor/autoload.php';

    use connection\DataBase;
    use controllers\Authentication;
    use middleware\AuthMiddleware;
    use controllers\Employee;
    use helpers\Response;
    use helpers\Status;

    header('Content-Type:Application/json');
    $method=$_SERVER['REQUEST_METHOD'];
    $path=$_SERVER['REQUEST_URI'];
    $req=file_get_contents('php://input');
    $data=json_decode($req,true);
    $conn=DataBase::getConn();
    $path_arr=explode('/',trim($path,'/'));
    $headers=getallheaders();
    
    if(isset($headers['Authorization']))
        $jwt=$headers['Authorization'];
    
    if($method=='POST' && $path_arr[0]=='login'){
        $auth=new Authentication($conn);
        $auth->login($data);
        return;
    }

    if($method=='POST' && $path_arr[0]=='signup'){
        $auth=new Authentication($conn);
        $auth->signup($data);
        return;
    }        
     if(isset($jwt) && AuthMiddleware::checkToken($jwt)){
        $employee=new Employee($conn);
        switch($method){
            case 'GET':
                if($path_arr[0]=='getuser')
                    $employee->getEmployee((int)$path_arr[1]);
                else if($path_arr[0]=='')
                    $employee->getEmployees();
                break;
            case 'POST':
                if($path_arr[0]=='add')
                    $employee->addEmployee($data);
                break;
            case 'PATCH' :
                if($path_arr[0]=='update')
                    $employee->updateEmployee((int)$path_arr[1],$data);
                break;
            case 'DELETE' :
                if($path_arr[0]=='delete')
                    $employee->removeEmployee((int)$path_arr[1]);
                break;
            default :
                Status::notFound();
                Response::sendMessage('Not Found');
        }
    }
    else{
        Status::unauthorized();
        Response::sendMessage('unauthorized access');
    }
?>