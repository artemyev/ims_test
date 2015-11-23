<?php
/**
 * config.php
 * Конфигурация
 *
 * @author Evgeniy Artemyev
 * @version 0.0.1
 */

if(!defined('IMS')){ exit(); }

ini_set('display_errors', 0);
error_reporting(E_ALL ^ E_NOTICE);
if(version_compare(PHP_VERSION, '5.3.0', '>=')){
  error_reporting(error_reporting() & ~E_DEPRECATED);
}

$db_host        = 'localhost';
$db_username    = 'webekfep_1';
$db_password    = 'webekfep_2';
$db_name        = 'webekfep_1';

$currency		= ' ₽';
$shipping		= 300;

$mysqli_conn = new mysqli($db_host, $db_username, $db_password,$db_name);
if($mysqli_conn->connect_error){
  die('Error: ('. $mysqli_conn->connect_errno .') '. $mysqli_conn->connect_error);
}
$mysqli_conn->set_charset ('utf-8');
$mysqli_conn->query('SET NAMES utf8'); // @todo fix

