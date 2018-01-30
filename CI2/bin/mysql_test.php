<?php

$db = mysql_connect('localhost','root','no_password') or die("Database error"); 
mysql_select_db('ammportal', $db); 

//SOLUTION:: add this comment before your 1st query -- force multiLanuage support 
$result = mysql_query("update ci_sessions set ip_address = '127.0.0.4' where session_id = '5bfec41ef26e0ad550cabf5bab278ed7'"); 
