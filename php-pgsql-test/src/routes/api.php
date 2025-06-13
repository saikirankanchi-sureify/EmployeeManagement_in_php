<?php
    require 'vendor/autoload.php';

    use controllers\Authentication;
    use middleware\AuthMiddleware;
    use controllers\Employee;
    use helpers\Response;
    use helpers\Request;
    use helpers\Status;
    use helpers\App;
    use logger\Logger;
    use connection\DataBase;

    $params=Request::getParams();
    $data=Request::getBody();
    $jwt=Request::getHeader('Authorization');
    Response::setHeader('Content-Type:Application/json');
    $conn=DataBase::getConn();

    $app=new App();

    $app->post('login',function() use($data,$conn){
        $logger=new Logger();
        $auth=new Authentication($logger,$conn);
        $auth->login($data);
    });

    $app->post('signup',function() use($data,$conn){
        $logger=new Logger();
        $auth=new Authentication($logger,$conn);
        $auth->signup($data);
    });    

    AuthMiddleware::checkToken($jwt);
    
    $logger=new Logger();
    $employee=new Employee($logger,$conn);

    $app->get('getuser',function() use($params,$employee){
        $employee->getEmployee($params);
    });

    $app->get('',function() use($params,$employee){
        $employee->getEmployees();
    });

    $app->post('add',function() use($data,$employee){
        $employee->addEmployee($data);
    });

    $app->patch('update',function() use($data,$employee,$params){
        $employee->updateEmployee($params,$data);
    });

    $app->delete('delete',function() use($employee,$params){
        $employee->removeEmployee($params);
    });

    $app->default(function(){
        Status::notFound();
        Response::sendMessage('route not found');
    });