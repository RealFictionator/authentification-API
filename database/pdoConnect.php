<?php
class DatabaseConnect {
    var $pdo;
    public function getPDO() {
        return $this->pdo;
    }
    public function  __construct() {
        
        if (isset($this->pdo)) {
            return $this->pdo;
        }

        include('conf.php');
        try{
            $this->pdo = new PDO('mysql:host=localhost;dbname=api-test;charset=utf8', $dbData['username'],$dbData['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e){
            echo  'connection failed: ' .$e->getMessage();
        }
    }
}


