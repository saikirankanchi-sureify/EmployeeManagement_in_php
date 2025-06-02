<?php
    function prepareDemo($conn){
        try{
            $sql='insert into mytab (sid,name) values(?,?)';
            $stmt=$conn->prepare($sql);
            $stmt->execute([23,'xyz']);
            echo 'successfully inserted';
        }
        catch(PDOException $err){
            echo "insertion failed $err";
        }
    }
?>