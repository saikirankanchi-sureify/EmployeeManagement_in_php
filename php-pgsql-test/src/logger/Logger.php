<?php
    namespace logger;

    use Psr\Log\LoggerInterface;

    class Logger implements LoggerInterface
    {
        private $filePath=__DIR__.'/logs.txt';

        public function emergency(\Stringable|string $message, array $context = array()):void
        {
            $this->log('EMERGENCY : ',$message);
        }

        public function alert(\Stringable|string $message, array $context = array()):void
        {
            $this->log('ALERT : ',$message);
        }

        public function critical(\Stringable|string $message, array $context = array()):void
        {
            $this->log('CRITICAL : ',$message);
        }

        public function error(\Stringable|string $message, array $context = array()):void
        {
            $this->log('ERROR : ',$message);
        }

        public function warning(\Stringable|string $message, array $context = array()):void
        {
            $this->log('WARNING : ',$message);
        }

        public function notice(\Stringable|string $message, array $context = array()):void
        {
            $this->log('NOTICE : ',$message);
        }

        public function info(\Stringable|string $message, array $context = array()):void
        {
            $this->log('INFO : ',$message);
        }

        public function debug(\Stringable|string $message, array $context = array()):void
        {
            $this->log('DEBUG : ',$message);
        }

        public function log($level,\Stringable|string $message, array $context = array()):void
        {
            date_default_timezone_set('Asia/Kolkata');
            $time=date('Y-m-d H:i:s');
            file_put_contents($this->filePath,"$level $message at $time\n",FILE_APPEND);
        }

    }