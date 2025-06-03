<?php
    namespace services;
    //header('Content-Type:Application/json');
    class JSON 
    {
        public static function sendMessage($msg){
            echo json_encode(["msg"=>$msg]);
        }
        public static function send($date){
            echo json_encode(data);
        }
    }
?>