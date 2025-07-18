<?php
    namespace middleware;
    
    require 'vendor/autoload.php';
    
    use helpers\Status;
    use helpers\Response;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Firebase\JWT\ExpiredException;
    use Firebase\JWT\SignatureInvalidException;

    class AuthMiddleware
    {
        private static function extractToken($raw_token)
        {
            $token_arr=explode(' ',trim($raw_token));
            if(sizeof($token_arr)<2)
                throw new Error('invalid token');
            return $token_arr[1];
        }

        public static function checkToken($raw_token)
        {
            if(!isset($raw_token)){
                Status::unauthorized();
                Response::sendMessage('unauthorized access');
                exit();
            }
            try{
                $token=self::extractToken($raw_token);
                $secret_key=$_ENV['SECRET_KEY'];
                JWT::decode($token,new Key($secret_key,'HS256'));
                return true;
            }
            catch(ExpiredException $e)
            {
                Status::unauthorized();
                Response::sendMessage('unauthorized access');
                exit();
            }
            catch(SignatureInvalidException $e)
            {
                Status::unauthorized();
                Response::sendMessage('unauthorized access');
                exit();
            }
        }
    }