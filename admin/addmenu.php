<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# addmenu.php                                                   #
# This is the page that contains the various elements needed to #
# add new Extra Menu items.  Extra menus contain additional     #
# items that the admins can set to appear on any number of      #
# pages throughout the site.  This is good for download links,  #
# related items, screenshot links, etc.                         #
#################################################################

$cgadmin = "
	<h1>$admin_addmenutxt[1]</h1>";

# If the administrator has not chosen any actions from the initial form, then we generate the initial form #
if(empty($_POST{'savemenu'})) {
	$cgadmin .= "
	<form name='addmenu' method='post' action='$cagcmsadmin?action=addmenu'>
		<fieldset>
			<div class='legend'>$admin_addmenutxt[2]</div>
			<fieldset>
				<select name='itemid[]' multiple='multiple' size='5'>";
	$sql_query = mysql_query("SELECT * FROM `extramenus` ORDER BY admintitle", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
					<option value='$rs[itemid]'>$rs[admintitle]</option>";
	}
	$cgadmin .= "
				</select>
				<fieldset>
					<div class='legend'>$admin_addmenutxt[3]</div>
						<div class='checkbox'>
							<input type='radio' name='menuaction' id='menuaction_edit' value='edit' checked='checked' />
							<label for='menuaction_edit'>$admin_ans[5]</label>
						</div>
						<div class='checkbox'>
							<input type='radio' name='menuaction' id='menuaction_remove' value='remove' />
							<label for='menuaction_remove'>$admin_ans[6]</label>
						</div>
				</fieldset>
			</fieldset>
			<input type='submit' name='savemenu' value='$admin_ans[7]' class='submit' />
			<fieldset>
				<label for='itemnum'>$admin_addmenutxt[4]:</label>
				<input type='text' name='itemnum' id='itemnum' value='1' class='submit' style='text-align: right;width: 30px !important;' />
			</fieldset>
		</fieldset>
		<input type='submit' name='savemenu' value='$admin_addmenutxt[4]' class='submit' />
	</form>
";
}

# If the user chose to edit or remove existing menu items, we perform the following actions #
elseif($_POST{'savemenu'} == $admin_ans[7]) {
	# If they chose to edit existing menu items, we need to generate the edit form #
	if($_POST{'menuaction'} == "edit") {
		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

		# We now need to build our editor form #
		$cgadmin .= "
	<form name='menuedit' method='post' action='$cagcmsadmin?action=addmenu'>
		<div class='legend'>$admin_addmenutxt[5]</div>
		<input type='hidden' name='itemids' id='itemids' value='".implode(',',$_POST['itemid'])."' />
		<fieldset>";

		# We need to pull the information for each menu item being edited from the database #
		foreach($_POST{'itemid'} as $item) {
			$sql_edit = mysql_query("SELECT * FROM `extramenus` WHERE `itemid` = '$item' LIMIT 1;", $link);
			while($rs = mysql_fetch_array($sql_edit)) {
				$itemid = $rs[itemid];
				$pageid = explode("|",$rs[pageid]);
				$cgadmin .= "
			<input type='hidden' name='itemid[$itemid]' value='$itemid' />
			<div class='legend'>$rs[title]</div>
			<fieldset>
				<label for='pageid$itemid'>$admin_addmenutxt[6]</label>
				<select name='pageid[$itemid][]' id='pageid$itemid' multiple='multiple'>";
				# We now need to figure out which pages this menu item belongs to #
				$sql_pageid = mysql_query("SELECT * FROM `idtable` ORDER BY idname",$link);
				while($rt = mysql_fetch_array($sql_pageid)) {
					$selected = "";
					foreach($pageid as $page) {
						if($page == $rt[id]) {
							$selected = " selected='selected'";
						}
					}
					$cgadmin .= "
					<option value='$rt[id]'$selected>$rt[idname]</option>";
				}
				$cgadmin .= "
				</select>
				<br />
				<label for='location$itemid'>$admin_addmenutxt[7]</label>
				<input type='text' name='location[$itemid]' id='location$itemid' value='$rs[location]' />
				<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addmenutxt{'7a'})."\")' border='0' />
				<br />
				<label for='title$itemid'>$admin_addmenutxt[8]</label>
				<input type='text' name='title[$itemid]' id='title$itemid' value='$rs[title]' />
				<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addmenutxt{'8a'})."\")' border='0' />
				<br />
				<label for='admintitle$itemid'>$admin_addmenutxt[11]</label>
				<input type='text' name='admintitle[$itemid]' id='admintitle$itemid' value='$rs[admintitle]' />
				<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addmenutxt{'11a'})."\")' border='0' />
			</fieldset>
		</fieldset>";
			}
		}
		$cgadmin .= "
		<input type='submit' name='savemenu' value='$admin_ans[13]' class='submit' />
	</form>";

	}

	# If the user chose to remove existing menu items, then we simply need to remove them #
	elseif($_POST{'menuaction'} == "remove") {
		foreach($_POST{'itemid'} as $item) {
			$sql_remove = "DELETE FROM `extramenus` WHERE `itemid` = '$item' LIMIT 1";
			if(mysql_query($sql_remove, $link)) {
				$inserted = true;
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error removing the menu item(s) you selected.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_remove."</div></div>";
				$inserted = false;
			}
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The item was removed successfully.</div>";
		}
	}
}

