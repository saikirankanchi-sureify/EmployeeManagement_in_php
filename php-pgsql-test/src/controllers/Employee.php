<?php
    namespace controllers;

    require 'vendor/autoload.php';
    
    use helpers\Status;
    use helpers\Response;
    use controllers\Authentication;
    use controllers\Employee;
    use middleware\AuthMiddleware;
    use models\EmployeeModel;
    
    class Employee
    {
        private $logger;
        private $model;

        function __construct($logger,$conn)
        {
            $this->logger=$logger;
            $this->model=new EmployeeModel($conn);
        }
        public function addEmployee($data)
        {
            if($this->model->isEmployeeFound($data['EmpId']))
                {
                    Status::conflict();
                    Response::sendMessage('id already exist');
                }
            if($this->model->isEmployeeAdded($data)){
                $this->logger->info("Employee {$data['EmpName']} added");
                Status::newResource();
                Response::sendMessage('Employee Added');
            }
            $this->logger->error("error occured at database");
            Status::internalServerError();
            Response::sendMessage('internal server error');
        }
        public function getEmployee($id)
        {
            try{
                $employee=$this->model->getEmployeeData($id);
                if(empty($employee)){
                    $this->logger->alert("Employee with $id fetched failed");
                    Status::notFound();
                    Response::sendMessage('not found');
                }
                $this->logger->info("Employee {$employee[0]['EmpName']} fetched");
                Status::ok();
                Response::send($employee);
            }
            catch(\Exception){
                $this->logger->error("database error");
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }
        public function getEmployees()
        {
            try{
                $employees=$this->model->getEmployeesData();
                if(empty($employees)){
                    Status::notFound();
                    Response::sendMessage('add some employees');
                }  
                $this->logger->info("Employees fetched");
                Status::ok();
                Response::send($employees);
            }
            catch(\Exception){
                $this->logger->error("error occured at database");
                Status::internalServerError();
                Response::sendMessage('internal server error');
            }
        }

        public function updateEmployee($id,$data)
        {
            if(!$this->model->isEmployeeActive($id)){
                Status::notFound();
                Response::sendMessage('not found');
            }
            if($this->model->isEmployeeUpdated($id,$data)){
                $this->logger->alert("Employee {$data['EmpName']} updated");
                Status::ok();
                Response::sendMessage('updated');
            }
            $this->logger->error("error occured at database");
            Status::internalServerError();
            Response::sendMessage('internal server error');
        }
        public function removeEmployee($id)
        {
            if(!$this->model->isEmployeeActive($id)){
                $this->logger->alert("Employee with $id fetch failed");
                Status::notFound();
                Response::sendMessage('not found');
            }
            if($this->model->isEmployeeRemoved($id)){
                $this->logger->warning("Employee with $id deleted");
                Status::ok();
                Response::sendMessage('deleted');
            }
            $this->logger->error("error occured at database");
            Status::internalServerError();
            Response::sendMessage('internal server error');
        }
    }