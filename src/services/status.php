<?php
    namespace services;
    class Status
    {
        private static function status($status)
        {
            http_response_code($status);
        }
        public static function ok()
        {
            self::status(200);
        }
        public static function conflict()
        {
            self::status(409);
        }
        public static function unauthorized()
        {
            self::status(401);
        }
        public static function notFound()
        {
            self::status(404);
        }
        public static function internalServerError()
        {
            self::status(500);
        }
    }
?>