<?php
    function retrieve($conn,$id){
        try{
            $sql='select name from mytab where sid=?';
            $stmt=$conn->prepare($sql);
            $stmt->execute([$id]);
            $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
            print_r($res);
        }
        catch(PDOExcpetion $err){
            echo 'retreive failed'."$err <br>";
        }
    }
?>