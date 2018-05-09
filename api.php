<?php
    $dblocation = '127.0.0.1';
    $dbuser = 'root'; 
    $dbpassw = ''; 
    $dbname = 'catalog';
    $reqError = false;
    $textError = '';

    $mysqli = new mysqli($dblocation, $dbuser, $dbpassw, $dbname);
    if ($mysqli->connect_error) {
        $textError = 'Connect Error ('. $mysqli->connect_errno . ') '
		    . $mysqli->connect_error;
        $ans[] = array('result' => 1, 'reason' => $textError);
        die(json_encode($ans));
    }

    if (!empty($_POST['method'])) {
        switch ($_POST['method']) {
            case 'getRub':
                $result = $mysqli->query('SELECT * FROM rub');
                break;
            case 'getAuth':
                $result = $mysqli->query('SELECT * FROM authors');
                break;
            case 'getNews':
                $result = $mysqli->query('SELECT id,auth_id,head,announce '
				    .'FROM news');
                break;
            case 'getText':
                if (!empty($_POST['param'])) {
                    $result = $mysqli->query('SELECT id,text FROM news WHERE id='
					    .$mysqli->real_escape_string($_POST['param']));
                } else {
                    $reqError = true;
                    $textError = 'Empty parameter';
                }
                break;
            case 'getRubNews':
                $result = $mysqli->query('SELECT * FROM news_rub');
                break;
            case 'getAuthorNews':
                if (!empty($_POST['param'])) {
                    $result = $mysqli->query('SELECT head,announce,text '
					    .'FROM news WHERE auth_id='
						.$mysqli->real_escape_string($_POST['param']));
                } else {
                    $reqError = true;
                    $textError = 'Empty parameter';
                }
                break;
            case 'getNewsByRub':
                if (!empty($_POST['param'])) {
                    $result = $mysqli->query('SELECT head,announce FROM `news` a '
					    .'WHERE a.id in (SELECT b.new_id FROM `news_rub` b '
						.'WHERE b.rub_id = '
						.$mysqli->real_escape_string($_POST['param']).')');
                } else {
                    $reqError = true;
                    $textError = 'Empty parameter';
                }
                break;
            case 'getAuthList':
                $result = $mysqli->query('SELECT fio FROM authors');
                break;
            case 'getNewsInfo':
                if (!empty($_POST['param'])) {
                    $result = $mysqli->query('SELECT head,announce,fio FROM news a'
					    .' INNER JOIN authors b ON a.auth_id=b.id WHERE a.id='
                        .$mysqli->real_escape_string($_POST['param']));
                    if ($mysqli->errno) break;
                    $result2 = $mysqli->query('SELECT name \'rubric\' '
					    .'FROM news_rub a INNER JOIN rub b ON a.rub_id = b.id '
						.'WHERE a.new_id='
						.$mysqli->real_escape_string($_POST['param']) );
                } else {
                    $reqError = true;
                    $textError = 'Empty parameter';
                }
                break;
            case 'getNewsByHead':
                if (!empty($_POST['param'])) {
                    $result = $mysqli->query('SELECT head,announce,text '
					.'FROM news WHERE head LIKE \'%'
					.$mysqli->real_escape_string($_POST['param']).'%\'');
                } else {
                    $reqError = true;
                    $textError = 'Empty parameter';
                }
                break;
            case 'getNewsByHeadRub':
                if (!empty($_POST['param']) && !empty($_POST['param2'])) {
                    $result = $mysqli->query('SELECT head,announce,text '
					    .'FROM news WHERE  head LIKE \'%'
						.$mysqli->real_escape_string($_POST['param'])
						.'%\'  AND id IN (SELECT new_id '
						.'FROM news_rub WHERE rub_id='
						.$mysqli->real_escape_string($_POST['param2']).')' );
                } else {
                    $reqError = true;
                    $textError = 'Empty parameter';
                }
                break;
            case 'getNewsByHeadRubIns':
                if (!empty($_POST['param']) && !empty($_POST['param2'])) {
                    $n = array($_POST['param2']);
                    $rubs = $n;
                    while (true) {
                        $s = implode(',',$n);
                        $result = $mysqli->query('SELECT id '
						    .'FROM rub WHERE mainid IN ('.$s.')');
                        if ($mysqli->errno) break;
                        if ($result->num_rows == 0) break;
                        $n = array();
                        while($row = $result->fetch_assoc()) {
						    $n[] = $row['id'];
						}
                        $rubs = array_merge($rubs, $n);
                        $result->free();
                    }
                    if ($mysqli->errno) break;
                    $result = $mysqli->query('SELECT head,announce,text '
					    .'FROM news WHERE  head LIKE \'%'
						.$mysqli->real_escape_string($_POST['param'])
						.'%\' AND id IN (SELECT new_id '
						.'FROM news_rub WHERE rub_id IN ('
						.implode(',',$rubs).') )');
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
    } else {
        $reqError = true;
        $textError = 'Method not set';
    }
    
    if ($reqError) {
        $ans[] = array('result' => 1, 'reason' => $textError);
    } else {
        if ($mysqli->errno) {
            $textError = 'Select Error ('.$mysqli->errno.') '.$mysqli->error;
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

    echo json_encode($ans);
    $mysqli->close();
