<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# adduser.php                                                   #
# This is the page that contains the various elements needed to #
# add new users to the site.  Within this page, we can also     #
# modify/remove existing users and usergroups.                  #
#################################################################

$cgadmin = "This is going to be the page where we add/modify/remove users and usergroups on the site";

$cgadmin = "
	<h1>$admin_head[8]</h1>";

# If the user has YaBB user controls enabled for this CMS, then we don't want them messing with these functions. #
if($yabbok == 1) {
	$cgadmin .= "
	<div>
		$user_txt[0]
	</div>";
}

# If the user got to this page by clicking a link, then we need to generate the intial form to find out what they want to do. #
elseif(empty($_POST{'saveuser'}) && empty($_POST{'savegroup'})) {

	$cghead = "
<script language='javascript' type='text/javascript'>
function checkAll() {
	var what = document.getElementById('checkall');
	var where = document.getElementsByName('userid[]');
	for(var i=0;i<where.length;i++) {
		where[i].checked = what.checked;
	}
}
</script>";

	$cgadmin .= "
	$user_txt[1]
	<form name='adduser' method='post' action='$cagcmsadmin?action=adduser'>
		<h2>$user_txt[2]</h2>
			<fieldset id='adduserfield'>
				<div class='userfield'>
					<table summary='$user_txt[6]' class='usertable'>
						<thead>
							<tr>";
	$tablecols = array('<input type=\'checkbox\' name=\'checkall\' id=\'checkall\' onclick=\'checkAll(document.adduser)\' />',$user_txt[10],$user_txt[11],$user_txt[12],$user_txt[13],$user_txt[14],$user_txt[15],$user_txt[16],);
	foreach($tablecols as $tablecol) {
		if($tablecol != $tablecols[0]) {
			$cgadmin .= "
								<th>
									<label for='checkall'>$tablecol</label>
								</th>";
		}
		else {
			$cgadmin .= "
								<th>
									$tablecol
								</th>";
		}
	}
	$cgadmin .= "
							</tr>
						</thead>";
	$tablerows = array('userid','username','useremail','userrealname','userlevel','userip','userreg','userlogin');
	$sql_query = mysql_query("SELECT * FROM `users` ORDER BY `username`", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
							<tr>";
		foreach($tablerows as $tablerow) {
			if($tablerow == "userid") {
				$tempid = $rs['userid'];
				$rs[$tablerow] = "<input type='checkbox' class='check' name='userid[]' id='userid".$rs['userid']."' value='$rs[$tablerow]' />";
			}
			elseif($tablerow == "userip") {
				$rs[$tablerow] = explode("|",$rs[$tablerow]);
				$iplist = "";
				for($i=1;$i<=count($rs[$tablerow]);$i++) {
					$j = $i-1;
					$iplist .= $rs[$tablerow][$j];
					if($i<count($rs[$tablerow])) {
						$iplist .= ",<br />";
					}
				}
				$rs[$tablerow] = $iplist;
			}
			elseif($tablerow == "userreg" || $tablerow == "userlogin") {
				$rs[$tablerow] = date("m/d/Y \a\\t H:i",$rs[$tablerow]);
			}
			elseif($tablerow == "userlevel") {
				$sql = mysql_query("SELECT `groupname` FROM `usergroups` WHERE `groupid` = $rs[userlevel]",$link);
				while($group = mysql_fetch_array($sql)) {
					$rs[userlevel] = $group[groupname];
				}
			}
			else {
				$rs[$tablerow] = removehtml($rs[$tablerow]);
			}
			if(empty($rs[$tablerow])) {
				$cgadmin .= "
								<td>
									&nbsp;
								</td>";
			}
			elseif($tablerow != "userid") {
				$cgadmin .= "
								<td>
									<label for='userid".$tempid."'>".$rs[$tablerow]."</label>
								</td>";
			}
			else {
				$cgadmin .= "
								<td>
									$rs[$tablerow]
								</td>";
			}
		}
		$cgadmin .= "
							</tr>";
	}
	$cgadmin .= "
					</table>
				</div>
				<fieldset>
					<legend>$user_txt[7]</legend>
					<div class='checkbox'>
						<input type='radio' name='useraction' id='useractionedit' value='edit' checked='checked' /><label for='useractionedit'>$admin_ans[5]</label>
					</div>
					<div class='checkbox'>
						<input type='radio' name='useraction' id='useractionremove' value='remove' /><label for='useractionremove'>$admin_ans[6]</label>
					</div>
					<input type='submit' name='saveuser' class='submit' value='$admin_ans[7]' />
				</fieldset>
				<fieldset>
					<label for='usernum'><strong>$user_txt[8]:</strong></label><input type='text' name='usernum' id='usernum' value='1' class='submit' style='text-align: right;width: 30px !important;' />
					<input type='submit' name='saveuser' value='$user_txt[8]' class='submit' />
				</fieldset>
			</fieldset>
	</form>

	<form name='addgroup' method='post' action='$cagcmsadmin?action=adduser'>
		<fieldset>
			<legend>$user_txt[4]</legend>
				<select name='usergroups[]' multiple='multiple' size='5'>";
	$sql_query = mysql_query("SELECT * FROM `usergroups`", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
				<option value='$rs[groupid]'>$rs[groupname]</option>";
	}
	$cgadmin .= "
			</select>
			<fieldset>
				<legend>$user_txt[7]</legend>
				<div class='checkbox'>
					<input type='radio' name='groupaction' id='groupactionedit' value='edit' checked='checked' /><label for='groupactionedit'>$admin_ans[5]</label>
				</div>
				<div class='checkbox'>
					<input type='radio' name='groupaction' id='groupactionremove' value='remove' /><label for='groupactionremove'>$admin_ans[6]</label>
				</div>
				<input type='submit' name='savegroup' class='submit' value='$admin_ans[7]' />
			</fieldset>
			<fieldset>
				<label for='groupnum'><strong>$user_txt[9]:</strong></label><input type='text' name='groupnum' id='groupnum' value='1' class='submit' style='text-align: right; width: 30px !important;' />
				<input type='submit' name='savegroup' value='$user_txt[9]' class='submit' />
			</fieldset>
		</fieldset>
	</form>";
}

