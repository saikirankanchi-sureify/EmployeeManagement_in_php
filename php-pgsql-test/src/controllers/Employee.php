<?php
    namespace controllers;

    require 'vendor/autoload.php';
    use helpers\Status;
    use helpers\Response;
    use controllers\Authentication;
    use controllers\Employee;
    use middleware\AuthMiddleware;
    
    class Employee
    {
        private $conn;
        function __construct($conn)
        {
            $this->conn=$conn;
        }
        private function isEmployeeFound($id)
        {
            try{
                $sql='select "EmpName" from Employees where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id]);
                $res=$stmt->fetch(\PDO::FETCH_ASSOC);
                if(!$res){
                    return false;
                }
                return true;
            }
            catch(\PDOException $err){
                return false;
            }
        }
        public function addEmployee($data)
        {
            try{
                $sql='insert into Employees ("EmpId","EmpName","EmpDesig","EmpDept","EmpSal") values(?,?,?,?,?)';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$data['EmpId'],$data['EmpName'],$data['EmpDesig'],$data['EmpDept'], $data['EmpSal']]);
                Status::newResource();
                Response::sendMessage('Employee Added');
            }
            catch(\PDOException $err){
                Status::conflict();
                Response::sendMessage('id already exist');
            }
        }
        public function getEmployee($id)
        {
            try{
                $sql='select * from Employees Where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id]);
                $res=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                if(!isset($res[0])){
                    Status::notFound();
                    Response::sendMessage('not found');
                    return;
                }
                Status::ok();
                Response::send($res);
            }
            catch(\PDOException $err){
                Status::ok();
                Response::sendMessage('internal server error');
            }
        }
        public function getEmployees()
        {
            try{
                $sql='select * from Employees order by "EmpId" asc';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute();
                $res=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                if(!isset($res[0])){
                    Status::notFound();
                    Response::sendMessage('add some employees');
                    return;
                }  
                Status::ok();
                Response::send($res);
            }
            catch(\PDOException $err){
                Status::ok();
                Response::sendMessage('internal server error');
            }
        }

        public function updateEmployee($id,$data)
        {
            try{
                if(!$this->isEmployeeFound($id)){
                    Status::notFound();
                    Response::sendMessage('not found');
                    return;
                }
                $sql='update Employees 
                        set "EmpName"=?,"EmpDesig"=?,"EmpDept"=?,"EmpSal"=?
                        where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $res=$stmt->execute([$data['EmpName'],$data['EmpDesig'],$data['EmpDept'],$data['EmpSal'],$id]);
                Status::ok();
                Response::sendMessage('updated');
            }
            catch(\PDOException $err){
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }
        public function removeEmployee($id)
        {
            try{
                if(!$this->isEmployeeFound($id)){
                    Status::notFound();
                    Response::sendMessage('not found');
                    return;
                }
                $sql='delete from Employees where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id]);
                Status::ok();
                Response::sendMessage('deleted');
            }
            catch(\PDOException $err){
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }
    }
?>