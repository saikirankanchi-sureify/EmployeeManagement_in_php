<?php
    namespace helpers;
    class Response
    {
        public static function sendMessage($msg){
            echo json_encode(["msg"=>$msg]);
            exit;
        }

        public static function send($data){
            echo json_encode($data);
            exit;
        }
        
        public static function setHeader($val)
        {
            header($val);
        }
    }