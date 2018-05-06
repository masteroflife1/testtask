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
			case 'getAuthorNews':
				if (!empty($_POST['param'])){
					$result = $mysqli->query('SELECT head,announce,text FROM news WHERE auth_id='.$mysqli->real_escape_string($_POST['param']));
				} else {
					$reqError = true;
					$textError = 'Empty parameter';
				}
				break;
			case 'getNewsByRub':
				if (!empty($_POST['param'])){
					$result = $mysqli->query('SELECT head,announce FROM `news` a WHERE a.id in (SELECT b.new_id FROM `news_rub` b WHERE b.rub_id = '.$mysqli->real_escape_string($_POST['param']).')');
				} else {
					$reqError = true;
					$textError = 'Empty parameter';
				}
				break;
			case 'getAuthList':
				$result = $mysqli->query('SELECT fio FROM authors');
				break;
			case 'getNewsInfo':
				if (!empty($_POST['param'])){
					$result = $mysqli->query('SELECT head,announce FROM news WHERE id='.$mysqli->real_escape_string($_POST['param']));
					if ($mysqli->errno) break;
					$result2 = $mysqli->query('SELECT name \'rubric\' FROM news_rub a INNER JOIN rub b ON a.rub_id = b.id WHERE a.new_id='.$mysqli->real_escape_string($_POST['param']) );
				} else {
					$reqError = true;
					$textError = 'Empty parameter';
				}
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
			if (isset($result2)){
				while($row = $result2->fetch_assoc()){
					$ans[] = $row;
				}
				$result2->free();
			}
		}
	}

	//if (isset($result)) $result->free();
	echo json_encode($ans);
	$mysqli->close();
?>