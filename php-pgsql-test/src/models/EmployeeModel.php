<?php
    namespace models;

    class EmployeeModel
    {
        private $conn;
        function __construct($conn)
        {
            $this->conn=$conn;
        }
        public function isEmployeeActive($id)
        {
            try{
                $sql='select "EmpName" from Employees where "EmpId"=? and "IsActive"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id,'t']);
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
        public function isEmployeeFound($id)
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
        public function isEmployeeAdded($data)
        {
            try{
                $sql='insert into Employees (
                "EmpId","EmpName","EmpDesig","EmpDept","EmpSal","IsActive"
                ) values(?,?,?,?,?,?)';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([
                    $data['EmpId']
                    ,$data['EmpName']
                    ,$data['EmpDesig']
                    ,$data['EmpDept']
                    , $data['EmpSal'],
                    't'
                ]);
                return true;
            }
            catch(\PDOException $err){
                return false;
            }
        }
        public function getEmployeeData($id)
        {
            try{
                $sql='select "EmpId","EmpName","EmpDesig","EmpDept","EmpSal"
                      from Employees Where "EmpId"=? AND "IsActive"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$id,'t']);
                $res=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $res;
            }
            catch(\PDOException $err){
                throw new \Exception('internal error');
            }
        }
        public function getEmployeesData()
        {
            try{
                $sql='select "EmpId","EmpName","EmpDesig","EmpDept","EmpSal"
                      from Employees where "IsActive"=? order by "EmpId" ASC';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute(['t']);
                $res=$stmt->fetchAll(\PDO::FETCH_ASSOC);
                return $res;
            }
            catch(\PDOException $err){
                throw new \Exception('internal error');
            }
        }
        public function isEmployeeUpdated($id,$data)
        {
            try{
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
                return true;
            }
            catch(\PDOException $err){
                return false;
            }
        }
        public function isEmployeeRemoved($id)
        {
            try{
                $sql='update Employees set "IsActive"=? where "EmpId"=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute(['f',$id]);
                return true;
            }
            catch(\PDOException $err){
                echo $err->getMessage();
                return false;
            }
        }
    }