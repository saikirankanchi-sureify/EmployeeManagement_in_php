<?php
    function select($conn){
        try{
            $sql= 'SELECT * from Employees' ;
            $stat=$conn->prepare($sql);
            $stat->execute();
            //to retrieve the associative array
            $res=$stat->fetchAll(PDO::FETCH_ASSOC);
           // print_r($res);
            echo json_encode($res);
        }
        catch(PDOException $err){
            echo "retrieve failed $err <br>";
        }
}
?>