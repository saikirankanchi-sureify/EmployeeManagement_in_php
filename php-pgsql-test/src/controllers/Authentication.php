<?php
    namespace controllers;
    
    require 'vendor/autoload.php';
    use helpers\Status;
    use helpers\Response;
    use \PDO;
    use Firebase\JWT\JWT;
    // use JWT\

    class Authentication{
        private $conn;
        function __construct($conn)
        {
            $this->conn=$conn;
        }
        private function isUserFound($name)
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
        private function checkPassword($user,$password)
        {
            $userPassword=$this->isUserFound($user);
            if(isset($userPassword) && $userPassword==$password)
                return true;
            return false;
        }
        public final function signup($data)
        {
            try{
                $sql='insert into users values(?,?)';
                $stmt=$this->conn->prepare($sql);
                $stmt->execute([$data['user'],$data['password']]);
                Status::newResource();
                Response::sendMessage('added');
            }
            catch(\PDOException $err){
                Status::conflict();
                Response::sendMessage('user name already taken');
            }
        }
        public final function login($data) 
        {
            $user=$data['user'];
            $password=$data['password'];

            if(!$this->checkPassword($user,$password)){  
                Status::unauthorized();
                Response::sendMessage('incorrect credentials');
                return;
            }
            $payload = [
                "exp" => time() + 3600, 
                "name" => $user,
                "password" => $password
            ];
            $secretKey = $_ENV['SECRET_KEY'];
            $jwt = JWT::encode($payload, $secretKey, 'HS256');
            Status::ok();
            Response::send(["token" => $jwt]);
            return;
        }
    }
?>