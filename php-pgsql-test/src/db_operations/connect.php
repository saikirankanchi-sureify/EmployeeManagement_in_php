<?php
    header('Content-Type:application/json');
    function getConn(){
        try {
            $host=$_ENV['HOST'];
            $dbname=$_ENV['DB'];
            $user=$_ENV['USER'];
            $password=$_ENV['PASSWORD'];
            $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
            return $conn;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage()."<br>";
        }
    }
?>
