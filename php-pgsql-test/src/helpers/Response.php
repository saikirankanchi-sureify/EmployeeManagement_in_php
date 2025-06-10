<?php
    namespace helpers;
    class Response
    {
        public static function sendMessage($msg){
            echo json_encode(["msg"=>$msg]);
        }
        public static function send($data){
            echo json_encode($data);
        }
        public static function setHeader($val)
        {
            header($val);
        }
    }
?>