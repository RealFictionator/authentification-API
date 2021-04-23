<?php
include('class/user.php');
include('class/token.php');
include('database/pdoConnect.php');
session_start();

/*User sent data via JSON*/

$json = file_get_contents('php://input');
$data = json_decode($json);
/*User sent authentification data via JSON*/
if(isset($data->id) && isset($data->pw) && !isset($data->token)) {
	$resp = User::authenticate($data->id,$data->pw);
	if($resp != false) {
		$user = new User($resp['user_id'],$resp['user_fname'],$resp['user_lname']);
		$token = new Token('new');
    	$token->saveToken($user->getId());
    	header('Content-Type: application/json;charset=utf-8');
    	echo $token->getJson($user->getFullName());
	}
	/*Sent User data wrong*/
	else {
		$error = array('Response' => "false", 'Description' => "Die Anmeldedaten sind nicht korrekt");
		header('Content-Type: application/json;charset=utf-8');
    	echo json_encode($error);
	}
}
/*User sent token and id via JSON*/
elseif(isset($data->user) && isset($data->token)) {
	$tData = Token::getTokenData($data->user);
	$token = new Token($tData);
	if($data->token == hash('sha256', $token->getId())) {
		if($token->getTime() > time()) {
			$token->resetTime();
			$uData = User::getUserData($data->user);
			$user = new User($uData['user_id'],$uData['user_fname'],$uData['user_lname']);
			header('Content-Type: application/json;charset=utf-8');
	    	echo json_encode(array('Response' => "true",'Name' => $user->getFullName()), JSON_UNESCAPED_UNICODE);
		}
	}
	/*Sent Token wrong*/
	else {
		$error = array('Response' => "false", 'Description' => "Die Sitzung ist nicht verfügbar");
		header('Content-Type: application/json;charset=utf-8');
	    echo json_encode($error);
	}
}
/*Sent Data wrong*/
else {
	echo "Sie haben keinen Zutritt";
}


?>




