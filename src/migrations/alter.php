<?php
    function alterTable($conn){
        $sql='Create table Employees ("EmpId" INT primary key,"EmpName" varchar(20) Not Null,"EmpDesig" varchar(20) Not Null 
                ,"EmpDept" varchar(20) Not Null,"EmpSal" INT Not Null)';
        //("EmpName","EmpDesig","EmpDept","EmpSal")
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo json_encode($res);
    }
?>