<?php
class User {
    private $id;
    private $fname;
    private $lname;
	function __construct($id,$fname,$lname) {
        $this->id = $id;
        $this->fname = $fname;
        $this->lname = $lname;
    }
    /*Getter*/
    public function getId() {
        return $this->id;
    } 
    public function getFname() {
        return $this->fname;
    } 
    public function getLname() {
        return $this->lname;
    }
    /*Setter*/ 
    public function setId($id) {
        $this->id = $id;
    } 
    public function setFname($fname) {
        $this->fname = $fname;
    } 
    public function setLname($lname) {
        $this->lname = $lname;
    } 
    public function getFullName() {
        $fullName = ucfirst($this->fname).' '.ucfirst($this->lname);
        return $fullName;
    }
    /*Get user data from DB by id*/
    public static function getUserData($usr) {
    	$databaseConnect = new DatabaseConnect();
	    $pdo = $databaseConnect->getPdo();
    	$smt = $pdo->prepare("SELECT `user_id`,`user_fname`,`user_lname` FROM user WHERE `user_id` = :usr");
		$smt->execute(array('usr' => $usr));
		$result = $smt->fetch(PDO::FETCH_ASSOC);
    	return $result;
    }
    /*Get user data from DB by id and password*/
    public static function authenticate($usr,$pw) {
    	$pw_hash = hash('sha256', $pw);
	    $databaseConnect = new DatabaseConnect();
	    $pdo = $databaseConnect->getPdo();
        $smt = $pdo->prepare("SELECT `user_id`,`user_fname`,`user_lname` FROM user WHERE `user_id` = :usr AND `user_passwd` = :pw");
		$smt->execute(array('usr' => $usr,'pw' => $pw_hash));
		$user = $smt->fetchAll(PDO::FETCH_ASSOC);
		if(count($user) > 0) {
            $smt = $pdo->prepare("SELECT COUNT(`id`) AS row_count FROM token WHERE `user` = :usr AND `validity` > :now");
            $smt->execute(array('usr' => $usr,'now' => time()));
            $result = $smt->fetch(PDO::FETCH_ASSOC);
            if($result['row_count'] == 0) {
                return $user[0];
            } 
            else {
                return false;
		  }
        }
        else {
        	return false;
        } 
	}
}
?>

