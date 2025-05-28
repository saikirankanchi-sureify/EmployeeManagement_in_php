<?php
    function signup($conn,$data){
        try{
            $sql='insert into users values(?,?)';
            $stmt=$conn->prepare($sql);
            $stmt->execute([$data['user'],$data['password']]);
            http_response_code(201);
            echo json_encode(['msg'=>'added']);
        }
        catch(PDOException $err){
            http_response_code(409);
            echo json_encode(['msg'=>"user name already taken"]);
        }
    }
?>