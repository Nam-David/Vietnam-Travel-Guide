<?php

class Config{
    public function connect(){
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "VIETNAMTRAVEL";

        $conn = mysqli_connect($hostname, $username, $password, $dbname) or die ("Lỗi kết nối Database")
        return $conn;
    }
}