# If the user chooses to edit or remove users, we perform the following actions #
elseif($_POST{'saveuser'} == $admin_ans[7]) {
	if(empty($_POST[userid])) {
		unset($_POST);
		header("Location: $cagcmsadmin?action=adduser");
	}
	if($_POST{'useraction'} == "edit") {
		$cgadmin .= "
	<form name='useredit' method='post' action='$cagcmsadmin?action=adduser'>";
		foreach($_POST{'userid'} as $userid) {
			$sql = mysql_query("SELECT * FROM `users` WHERE `userid` = '$userid'", $link);
			while($user = mysql_fetch_array($sql)) {
				$cgadmin .= "
		<input type='hidden' name='userid[$userid]' value='$userid' />
		<fieldset>
			<label for='username$userid'>$user_txt[10]</label>
			<input type='text' name='username[$userid]' id='username$userid' value='$user[username]' />
			<br />
			<label for='password$userid'>$user_txt[19]</label>
			<input type='password' name='password[$userid]' id='password$userid' value='$user[password]' />
			<br />
			<label for='useremail$userid'>$user_txt[11]</label>
			<input type='text' name='useremail[$userid]' id='useremail$userid' value='$user[useremail]' />
			<br />
			<label for='userrealname$userid'>$user_txt[12]</label>
			<input type='text' name='userrealname[$userid]' id='userrealname$userid' value='$user[userrealname]' />
			<br />
			<label for='userlevel$userid'>$user_txt[13]</label>
			<select name='userlevel[$userid]' id='userlevel$userid'>
				<option value='0'>&nbsp;</option>";
				$sql_level = mysql_query("SELECT * FROM `usergroups`", $link);
				while($levels = mysql_fetch_array($sql_level)) {
					if($user[userlevel] == $levels[groupid]) {
						$selected = " selected='selected'";
					}
					else {
						$selected = "";
					}
					$cgadmin .= "
				<option value='$levels[groupid]'$selected>$levels[groupname]</option>";
				}
				$cgadmin .= "
			</select>
			<fieldset class='regdate'>
				<legend>$user_txt[15]</legend>
				<select name='regmonth[$userid]' id='regmonth$userid' style='width: 4em !important;'>";

					$regmonth = date("n",$user[userreg]);
					$regday = date("j",$user[userreg]);
					$regyear = date("Y",$user[userreg]);
					$reghour = date("G",$user[userreg]);
					$regmin = date("i",$user[userreg]);


					for($i=1;$i<=12;$i++) {
						if($i <= 9) {
							$j = "0$i";
						}
						else {
							$j = $i;
						}
						if($i == $regmonth) {
							$selected = " selected='selected'";
						}
						else {
							$selected = "";
						}
						$cgadmin .= "
				<option value='$i'$selected>$j</option>";
					}
					$cgadmin .= "
			</select>
			 / 
			<select name='regday[$userid]' id='regday$userid' style='width: 4em !important;'>";
					for($i=1;$i<=31;$i++) {
						if($i <= 9) {
							$j = "0$i";
						}
						else {
							$j = $i;
						}
						if($i == $regday) {
							$selected = " selected='selected'";
						}
						else {
							$selected = "";
						}
						$cgadmin .= "
						<option value='$i'$selected>$j</option>";
					}
					$cgadmin .= "
			</select>
			 / 
			<select name='regyear[$userid]' id='regyear$userid' style='width: 6em !important;'>";
					for($i=(date("Y",time())-15);$i<=(date("Y",time())+15);$i++) {
						if($i == $regyear) {
							$selected = " selected='selected'";
						}
						else {
							$selected = "";
						}
						$cgadmin .= "
					<option value='$i'$selected>$i</option>";
					}
					$cgadmin .="
			</select>
			 at 
			<select name='reghour[$userid]' id='reghour$userid' style='width: 4em !important;'>";
					for($i=0;$i<=24;$i++) {
						if($i<=9) {
							$j = "0$i";
						}
						else {
							$j = $i;
						}
						if($i == $reghour) {
							$selected = " selected='selected'";
						}
						else {
							$selected = "";
						}
						$cgadmin .= "
				<option value='$i'$selected>$j</option>";
					}
					$cgadmin .= "
			</select>
			:
			<select name='regmin[$userid]' id='regmin$userid' style='width: 4em !important;'>";
					for($i=0;$i<=59;$i++) {
						if($i<=9) {
							$j = "0$i";
						}
						else {
							$j = $i;
						}
						if($i == $regmin) {
							$selected = " selected='selected'";
						}
						else {
							$selected = "";
						}
						$cgadmin .= "
				<option value='$i'$selected>$j</option>";
					}
					$cgadmin .= "
			</select>
		</fieldset>
	</fieldset>";
			}
		}
		$cgadmin .= "
		<input type='submit' name='saveuser' value='$user_txt[17]' class='submit' />
	</form>";
	}

	elseif($_POST{'useraction'} == "remove") {
		foreach($_POST{'userid'} as $userid) {
			if($userid != 1) {
				$sql_group = mysql_query("SELECT `userlevel` FROM `users` WHERE `userid` = '$userid'", $link);
				while($userlevel = mysql_fetch_array($sql_group)) {
					$groupid = $userlevel['userlevel'];
				}
				$sql_remove = "DELETE FROM `users` WHERE `userid` = $userid";
				if(mysql_query($sql_remove, $link)) {
					$sql_remove = "UPDATE `usergroups` SET `members` = `members`-1 WHERE `groupid` = '$groupid'";
					if(mysql_query($sql_remove, $link)) {
						$inserted = true;
					}
					else {
						$cgadmin .= "There was an error removing the user with ID #: $userid.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_remove."</div></div>";
						$inserted = false;
					}
				}
				else {
					$cgadmin .= "There was an error removing the user with ID #: $userid.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_remove."</div></div>";
					$inserted = false;
				}
			}
			else {
				$cgadmin .= "<div>$user_txt[25]</div>";
			}
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The user with ID #: $userid was removed successfully.</div>";
		}
	}
}

