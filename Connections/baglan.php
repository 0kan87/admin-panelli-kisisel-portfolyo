<?php
$hostname_baglan = "localhost";
$database_baglan = "yonetimpaneli";
$username_baglan = "root";
$password_baglan = "";
@$baglan = mysql_pconnect($hostname_baglan, $username_baglan, $password_baglan) or trigger_error(mysql_error(),E_USER_ERROR); 

mysql_query("SET CHARACTER SET 'utf8'");
mysql_query("SET NAMES 'utf8'");
?>