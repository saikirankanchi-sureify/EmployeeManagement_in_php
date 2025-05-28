<?php
    include_once('checkUser.php');
    require 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    //header('Content-Type:Application/json');
    function login($conn,$data){
        $user=$data['user'];
        $password=$data['password'];
        if(!checkUser($conn,$user,$password)){
            http_response_code(401);
            echo json_encode(['msg'=>'incorrect credentials']);
            return;
        }
        $payload = [
            "exp" => time() + 3600, 
            "userId" => 123,
            "role" => "admin"
        ];
        $secretKey = $_ENV['SECRET_KEY'];
        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        echo json_encode(["token" => $jwt]);
        return;
}
?>