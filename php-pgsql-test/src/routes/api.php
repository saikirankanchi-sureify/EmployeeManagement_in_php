<?php
    require 'vendor/autoload.php';

    use connection\DataBase;
    use controllers\Authentication;
    use middleware\AuthMiddleware;
    use controllers\Employee;
    use helpers\Response;
    use helpers\Request;
    use helpers\Status;
    use helpers\App;

    $params=Request::getParams();
    $data=Request::getBody();
    $jwt=Request::getHeader('Authorization');
    $conn=DataBase::getConn();
    Response::setHeader('Content-Type:Application/json');

    $app=new App();

    $app->post('login',function() use($conn,$data){
        $auth=new Authentication($conn);
        $auth->login($data);
    });

    $app->post('signup',function() use($conn,$data){
        $auth=new Authentication($conn);
        $auth->signup($data);
    });    
    
    if(isset($jwt) && AuthMiddleware::checkToken($jwt)){

        $employee=new Employee($conn);

        $app->get('getuser',function()use($params,$employee){
            $employee->getEmployee($params);
        });

        $app->get('',function()use($params,$employee){
            $employee->getEmployees();
        });

        $app->post('add',function()use($data,$employee){
            $employee->addEmployee($data);
        });

        $app->patch('update',function()use($data,$employee,$params){
            $employee->updateEmployee($params,$data);
        });

        $app->delete('delete',function()use($employee,$params){
            $employee->removeEmployee($params);
        });

    }
    else{
        Status::unauthorized();
        Response::sendMessage('unauthorized access');
    }
?>