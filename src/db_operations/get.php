<?php
function isEmployeeFound($conn,$id){
    try{
        $sql='select * from Employees where "EmpId"=?';
        $stmt=$conn->prepare($sql);
        $stmt->execute([$id]);
        $res=$stmt->fetch(PDO::FETCH_ASSOC);
        if(!$res){
            return false;
        }
        return true;
    }
    catch(PDO $err){
        return false;
    }
}
function getEmployee($conn,$id){
    try{
        if(!isEmployeeFound($conn,$id)){
            $res=array(
                "msg"=>"not found"
            );
            http_response_code(404);
            echo json_encode($res);
            return;
        }
        $sql='select * from Employees Where "EmpId"=?';
        $stmt=$conn->prepare($sql);
        $stmt->execute([$id]);
        $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode($res);
        return;
    }
    catch(PDO $err){
        http_response_code(500);
        $res=array(
            "err"=>"internal server error"
        );
        echo json_encode($res);
        return;
    }
}

function getEmployees($conn){
    try{
        $sql='select * from Employees order by "EmpId" asc';
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!isset($res[0])){
            http_response_code(404);
            $res=array(
                "msg"=>"add some employees"
            );
            echo json_encode($res);
            return;
        }
        http_response_code(200);
        echo json_encode($res);    
        return;
    }
    catch(PDO $err){
        http_response_code(500);
        $res=array(
            "err"=>"internal server error"
        );
        echo json_encode($res);
        return;
    }
}
?>