# If the user chooses to edit or remove groups, we perform the following actions #
elseif($_POST{'savegroup'} == $admin_ans[7]) {
	if(empty($_POST{'usergroups'})) {
		unset($_POST);
		header("Location: $cagcmsadmin?action=adduser");
	}
	if($_POST{'groupaction'} == "edit") {
		$cgadmin .= "
		<form name='groupedit' method='post' action='$cagcmsadmin?action=adduser'>";
		foreach($_POST{'usergroups'} as $groupid) {
			$sql_groups = mysql_query("SELECT * FROM `usergroups` WHERE `groupid` = '$groupid'", $link);
			while($groupinfo = mysql_fetch_array($sql_groups)) {
				$cgadmin .= "
		<input type='hidden' name='groupid[$groupid]' value='$groupid' />
			<fieldset>
				<label for='groupname$groupid'>$user_txt[20]</label>
				<input type='text' name='groupname[$groupid]' id='groupname$groupid' value='$groupinfo[groupname]' />
				<br />
				<label for='groupmems$groupid'>$user_txt[21]<span class='note'>$user_txt[23]</span></label>";
				$sql_groups = mysql_query("SELECT `username`, `userrealname`, `userlevel` FROM `users`",$link);
				$selectsize = mysql_num_rows($sql_groups);
				if($selectsize >= 25) {
					$selectsize = 25;
				}
				$cgadmin .= "
				<select multiple='multiple' name='groupmems[$groupid][]' id='groupmems$groupid' size='$selectsize'>";
				while($groupmem = mysql_fetch_array($sql_groups)) {
					if($groupmem[userlevel] == $groupinfo[groupid]) {
						$selected = " selected='selected'";
					}
					else {
						$selected = "";
					}
					$cgadmin .= "
					<option value='$groupmem[username]'$selected>$groupmem[userrealname]</option>";
				}
				$cgadmin .= "
				</select>";
				$perm_txt = array(1=>'Create',16=>'Modify',256=>'Remove',4096=>'View');
				$lvl_txt = array(1=>'All',2=>'Sections',4=>'Categories',8=>'Content Pages');
				$cgadmin .= "
				<fieldset>
					<legend>Content Permissions</legend>";
				foreach($perm_txt as $key=>$val) {
					$cgadmin .= "
					<fieldset class='permfield'>
						<legend>$val</legend>";
					foreach($lvl_txt as $key2=>$val2) {
						$cgadmin .= "
							<input type='checkbox' name='contentperms' value='".$key*$key2."' id='contentperms.".$key*$key2."' />
							<label for='contentperms".$key*$key2."'>$val2</label>";
					}
					$cgadmin .= "
					</fieldset>";
				}
				$cgadmin .= "
				</fieldset>
				<fieldset>
					<legend>News Permissions</legend>";
				$perm_txt = array(1=>'Create',16=>'Modify',256=>'Remove');
				$lvl_txt = array(1=>'All',2=>'Own');
				foreach($perm_txt as $key=>$val) {
					$cgadmin .= "
					<fieldset class='permfield'>
						<legend>$val</legend>";
					foreach($lvl_txt as $key2=>$val2) {
						$cgadmin .= "
							<input type='checkbox' name='newsperms' value='".$key*$key2."' id='newsperms.".$key*$key2."' />
							<label for='newsperms".$key*$key2."'>$val2</label>";
					}
					$cgadmin .= "
					</fieldset>";
				}
				$cgadmin .= "
				</fieldset>
				<fieldset>
					<legend>User and Usergroup Permissions</legend>";
				$perm_txt = array(1=>'Create',16=>'Modify',256=>'Remove');
				$lvl_txt = array(1=>'All',2=>'Usergroups',4=>'Individual Users');
				foreach($perm_txt as $key=>$val) {
					$cgadmin .= "
					<fieldset class='permfield'>
						<legend>$val</legend>";
					foreach($lvl_txt as $key2=>$val2) {
						$cgadmin .= "
							<input type='checkbox' name='userperms' value='".$key*$key2."' id='userperms.".$key*$key2."' />
							<label for='userperms".$key*$key2."'>$val2</label>";
					}
					$cgadmin .= "
					</fieldset>";
				}
				$cgadmin .= "
				</fieldset>";
			}
		}
		$cgadmin .= "
		</fieldset>
		<input type='submit' name='savegroup' value='$user_txt[17]' />
	</form>";
	}

	elseif($_POST{'groupaction'} == "remove") {
		foreach($_POST{'usergroups'} as $groupid) {
			if($groupid != 1) {
				$sql_remove = "DELETE FROM `usergroups` WHERE `groupid` = '$groupid'";
				if(mysql_query($sql_remove, $link)) {
					$inserted = true;
				}
				else {
					$cgadmin .= "There was an error removing the group with ID: $groupid.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_remove."</div></div>";
					$inserted = false;
				}
			}
			else {
				$cgadmin .= "<div class='dbmessage'>$user_txt[24]</div>";
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">The usergroup with ID: $groupid was removed successfully.</div>";
			}
		}
	}
}

