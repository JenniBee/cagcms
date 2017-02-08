<?php
require_once('cagcms/sources/qwerty/config.php');
$link = mysql_connect(HOST,DBUSER,DBPASS);
mysql_select_db(DB, $link);

$sql = "UPDATE extramenus SET clicks=clicks+1 WHERE itemid=".$_GET['itemid'];
if(mysql_query($sql,$link)) {
	$sql = mysql_query("SELECT location FROM extramenus WHERE itemid=".$_GET['itemid'],$link);
	$rs = mysql_fetch_array($sql);
	header("Location: ".$rs['location']);
}
else {
	die("There was an error in your SQL.");
}
?>