# If the user chose to create a new menu item, then we need to build the menu item creation form #
elseif($_POST{'savemenu'} == $admin_addmenutxt[4]) {

	$cgclose = "";
	# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
	$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

	# We will now build our page creation form. #
	$cgadmin = '
	<form name="menuedit" method="post" action="'.$cagcmsadmin.'?action=addmenu">
		<div class="legend">'.$admin_addmenutxt[9].'</div>
		<input type="hidden" name="itemnum" value="'.$_POST['itemnum'].'" />';

	# We will now generate the correct number of "new page" areas in the form #
	for($i=1;$i<=$_POST{'itemnum'};$i++) {
		$itemid = $i;
		$cgadmin .= '
		<fieldset>
			<label for="pageid'.$itemid.'">'.$admin_addmenutxt[6].'</label>
			<select name="pageid['.$itemid.'][]" id="pageid'.$itemid.'" multiple="multiple">';
		# We now need to figure out which section this page belongs to #
		$sql_pageid = mysql_query("SELECT * FROM `idtable` ORDER BY idname",$link);
		while($rt = mysql_fetch_array($sql_pageid)) {
			$cgadmin .= '
				<option value="'.$rt['id'].'">'.$rt['idname'].'</option>';
		}
		$cgadmin .= '
			</select>
			<label for="location'.$itemid.'">'.$admin_addmenutxt[7].'<img src="'.$imgdir.'/question.png" alt="?" onmouseover="return escape(\''.removequotes($admin_addmenutxt['7a']).'\')" border="0" /></label>
			<input type="text" name="location['.$itemid.']" id="location'.$itemid.'" />
			<br />
			<label for="title'.$pageid.'">'.$admin_addmenutxt[8].'<img src="'.$imgdir.'/question.png" alt="?" onmouseover="return escape(\''.removequotes($admin_addmenutxt['8a']).'\')" border="0" /></label>
			<input type="text" name="title['.$itemid.']" id="title'.$itemid.'" />
			<br />
			<label for="admintitle'.$pageid.'">'.$admin_addmenutxt[11].'<img src="'.$imgdir.'/question.png" alt="?" onmouseover="return escape(\''.removequotes($admin_addmenutxt['11a']).'\')" border="0" /></label>
			<input type="text" name="admintitle['.$itemid.']" id="admintitle'.$itemid.'" />
		</fieldset>';
	}
	$cgadmin .= '
		<input type="submit" name="savemenu" value="'.$admin_addmenutxt[10].'" />
	</form>';

}

# If the user has already gone through the menu item editing form, then we need to update the page information #
elseif($_POST{'savemenu'} == $admin_ans[13]) {
	if(get_magic_quotes_gpc()) {
		$_POST = stripslashes_deep($_POST);
	}
	# We now need to update the information in our extramenus table #
	foreach(explode(',',$_POST['itemids']) as $item) {
		extract($_POST);
		$title = mysql_real_escape_string($title[$item],$link);
		$admintitle = mysql_real_escape_string($admintitle[$item],$link);
		$location = mysql_real_escape_string($location[$item],$link);
		$pageid = mysql_real_escape_string(implode("|",$pageid[$item]),$link);
		$sql_update = "UPDATE `extramenus` SET `location` = '$location', `pageid` = '$pageid', `title` = '$title', `admintitle` = '$admintitle' WHERE `itemid` = $item LIMIT 1";
		if(mysql_query($sql_update, $link)) {
			$inserted = true;
		}
		else {
			$cgadmin .= '<div class=\"dbmessage\">There was an error committing the changes you made.<br />'.mysql_error().'</div><div>Following is the query you attempted to execute:<div>'.$sql_update.'</div></div>';
			$inserted = false;
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The category $catname was removed successfully.</div>";
		}
	}
}

# If the user has already been through the menu item creation form, then we need to actually create the new menu item(s) #
elseif($_POST{'savemenu'} == $admin_addmenutxt[10]) {
	# We now need to build our queries and insert the information into our extramenus table #
	for($i=1;$i<=$_POST{'itemnum'};$i++) {
		extract($_POST);
		$title = $title[$i];
		$admintitle = $admintitle[$i];
		$location = $location[$i];
		$pageid = implode("|",$pageid[$i]);
		$sql_insert = "INSERT INTO `extramenus` (`pageid`, `location`, `title`, `admintitle`) VALUES ('$pageid', '$location', '$title', '$admintitle')";
		if(mysql_query($sql_insert, $link)) {
			$inserted = true;
		}
		else {
			$cgadmin .= "<div class=\"dbmessage\">There was an error committing the changes you made.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_update."</div></div>";
			$inserted = false;
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The category $catname was removed successfully.</div>";
		}
	}
}

?>