# If the user chooses to create new users, we need to generate the user creation form #
elseif($_POST{'saveuser'} == $user_txt[8]) {
	$cgadmin .= "
	<form name='newuser' method='post' action='$cagcmsadmin?action=adduser'>
		<input type='hidden' name='usernum' value='$_POST[usernum]' />";
	for($userid=1;$userid<=$_POST{'usernum'};$userid++) {
		$cgadmin .= "
		<fieldset>
			<label for='username$userid'>$user_txt[10]</label>
			<input type='text' name='username[$userid]' id='username$userid' />
			<br />
			<label for='password$userid'>$user_txt[19]</label>
			<input type='password' name='password[$userid]' id='password$userid' />
			<br />
			<label for='useremail$userid'>$user_txt[11]</label>
			<input type='text' name='useremail[$userid]' id='useremail$userid' />
			<br />
			<label for='userrealname$userid'>$user_txt[12]</label>
			<input type='text' name='userrealname[$userid]' id='userrealname$userid' />
			<br />
			<label for='userlevel$userid'>$user_txt[13]</label>
			<select name='userlevel[$userid]' id='userlevel$userid'>
				<option value='0'>&nbsp;</option>";
				$sql_level = mysql_query("SELECT * FROM `usergroups`", $link);
		while($levels = mysql_fetch_array($sql_level)) {
			$cgadmin .= "
				<option value='$levels[groupid]'>$levels[groupname]</option>";
		}
		$cgadmin .= "
			</select>
		</fieldset>";

	}
	$cgadmin .= "
		<input type='submit' name='saveuser' value='$user_txt[18]' />
	</form>";
}

