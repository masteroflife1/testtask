<?php
	$dblocation = "127.0.0.1";
	$dbuser = "root"; 
	$dbpasswd = ""; 
	$mysqli = new mysqli($dblocation, $dbuser, $dbpasswd);
	if ($mysqli->connect_error) {
		die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	$mysqli->query('CREATE DATABASE IF NOT EXISTS catalog DEFAULT CHARACTER SET utf8');
	if ($mysqli->errno) {
		die('CreateDB Error (' . $mysqli->errno . ') ' . $mysqli->error);
	}
	if (!$mysqli->select_db('catalog')){
		die('Select_db Error');
	}
	$mysqli->query('CREATE TABLE IF NOT EXISTS rub( 
		            `id` INT(11) NOT NULL AUTO_INCREMENT, 
		            `mainid` INT(11), 
		            `name` VARCHAR(255), 
		             PRIMARY KEY(`id`))');
	if ($mysqli->errno) {
		die('Create Table "rub" Error (' . $mysqli->errno . ') ' . $mysqli->error);
	}
	$mysqli->query('CREATE TABLE IF NOT EXISTS news_rub(
		           `new_id` INT(11) NOT NULL, 
		           `rub_id` INT(11) NOT NULL)');
	if ($mysqli->errno) {
		die('Create Table "news_rub" Error (' . $mysqli->errno . ') ' . $mysqli->error);
	}
	$mysqli->query('CREATE TABLE IF NOT EXISTS authors (
		           `id` INT(11) NOT NULL AUTO_INCREMENT, 
		           `fio` VARCHAR(255), `sign` VARCHAR(255), 
		           `avatar` VARCHAR(255), 
		           PRIMARY KEY(`id`));');
	if ($mysqli->errno) {
		die('Create Table "authors" Error (' . $mysqli->errno . ') ' . $mysqli->error);
	}
	$mysqli->query('CREATE TABLE IF NOT EXISTS news (
		`id` INT(11) NOT NULL AUTO_INCREMENT, 
		`auth_id` INT(11) NOT NULL,
		`rub_id` INT(11) NOT NULL, 
		`head` VARCHAR(255), 
		`announce` VARCHAR(255), 
		`text` TEXT, 
		PRIMARY KEY(`id`))');
	if ($mysqli->errno) {
		die('Create Table "news" Error (' . $mysqli->errno . ') ' . $mysqli->error);
	}
	echo "Succes!";
?>