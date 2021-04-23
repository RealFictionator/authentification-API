<?php
class Token {
    private $id;
    private $time;
    /*Generate random string*/
    function __construct($data) {
        if($data == 'new') {
            $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $length = 20;
            $string = '';
            for ($i = 0; $i < $length; $i++) {
                $string .= $charset[rand(0, $length - 1)];
            }
            $this->id = $string;
            $this->time = time() + 3600;
        }
        else {
            $this->id = $data['id'];
            $this->time = $data['validity'];
        }   
    }
    /*Getter*/
    public function getId() {
        return $this->id;
    }
    public function getTime() {
        return $this->time;
    }
    /*Setter*/
    public function setId($id) {
        $this->id = $id;
    }
    public function setTime($time) {
        $this->time = $time;
    }
    /*Save token and uder in DB*/
    public function saveToken($user) {
        $databaseConnect = new DatabaseConnect();
        $pdo = $databaseConnect->getPdo();
        $smt = $pdo->prepare("INSERT INTO token (`id`,`validity`,`user`) VALUES (:id,:val,:user)");
        $smt->execute(array('id' => $this->id,'val' => $this->time,'user' => $user));
    }
    /*Get token and userName as JSON*/
    public function getJson($name) {
    	$arr = array('Response' => hash('sha256', $this->id),'Name' => $name);
    	return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }
    /*Get token start time*/
    public function getTokenStart() {
        $databaseConnect = new DatabaseConnect();
        $pdo = $databaseConnect->getPdo();
        $smt = $pdo->prepare("SELECT `logTime` FROM token WHERE `id` = :id");
        $smt->execute(array('id' => $this->id));
        $result = $smt->fetch(PDO::FETCH_ASSOC);
        return $result['logTime'];
    }
    /*Get token from DB*/
    public static function getTokenData($usr) {
        $databaseConnect = new DatabaseConnect();
        $pdo = $databaseConnect->getPdo();
        $smt = $pdo->prepare("SELECT `id`,`validity` FROM token WHERE `user` = :usr AND `validity` > :now");
        $smt->execute(array('usr' => $usr,'now' => time()));
        $result = $smt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result) > 0) {
            return $result[0];
        }
        else {
            return false;
        }
    }
    /*Reset token time in DB*/
    public function resetTime() {
    	$this->time = time() + 3600;
        $databaseConnect = new DatabaseConnect();
        $pdo = $databaseConnect->getPdo();
        $smt = $pdo->prepare("UPDATE token SET `validity` = :val WHERE `id` = :id");
        $smt->execute(array('id' => $this->id,'val' => $this->time));
    }
	    
}