# If the user chooses to create new usergroups, we need to generate the usergroup creation form #
elseif($_POST{'savegroup'} == $user_txt[9]) {
	$cgadmin .= "
	<form name='newgroup' method='post' action='$cagcmsadmin?action=adduser'>
		<input type='hidden' name='groupnum' value='$_POST[groupnum]' />";
	for($groupid=1;$groupid<=$_POST{'groupnum'};$groupid++) {
		$cgadmin .= "
			<fieldset>
				<label for='groupname$groupid'>$user_txt[20]</label>
				<input type='text' name='groupname[$groupid]' id='groupname$groupid' />
				<br />
				<label for='groupmems$groupid'>$user_txt[21]<span class='note'>$user_txt[23]</span></label>
				<select multiple='multiple' name='groupmems[$groupid][]' id='groupmems$groupid'>";
		$sql_groups = mysql_query("SELECT `username`, `userrealname` FROM `users`",$link);
		while($groupmem = mysql_fetch_array($sql_groups)) {
			$cgadmin .= "
					<option value='$groupmem[username]'>$groupmem[userrealname]</option>";
		}
		$cgadmin .= "
				</select>
			</fieldset>";
	}
	$cgadmin .= "
		<input type='submit' name='savegroup' value='$user_txt[22]' />
	</form>";
}

