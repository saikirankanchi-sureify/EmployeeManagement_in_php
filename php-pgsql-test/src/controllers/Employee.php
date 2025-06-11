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
        private $logger;
        function __construct($conn,$logger)
        {
            $this->conn=$conn;
            $this->logger=$logger;
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
            if($this->isEmployeeFound($data['EmpId']))
                {
                    Status::conflict();
                    Response::sendMessage('id already exist');
                    return;
                }
            try{
                $sql='insert into Employees (
                "EmpId","EmpName","EmpDesig","EmpDept","EmpSal"
                ) values(?,?,?,?,?)';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([
                    $data['EmpId']
                    ,$data['EmpName']
                    ,$data['EmpDesig']
                    ,$data['EmpDept']
                    , $data['EmpSal']
                ]);
                $this->logger->info("Employee {$data['EmpName']} added");
                Status::newResource();
                Response::sendMessage('Employee Added');
            }
            catch(\PDOException $err){
                $this->logger->error("error occured at database");
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }
        public function getEmployee($id)
        {
            try{
                $sql='select * from Employees Where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id]);
                $res=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                if(empty($res)){
                    $this->logger->alert("Employee with $id fetched failed");
                    Status::notFound();
                    Response::sendMessage('not found');
                    return;
                }
                $this->logger->info("Employee {$res['EmpName']} fetched");
                Status::ok();
                Response::send($res);
            }
            catch(\PDOException $err){
                Status::internalServerError();
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
                if(empty($res)){
                    Status::notFound();
                    Response::sendMessage('add some employees');
                    return;
                }  
                $this->logger->info("Employees fetched");
                Status::ok();
                Response::send($res);
            }
            catch(\PDOException $err){
                $this->logger->error("error occured at database");
                Status::internalServerError();
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
                $res=$stmt->execute([
                    $data['EmpName'],
                    $data['EmpDesig'],
                    $data['EmpDept'],
                    $data['EmpSal'],
                    $id
                ]);
                $this->logger->alert("Employee {$data['EmpName']} updated");
                Status::ok();
                Response::sendMessage('updated');
            }
            catch(\PDOException $err){
                $this->logger->error("error occured at database");
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }
        public function removeEmployee($id)
        {
            try{
                if(!$this->isEmployeeFound($id)){
                    $this->logger->alert("Employee with $id fetch failed");
                    Status::notFound();
                    Response::sendMessage('not found');
                    return;
                }
                $sql='delete from Employees where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id]);
                $this->logger->warning("Employee with $id deleted");
                Status::ok();
                Response::sendMessage('deleted');
            }
            catch(\PDOException $err){
                $this->logger->error("error occured at database");
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }
    }