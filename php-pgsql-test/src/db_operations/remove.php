<?php
    function removeEmployee($conn,$id){
        try{
            if(!isEmployeeFound($conn,$id)){
                http_response_code(404);
                echo json_encode(["msg"=>"not found"]);
                return;
            }
            $sql='delete from Employees where "EmpId"=?';
            $stmt=$conn->prepare($sql);
            $stmt->execute([$id]);
            http_response_code(200);
            echo json_encode(["msg"=>"removed"]);
            return;
        }
        catch(PDO $err){
            http_response_code(500);
            echo json_encode(["err"=>"internal server error"]);
            return;
        }
    }
?>