# If the user already went through the user editing form, then we need to update that information #
elseif($_POST{'saveuser'} == $user_txt[17]) {
	foreach($_POST{'userid'} as $user) {
#		print_r($_POST);
#		die();
		$sql_edit = array();
		$sql = mysql_query("SELECT * FROM `users` WHERE `userid` = '$user'", $link);
		$userreg[$user] = $_POST[regyear][$user]."-".$_POST[regmonth][$user]."-".$_POST[regday][$user]." ".$_POST[reghour][$user].":".$_POST[regmin][$user];
		echo $userreg[$user];
		while($oldinfo = mysql_fetch_array($sql)) {
			# First, we have to check to see if the user's password has been changed #
			if($oldinfo[password] != $_POST[password][$user] && $oldinfo[password] != md5_base64($_POST[password][$user])) {
				$password[$user] = md5_base64($_POST[password][$user],$link);
				$sql_edit[] = "`password` = '".mysql_real_escape_string($password[$user],$link)."'";
			}
			# Next, we'll check to see if the user's username has been changed #
			if($oldinfo[username] != $_POST[username][$user]) {
				$username = $_POST[username];
				$username = $username[$user];
				$sql_edit[] = "`username` = '".mysql_real_escape_string($username,$link)."'";
			}
			# Next, we'll check to see if the user's email address has been changed #
			if($oldinfo[useremail] != $_POST[useremail][$user]) {
				$useremail = $_POST[useremail];
				$useremail = $useremail[$user];
				$sql_edit[] = "`useremail` = '".mysql_real_escape_string($useremail,$link)."'";
			}
			# Next, we'll check to see if the user's real name has been changed #
			if($oldinfo[userrealname] != $_POST[userrealname][$user]) {
				$sql_edit[] = "`userrealname` = '".mysql_real_escape_string($_POST[userrealname][$user],$link)."'";
			}
			# Next, we'll check to see if the user's reg date has changed #
			if($oldinfo[userreg] != strtotime($userreg[$user])) {
				$userreg[$user] = strtotime($userreg[$user]);
				$sql_edit[] = "`userreg` = '".$userreg[$user]."'";
			}
			# Finally, we'll check to see if the user's level has changed #
			if($oldinfo[userlevel] != $_POST[userlevel][$user]) {
				$sql_changegroup = "UPDATE `usergroups` SET `members` = `members`-1 WHERE `groupid` = '$oldinfo[userlevel]'";
				$sql_changegroup2 = "UPDATE `usergroups`  SET `members` = `members`+1 WHERE `groupid` = '$_POST[userlevel][$user]'";
				$sql_edit[] = "`userlevel` = '".$_POST['userlevel'][$user]."'";
			}
		}
		if(!empty($sql_edit)) {
			$sql_edit = "UPDATE `users` SET ".join(", ",$sql_edit);
			# If anything has changed, then we need to update the information #
			$sql_edit .= " WHERE `userid` = '$user' LIMIT 1";
			if(mysql_query($sql_edit)) {
				if(!empty($sql_changegroup)) {
					if(mysql_query($sql_changegroup, $link) && mysql_query($sql_changegroup2, $link)) {
						$inserted = true;
					}
					else {
						$cgadmin .= "<div class=\"dbmessage\">There was an error updating the user with ID #: $user.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_changegroup."</div><div>".$sql_changegroup2."</div></div>";
						$inserted = false;
					}
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating the user with ID #: $user.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_edit."</div></div>";
				$inserted = false;
			}
		}
		else {
			$cgadmin .= "<div class=\"dbmessage\">No changes to the user with ID #: $user were detected; therefore, no changes were made.</div>";
			$inserted = false;
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The user with ID #: $user was updated successfully.</div>";
		}
	}
}

# If the user already went through the group editing form, then we need to update that information #
elseif($_POST{'savegroup'} == $user_txt[17]) {
	foreach($_POST[groupid] as $groupid) {
		$sql = mysql_query("SELECT * FROM `usergroups` WHERE `groupid` = '$_POST[groupid]'", $link);
		while($groupinfo = mysql_fetch_array($sql)) {
			$sql_edit = array();
			if($_POST[groupname][$groupid] != $groupinfo{'groupname'}) {
				$sql_edit[] = "`groupname` = '$_POST[groupname][$groupid]'";
			}
			$count = count($_POST[groupmems][$groupid]);
			if($count != $groupinfo[members]) {
				$sql_edit[] = "`members` = '$count'";
			}
			if(!empty($sql_edit)) {
				$sql_edit = "UPDATE `usergroups` SET ".join(", ",$sql_edit)." WHERE `groupid` = $groupid";
				if(mysql_query($sql_edit, $link)) {
					$sql_edit = "UPDATE `users` SET `userlevel` = 0 WHERE `userlevel` = '$groupid'";
					if(mysql_query($sql_edit, $link)) {
						$inserted = true;
						foreach($_POST[groupmems][$groupid] as $user) {
							$sql_add = "UPDATE `users` SET `userlevel` = '$groupid' WHERE `username` = '$user'";
							if(mysql_query($sql_add, $link)) {
								$cgadmin .= "<div class=\"dbmessage\">There was an error updating the group for $user.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_add."</div></div>";
								$inserted = false;
							}
						}
					}
					else {
						$cgadmin .= "<div class=\"dbmessage\">There was an error removing users from the group with ID: $groupid.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_edit."</div></div>";
						$inserted = false;
					}
				}
				else {
					$cgadmin .= "<div class=\"dbmessage\">There was an error updating the group with ID: $groupid.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_edit."</div></div>";
					$inserted = false;
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">No changes to the group with ID: $groupid were detected; therefore, no changes were made.</div>";
				$inserted = false;
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">The group with ID: $groupid was updated successfully.</div>";
			}
		}
	}
}

# If the user already went through the user creation form, then we need to store that information #
elseif($_POST{'saveuser'} == $user_txt[18]) {
	for($userid=1;$userid<=$_POST{'usernum'};$userid++) {
		extract($_POST);
		$password[$userid] = md5_base64($password[$userid]);
		$userreg = time();
		$sql = "INSERT INTO `users` (`username`,`password`,`useremail`,`userrealname`,`userlevel`,`userreg`) VALUES ('$username[$userid]','$password[$userid]','$useremail[$userid]','$userrealname[$userid]','$userlevel[$userid]','$userreg')";
		if(mysql_query($sql,$link)) {
			$sql = "UPDATE `usergroups` SET `members` = `members`+1 WHERE `groupid` = '$userlevel'";
			if(mysql_query($sql, $link)) {
				$inserted = true;
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error adding ".$username[$userid].".".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql."</div></div>";
				$inserted = false;
			}
		}
		else {
			$cgadmin .= "<div class=\"dbmessage\">There was an error adding ".$username[$userid].".".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql."</div></div>";
			$inserted = false;
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">".$username[$userid]." was added successfully.</div>";
		}
	}
}

# If the user already went through the group creation form, then we need to store that information #
elseif($_POST{'savegroup'} == $user_txt[22]) {
	for($groupid=1;$groupid<=$_POST{'groupnum'};$groupid++) {
		extract($_POST);
		$groupmemnum = count($groupmems[$groupid]);
		$sql = "INSERT INTO `usergroups` (`groupname`, `members`) VALUES ('$groupname[$groupid]','$groupmemnum')";
		if(mysql_query($sql, $link)) {
			if($groupmemnum != 0) {
				$inserted = true;
				foreach($groupmems[$groupid] as $groupmember) {
					$sql_groupid = mysql_query("SELECT `groupid` FROM `usergroups` WHERE `groupname` = '$groupname[$groupid]' LIMIT 1;", $link);
					while($groupidnum = mysql_fetch_array($sql_groupid)) {
						$sql_member = "UPDATE `users` SET `userlevel` = '$groupidnum[groupid]' WHERE `username` = '$groupmember'";
						if(!mysql_query($sql_member, $link)) {
							$cgadmin .= "<div class=\"dbmessage\">There was an error adding users to ".$groupname[$groupid].".".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql."</div></div>";
							$inserted = false;
						}
					}
				}
			}
		}
		else {
			$cgadmin .= "<div class=\"dbmessage\">There was an error adding ".$groupname[$groupid].".".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql."</div></div>";
			$inserted = false;
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">".$groupname[$groupid]." was added successfully.</div>";
		}
	}
}
		
?>