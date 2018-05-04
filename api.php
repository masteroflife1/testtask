<?php
	$dblocation = '127.0.0.1';
	$dbuser = 'root'; 
	$dbpassw = ''; 
	$dbname = 'catalog';
	$mysqli = new mysqli($dblocation, $dbuser, $dbpassw, $dbname);
	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	//echo "Succes";
	$result = $mysqli->query('SELECT * FROM authors');
	if ($mysqli->errno) {
		die('Select Error (' . $mysqli->errno . ') ' . $mysqli->error);
	}
	//echo json_encode($result->fetch_assoc() );
	//$r = $result->fetch_assoc();
	//echo $r['id'].' '.$r['fio'].' '.$r['avatar'];
	while($row = $result->fetch_assoc()){
		$ans[] = $row;
	}
	echo json_encode($ans);
	$mysqli->close();
?>