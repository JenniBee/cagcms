<?php

$curtime = date("M d, Y \a\\t H:i:s \G\M\\T O", time());

if($_POST{'filetype'} == "php") {
	$cgbackup = "<?php

# This back-up file was generated:
# $curtime

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# backupdb.php                                                  #
# This is a back-up you created of your database.  You should   #
# be able to use this file to re-populate your database in the  #
# case that your database somehow gets corrupted.               #
# You will need to restore your database structure using the    #
# installation file first.  Then, you can use this file to      #
# restore the data within that database.                        #
#################################################################

require_once('../sources/qwerty/config.php');
";
}
elseif($_POST{'filetype'} == "sql") {
	$cgbackup = "
-- This back-up file was generated:
-- $curtime

-- CAGCMS - CAG's Content Management System
-- Version 0.3 beta
-- Released: September 2, 2006
-- This is a back-up file for restoring your CAGCMS database
";
}

# We will now back-up each table in our database #

foreach($_POST[dbtables] as $tablename) {
	if($_POST[backuptype] == "both" || $_POST[backuptype] == "structure") {
		if($_POST[filetype] == "php") {
			$cgbackup .= "
# Table structure for `$tablename` #

";
		}
		elseif($_POST[filetype] == "sql") {
			$cgbackup .= "
-- Table structure for `$tablename`

";
		}
		$sql_fields = mysql_query("SHOW CREATE TABLE `$tablename`");
		while($result = mysql_fetch_array($sql_fields)) {
			$sql_query = $result{'Create Table'}.";";
			if($_POST[exportversion] == 4) {
				$sql_query = preg_replace('/ENGINE/','TYPE',$sql_query);
				$sql_query = preg_replace('/ DEFAULT CHARSET=latin1/','',$sql_query);
			}
		}
		if($_POST{'filetype'} == "php") {
			$cgbackup .= "
mysql_query(\"$sql_query\", \$link);
";
		}
		elseif($_POST{'filetype'} == "sql") {
			$cgbackup .= $sql_query."
";
		}
	}
	if($_POST[backuptype] == "both" || $_POST[backuptype] == "data") {
		if($_POST[filetype] == "php") {
			$cgbackup .= "

# Dumping data for `$tablename` #";
		}
		elseif($_POST[filetype] == "sql") {
			$cgbackup .= "

-- Dumping data for `$tablename`";
		}
		$sql_backup = mysql_query("SELECT * FROM `$tablename`", $link);
		while($tableinfo = mysql_fetch_array($sql_backup)) {
			$sql_query = "INSERT INTO `$tablename` VALUES (";
			foreach($tableinfo as $tablekey=>$tablevalue) {
				if(!is_numeric($tablekey)) {
					if(!empty($tablevalue) && !is_numeric($tablevalue)) {
						$tablevalue = removequotes($tablevalue);
						$sql_query .= "'".htmlentities($tablevalue)."',";
					}
					elseif(empty($tablevalue) && !is_numeric($tablevalue)) {
						$sql_query .= "NULL,";
					}
					else {
						$sql_query .= "$tablevalue,";
					}
				}
			}
			$sql_query = substr($sql_query,0,(strlen($sql_query)-1));
			$sql_query  .= ");";
			if($_POST[filetype] == "php") {
				$cgbackup .= "
mysql_query(\"$sql_query\", \$link);
";
			}
			elseif($_POST[filetype] == "sql") {
				$cgbackup .= "
$sql_query;";
			}
		}
	}
}

if($_POST{'filetype'} == "php") {
	$cgbackup .= "

?>";
}
elseif($_POST{'filetype'} == "sql") {
	$cgbackup .= "

-- EOF";
}

$filename = "$backuppath/$_POST[backupfilename].$_POST[filetype]";
$handle = fopen($filename,"w");
fwrite($handle,$cgbackup);
chmod($filename,0600);
fclose($handle);

$cgbackup = removehtml($cgbackup);
$cgadmin .= "<textarea style='width: 100%;height: 400px;font-family: monospace;'>$cgbackup</textarea>";

?>