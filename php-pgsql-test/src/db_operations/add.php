<?php
    function addEmployee($conn,$data){
        try{
            $sql='insert into Employees ("EmpId","EmpName","EmpDesig","EmpDept","EmpSal") values(?,?,?,?,?)';
            $stmt=$conn->prepare($sql);
            $stmt->execute([$data['EmpId'],$data['EmpName'],$data['EmpDesig'],$data['EmpDept'],$data['EmpSal']]);
            $res=array("msg"=>"done");
            http_response_code(201);
            echo json_encode($res);
            return;
        }
        catch(PDOException $err){
            $err_res=array("err"=>"Error occured");
            http_response_code(409);
            echo json_encode($err);
            return;
        }
    }
?>