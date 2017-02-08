<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# mailto.php                                                    #
# This is the mailto file, which will hopefully help stem the   #
# tide of spambots picking up our newsposter's email addresses. #
#################################################################

require_once('cagcms/sources/qwerty/config.php');
require_once($sourcepath.'/loginfunctions.php');

# We need to set our $link variable and select our database to make it easier to connect to the database.

$link = mysql_connect(HOST,DBUSER,DBPASS);
mysql_select_db(DB, $link);

$id = $_GET{'id'};

$sql = mysql_query("SELECT `newsemail`,`username` FROM `news` WHERE `newstime` = '$id'", $link);
while($rs = mysql_fetch_array($sql)) {
	if(!empty($rs[0])) {
		$email = $rs[0];
	}
	elseif($yabbok) {
		$tempuser = getyabbuservars($memberdir."/".$rs[1].".vars");
		$email = $tempuser['email'];
	}
	else {
		$sql_user = mysql_query("SELECT `useremail` FROM `users` WHERE `username` = '".$rs[1]."'",$link);
		while($rt = mysql_fetch_array($sql_user)) {
			$email = $rt[0];
		}
	}
}

header("Location: mailto:$email");

?>

<body onLoad="self.close();">
</body>