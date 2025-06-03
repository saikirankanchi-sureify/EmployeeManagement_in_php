<?php
    require 'vendor/autoload.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    function checkToken($token){
        try{
            $token=trim($token,' ');
            $extracted_token=explode(' ',$token);
            $secretKey = $_ENV['SECRET_KEY'];
            if(sizeof($extracted_token)<2){
                http_response_code(401);
                return false;
            }
            JWT::decode($extracted_token[1],new Key($secretKey,'HS256'));
            return true;
        }    
        catch(Exception $err){
            http_response_code(401);
            return false;
        }
    }
?>