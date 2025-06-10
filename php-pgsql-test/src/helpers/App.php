<?php
    namespace helpers;

    class App
    {
        private $method=null;
        private $path=null;
        function __construct()
        {
            $this->method=Request::getMethod();
            $this->path=Request::getPath();
        }
        public final function get($path,$closure)
        {
            if($this->method!=='GET' || $this->path!==$path)
                return;
            $closure();
            exit();
        }
        public final function post($path,$closure)
        {
            if($this->method!=='POST'|| $this->path!==$path)
                return;
            $closure();
            exit();
        }
        public final function patch($path,$closure)
        {
            if($this->method!=='PATCH' || $this->path!==$path)
                return;
            $closure();
            exit();
        }
        public final function delete($path,$closure)
        {
            if($this->method!=='DELETE' || $this->path!==$path)
                return;
            $closure();
            exit();
        }
    }