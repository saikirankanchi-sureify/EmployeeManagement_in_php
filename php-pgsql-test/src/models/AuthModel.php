<?php
    namespace models;

    use \PDO;
    
    class AuthModel
    {
        private $conn;
        function __construct($conn)
        {
            $this->conn=$conn;
        }
        public function getUserPassword($name)
        {
            try{
                $sql='select password from users where name=?';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$name]);
                $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!isset($res[0])){
                    return null;
                }
                return $res[0]['password'];
            }
            catch(\PDOException $err){
                return null;
            }
        }
        public function isUserAdded($data)
        {
            $password=$data['password'];
            try{
                $hashed_password=password_hash($password,PASSWORD_DEFAULT);
                $sql='insert into users values(?,?)';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$data['user'],$hashed_password]);
                return true;
            }
            catch(\PDOException $err){
                return false;
            }
            catch(\Exception $err){
                return false;
            }
        }

    }