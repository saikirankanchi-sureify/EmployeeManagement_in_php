<?php
    function updateEmployee($conn,$id,$data){
        try{
            if(!isEmployeeFound($conn,$id)){
                $res=array(
                    "msg"=>"not found"
                );
                http_response_code(404);
                echo json_encode($res);
                return;
            }
            $sql='update Employees 
                    set "EmpName"=?,"EmpDesig"=?,"EmpDept"=?,"EmpSal"=?
                    where "EmpId"=?
            ';
            $stmt=$conn->prepare($sql);
            $res=$stmt->execute([$data['EmpName'],$data['EmpDesig'],$data['EmpDept'],$data['EmpSal'],$id]);
            $res=array(
                "msg"=>"updated"
            );
            echo json_encode($res);
        }
        catch(PDO $err){
            http_response_code(500);
            $res=array(
                "err"=>"internal server error"
            );
            echo json_encode($res);
        }
    }
?>