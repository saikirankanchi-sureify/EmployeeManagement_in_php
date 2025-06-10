<?php
    namespace helpers;

    class Request 
    {
        public static function getBody()
        {
            $req=file_get_contents('php://input');
            return json_decode($req,true);
        }
        public static function getMethod()
        {
            $method=$_SERVER['REQUEST_METHOD'];
            return $method;
        }
        public static function getPath()
        {
            $path=$_SERVER['REQUEST_URI'];
            $path_arr=explode('/',trim($path,'/'));
            if(!isset($path_arr[0]))
                return;
            return $path_arr[0];
        }
        public static function getParams()
        {
            $path=$_SERVER['REQUEST_URI'];
            $path_arr=explode('/',trim($path,'/'));
            if(!isset($path_arr[1]))
                return;
            return (int)$path_arr[1];
        }
        public static function getHeader($val)
        {
            $headers=getallheaders();
            if(isset($headers[$val]))
                return $headers[$val];
            return null;
        }
    }