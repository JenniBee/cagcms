<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# reports.php                                                   #
# This files generates the reports that you will find in the    #
# CAGCMS administration center                                  #
#################################################################

$cgadmin = "
	<h1>$admin_head[9]</h1>
		$reports_txt[1]";

if($user->checkperms('pref',REPORTS,CREATE)) {
	# If YaBB membership is disabled, we will generate a report of how many total registered members we have. #
	$cgadmin .= "
		<h2>".$reports_txt{'1a'}."</h2>";
	if(!$yabbok) {
		$sql_count = mysql_query("SELECT `username` FROM `users` ORDER BY `userid` DESC", $link);
		$count = mysql_num_rows($sql_count);
		while($tempvar = mysql_fetch_array($sql_count)) {
			$lastmem = $tempvar['username'];
			break;
		}
	}
	else {
		$sql_count = file_get_contents($memberdir."/members.ttl");
		$sql_count = explode("|",$sql_count);
		$count = $sql_count[0];
		$lastmem = $sql_count[1];
	}
	$cgadmin .= "
		<div class='lineitem'>
			$reports_txt[2]<strong>$count</strong>$reports_txt[3]<br /><strong>$lastmem</strong>$reports_txt[11]
		</div>";
	if(!$yabbok) {
	# If YaBB membership is disabled, we will also generate a report of which membergroups we have set up, and how many members are in each group #
		$cgadmin .= "
			<div class='lineitem'>";
		$sql_count = mysql_query("SELECT * FROM `usergroups`", $link);
		while($group = mysql_fetch_array($sql_count)) {
			$cgadmin .= "
			<p>$reports_txt[4]<strong>$group[groupname]</strong>$reports_txt[5]<strong>$group[members]</strong>$reports_txt[6]</p>";
		}
		$cgadmin .= "
			</div>";
	}

	# We will now generate a report showing how many total content items are on the site, and how many content items are in each section and category #
	$cgadmin .= "
			<h2>".$reports_txt{'1b'}."</h2>";
	$cgadmin .= "
		<table class='reporttable'>
			<thead>
				<tr>
					<th>
						$reports_txt[8]
					</th>
					<th>
						$reports_txt[9]
					</th>
					<th>
						$reports_txt[10]
					</th>
				</tr>
			</thead>";
	$sql_sec = mysql_query("SELECT * FROM `sections`", $link);
	while($sec = mysql_fetch_array($sql_sec)) {
		$cgadmin .= "
			<tr class='sections'>
				<td>
					$sec[secname]
				</td>
				<td>
					$sec[secitems]$reports_txt[7]
				</td>
				<td style='text-align: right;'>
					$sec[sechit]
				</td>
			</tr>";
	
		$sql_cat = mysql_query("SELECT * FROM `categories` WHERE `secid` = '$sec[secid]'", $link);
		if(mysql_num_rows($sql_cat) != 0) {
			while($cat = mysql_fetch_array($sql_cat)) {
				$cgadmin .= "
			<tr class='cat'>
				<td>
					$cat[catname]
				</td>
				<td>
					$cat[catitems]$reports_txt[7]
				</td>
				<td style='text-align: right;'>
					$cat[cathit]
				</td>
			</tr>";
				$sql_cont = mysql_query("SELECT * FROM `content` WHERE `catid` = '$cat[catid]'", $link);
				if(mysql_num_rows($sql_cont) != 0) {
					while($cont = mysql_fetch_array($sql_cont)) {
						$cgadmin .= "
			<tr class='cont'>
				<td>
					$cont[pagetitle]
				</td>
				<td>
					&nbsp;
				</td>
				<td style='text-align: right;'>
					$cont[contenthit]
				</td>
			</tr>";
					}
				}
			}
		}
	}
	$cgadmin .= "
		</table>";
}
else {
	$cgadmin = $error_txt[1];
}
?>