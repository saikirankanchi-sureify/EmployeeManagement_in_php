<?php
    namespace controllers;
    
    require 'vendor/autoload.php';
    use helpers\Status;
    use helpers\Response;
    use models\AuthModel;
    use \PDO;
    use Firebase\JWT\JWT;

    class Authentication{

        private $logger;
        private $model;

        function __construct($logger,$conn)
        {
            $this->logger=$logger;
            $this->model=new AuthModel($conn);
        }

        private function checkPassword($user,$password)
        {
            $hashedPassword=$this->model->getUserPassword($user);
            if(isset($hashedPassword) && password_verify($password,$hashedPassword))
                return true;
            return false;
        }

        public final function signup($data)
        {
            if($this->model->isUserAdded($data)){
                $this->logger->info("{$data['user']} user added");
                Status::newResource();
                Response::sendMessage('added');
            }
            $this->logger->warning('username conflict at signup');
            Status::conflict();
            Response::sendMessage('user name already taken');
        }

        public final function login($data) 
        {
            $user=$data['user'];
            $password=$data['password'];

            if(!$this->checkPassword($user,$password)){  
                $this->logger->alert("$user entered incorrect credentials");
                Status::unauthorized();
                Response::sendMessage('incorrect credentials');
                return;
            }
            $payload = [
                "exp" => time() + 3600, 
                "name" => $user
            ];
            $secretKey = $_ENV['SECRET_KEY'];
            $jwt = JWT::encode($payload, $secretKey, 'HS256');
            $this->logger->info('user logged');
            Status::ok();
            Response::send(["token" => $jwt]);
            return;
        }
        
    }