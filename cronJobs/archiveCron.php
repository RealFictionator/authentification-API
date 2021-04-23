<?php
include('../database/pdoConnect.php');
$result = array();
$databaseConnect = new DatabaseConnect();
$pdo = $databaseConnect->getPdo();
$smt = $pdo->prepare("SELECT * FROM token WHERE `validity` < :now");
$smt->execute(array('now' => time()));
$result = $smt->fetchAll(\PDO::FETCH_ASSOC);
if(count($result) > 0) {
	$save = $pdo->prepare("INSERT INTO archive (`worked`,`user`) VALUES (:worked,:user)");
	$delete = $pdo->prepare("DELETE FROM token WHERE `id` = :id AND `user` = :user");
	foreach ($result as $key => $val) {
		$save->execute(array('worked' => $val['logTime'],'user' => $val['user']));
		$delete->execute(array('id' => $val['id'],'user' => $val['user']));
	}
	echo count($result).' data';
}
else {
	echo 'no data';
}




