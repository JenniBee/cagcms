<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# maintenance.php                                               #
# This is the page that contains various maintenance actions,   #
# such as recounting our section/category items, etc.           #
#################################################################

$cgadmin = "<h1>Perform Regular Site Maintenance</h1>";
	
if($user->checkperms('pref',MAINT,CREATE)) {
	if(empty($_POST{'maintact'})) {
		$cgadmin .= "
			<form name='maintenance' method='post' action='$cagcmsadmin?action=maintenance'>
				<fieldset>
					<ul class='featurelist'>
						<li>
							<input type='submit' class='submit' name='maintact' value='$maint_txt[1]' />
						</li>
						<li>
							<input type='submit' class='submit' name='maintact' value='$maint_txt[2]' />
						</li>
						<li>
							<input type='submit' class='submit' name='maintact' value='$maint_txt[3]' />
						</li>
					</ul>
				</fieldset>
				<fieldset>
					<legend>Back-Up Your CAGCMS Database</legend>
					<label for='dbtables'>$maint_txt[4]</label>
					<select multiple='multiple' name='dbtables[]' id='dbtables' size='6'>";
		$sql_tables = mysql_list_tables(DB);
		while($tablenames = mysql_fetch_row($sql_tables)) {
		foreach($tablenames as $tablename) {
			$cgadmin .= "
						<option value='$tablename'>$tablename</option>";
		}
		}
	
		$cghead = "
<script language='javascript' type='text/javascript'>
function checkAll (val) {
	var el = document.getElementById(\"dbtables\");
	for (var i = 0; i < el.length; i++) {
		el[i].selected = val;
	}
}
</script>";
	
		$cgadmin .= "
					</select>
					<br />
					<input type='button' value='".$maint_txt['5a']."' onclick='javascript: checkAll(true);' class='submit' />
					<input type='button' value='".$maint_txt['5b']."' onclick='javascript: checkAll(false);' class='submit' />
					<br />
					<label for='backupfilename'>
						$maint_txt[6]
						<span class='note'>
							$maint_txt[7]
						</span>
					</label>";
		$defaultfile = "backupdb_".date("Y-m-d",time());
		$cgadmin .= "
					<input type='text' name='backupfilename' id='backupfilename' value='$defaultfile' />
					<br />
					<label for='filetype'>
						$maint_txt[8]
					</label>
					<select name='filetype' id='filetype'>
						<option value='php' selected='selected'>PHP</option>
						<option value='sql'>SQL</option>
					</select>
					<br />
					<label for='backuptype'>
						$maint_txt[9]
					</label>
					<select name='backuptype' id='backuptype'>
						<option value='structure'>$maint_txt[10]</option>
						<option value='data'>$maint_txt[11]</option>
						<option value='both' selected='selected'>$maint_txt[12]</option>
					</select>
					<br />";
		if(substr(mysql_get_server_info(),0,1) >= 5) {
			$cgadmin .= "
					<label for='exportversion'>
						$maint_txt[13]
					</label>
					<select name='exportversion' id='exportversion'>
						<option value='5'>5.x</option>
						<option value='4'>4.x</option>
					</select>
					<br />";
		}
		$cgadmin .= "
				</fieldset>
				<input type='submit' class='submit' name='maintact' value='$maint_txt[14]' />
			</form>";
				
	}
	
	elseif($_POST{'maintact'} == $maint_txt[1]) {
		$sql_query = mysql_query("SELECT * FROM `sections`", $link);
		while($sec = mysql_fetch_array($sql_query)) {
			$sql_count = mysql_query("SELECT * FROM `content` WHERE `secid` = '$sec[secid]'", $link);
			$items = mysql_num_rows($sql_count);
			mysql_query("UPDATE `sections` SET `secitems` = '$items' WHERE `secid` = '$sec[secid]'", $link);
			$cgadmin .= "<p>$sec[secname] has $items items within it.</p>";
		}
	}
	
	elseif($_POST{'maintact'} == $maint_txt[2]) {
		$sql_query = mysql_query("SELECT * FROM `categories`", $link);
		while($cat = mysql_fetch_array($sql_query)) {
			$sql_count = mysql_query("SELECT * FROM `content` WHERE `catid` = '$cat[catid]'", $link);
			$items = mysql_num_rows($sql_count);
			mysql_query("UPDATE `categories` SET `catitems` = '$items' WHERE `catid` = '$cat[catid]'", $link);
			$cgadmin .= "<p>$cat[catname] has $items items within it.</p>";
		}
	}
	
	elseif($_POST{'maintact'} == $maint_txt[3]) {
		$sql_query = mysql_query("SELECT * FROM `usergroups`", $link);
		while($level = mysql_fetch_array($sql_query)) {
			$sql_count = mysql_query("SELECT * FROM `users` WHERE `userlevel` = '$level[groupid]'", $link);
			$items = mysql_num_rows($sql_count);
			mysql_query("UPDATE `usergroups` SET `members` = '$items' WHERE `groupid` = '$level[groupid]'",$link);
			$cgadmin .= "<p>$level[groupname] has $items items within it.</p>";
		}
	}
	
	elseif($_POST{'maintact'} == $maint_txt[14]) {
		require_once('backupdb.php');
	}
}
else {
	$cgadmin = $error_txt[1];
}

?>