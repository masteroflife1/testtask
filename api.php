<?php
	$dblocation = '127.0.0.1';
	$dbuser = 'root'; 
	$dbpassw = ''; 
	$dbname = 'catalog';
	$reqError = false;
	$textError = '';
	//function sqlErr

	$mysqli = new mysqli($dblocation, $dbuser, $dbpassw, $dbname);
	if ($mysqli->connect_error) {
		$textError = 'Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
		$ans[] = array('result' => 1, 'reason' => $textError);
		die(json_encode($ans));
	}

	if (!empty($_POST['method'])){
		switch ($_POST['method']) {
			case 'getRub':
				$result = $mysqli->query('SELECT * FROM rub');
				break;
			case 'getAuth':
				$result = $mysqli->query('SELECT * FROM authors');
				break;
			case 'getNews':
				$result = $mysqli->query('SELECT id,auth_id,head,announce FROM news');
				break;
			case 'getText':
				if (!empty($_POST['param'])){
					$result = $mysqli->query('SELECT id,text FROM news WHERE id='.$mysqli->real_escape_string($_POST['param']));
				} else {
					$reqError = true;
					$textError = 'Empty parameter';
				}
				break;
			case 'getRubNews':
				$result = $mysqli->query('SELECT * FROM news_rub');
				break;
		
			default:
				$reqError = true;
				$textError = 'Unknown method';
				break;
		}
	}
	
	if ($reqError){
		$ans[] = array('result' => 1, 'reason' => $textError);
	} else {
		if ($mysqli->errno) {
			$textError = 'Select Error (' . $mysqli->errno . ') ' . $mysqli->error;
			$ans[] = array('result' => 1, 'reason' => $textError);
		} else {
			$ans[] = array('result' => 0);
			while($row = $result->fetch_assoc()){
				$ans[] = $row;
			}
			$result->free();
		}
	}

	//if (isset($result)) $result->free();
	echo json_encode($ans);
	$mysqli->close();
?>