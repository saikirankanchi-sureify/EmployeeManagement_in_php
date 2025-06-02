<?php
    function isUserFound($conn,$name){
        try{
            $sql='select password from users where name=?';
            $stmt=$conn->prepare($sql);
            $stmt->execute([$name]);
            $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!isset($res[0])){
                return null;
            }
            return $res[0]['password'];
        }
        catch(PDOException $err){
            return null;
        }
    }
    
    function checkUser($conn,$user,$password){
        $userPassword=isUserFound($conn,$user);
        if(isset($userPassword) && $userPassword==$password)
            return true;
        return false;
    }
?>
