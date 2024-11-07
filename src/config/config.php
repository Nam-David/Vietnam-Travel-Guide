<?php

class Config {
    private $conn;
    
    public function connect() {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "vietnamtravel";

        $this->conn = mysqli_connect($hostname, $username, $password, $dbname) or die ("Lỗi kết nối Database");
        return $this->conn;
    }


    public function getAffectedRows() {
        return mysqli_affected_rows($this->conn);
    }
}

