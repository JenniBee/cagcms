<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# addscreens.php                                                #
# This is the page that contains the various elements needed to #
# add new screenshot items.  Screenshots will be automatically  #
# displayed on all selected pages throughout the site, as long  #
# as the template for that page includes the <cgcms             #
# screenblock> variable.                                        #
#################################################################

$cgadmin = "
	<h1>$admin_addscreenstxt[1]</h1>";

# If the administrator has not chosen any actions from the initial form, then we generate the initial form #
if(empty($_POST{'savescreen'})) {
	$cgadmin .= "
	<form name='addscreens' method='post' action='$cagcmsadmin?action=addscreens'>
	<div>
		$admin_addscreenstxt[2]
		<div class='lineitem'>
			<select name='imgid[]' multiple='multiple' size='5'>";
	$sql_query = mysql_query("SELECT * FROM `screenshots`", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
				<option value='$rs[imgid]'>$rs[title]</option>";
	}
	$cgadmin .= "
			</select>
		</div>
	$admin_addscreenstxt[3]
		<label class='radio'><input type='radio' name='screenaction' value='edit' checked='checked' /> $admin_ans[5]</label>
		<label class='radio'><input type='radio' name='screenaction' value='remove' /> $admin_ans[6]</label>
		<input type='submit' name='savescreen' value='$admin_ans[7]' class='submit' />
	</div>
	<div class='submit'>
		<label><strong>$admin_addscreenstxt[4]:</strong> <input type='text' name='itemnum' value='1' class='submit' style='text-align: right;width: 30px !important;' /></label>
		<input type='submit' name='savescreen' value='$admin_addscreenstxt[4]' class='submit' />
	</div>
	</form>
";
}

# If the user chose to edit or remove existing screenshots, we perform the following actions #
elseif($_POST{'savescreen'} == $admin_ans[7]) {
	# If they chose to edit existing screenshots, we need to generate the edit form #
	if($_POST{'screenaction'} == "edit") {
		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

		# We now need to build our editor form #
		$cgadmin .= "
	<div>
	<form name='screenedit' method='post' action='$cagcmsadmin?action=addscreens'>
		$admin_addscreenstxt[5]";

		# We need to pull the information for each screenshot being edited from the database #
		foreach($_POST{'imgid'} as $item) {
			$sql_edit = mysql_query("SELECT * FROM `screenshots` WHERE `imgid` = '$item' LIMIT 1;", $link);
			while($rs = mysql_fetch_array($sql_edit)) {
				$imgid = $rs[imgid];
				$pageid = explode("|",$rs[pageid]);
				$cgadmin .= "
		<input type='hidden' name='imgid[$imgid]' value='$imgid' />
		<fieldset>
		<div class='legend'>$rs[title]</div>
		<fieldset>
			<label for='pageid$imgid'>$admin_addscreenstxt[6]</label>
			<select name='pageid[$imgid][]' id='pageid$imgid' multiple='multiple'>";
				# We now need to figure out which pages this screenshot belongs to #
				$sql_pageid = mysql_query("SELECT * FROM `idtable`",$link);
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
			<label for='location_thumb$imgid'>$admin_addscreenstxt[11]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'11a'})."\")' border='0' /></label>
			<input type='text' name='location_thumb[$imgid]' id='location_thumb$imgid' value='$rs[location_thumb]' />
			<br />
			<label for='location$imgid'>$admin_addscreenstxt[7]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'7a'})."\")' border='0' /></label>
			<input type='text' name='location[$imgid]' id='location$imgid' value='$rs[location]' />
			<br />
			<label for='title$imgid'>$admin_addscreenstxt[8]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'8a'})."\")' border='0' /></label>
			<input type='text' name='title[$imgid]' id='title$imgid' value='$rs[title]' />
			<br />
			<label for='caption$imgid'>$admin_addscreenstxt[12]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'12a'})."\")' border='0' /></label>
			<input type='text' name='caption[$imgid]' id='caption$imgid' value='$rs[caption]' />
		</fieldset>";
			}
		}
		$cgadmin .= "
		</fieldset>
		<input type='submit' name='savescreen' value='$admin_ans[13]' class='submit' />
	</form>
	</div>";

	}

	# If the user chose to remove existing screenshots, then we simply need to remove them #
	elseif($_POST{'screenaction'} == "remove") {
		foreach($_POST{'imgid'} as $item) {
			$sql_remove = "DELETE FROM `screenshots` WHERE `imgid` = '$item' LIMIT 1";
			if(!mysql_query($sql_remove, $link)) {
				die("There was an error removing one or more of the screenshots.".mysql_error());
			}
			$cgadmin .= "<div>$sql_remove</div>";

		}
		$cgadmin .= "<div>The actions listed above were executed successfully.</div>";
	}
}

# If the user chose to create a new screenshot, then we need to build the screenshot creation form #
elseif($_POST{'savescreen'} == $admin_addscreenstxt[4]) {

	$cgclose = "";
	# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
	$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

	# We will now build our page creation form. #
	$cgadmin = "
	<div>
	<form name='screenedit' method='post' action='$cagcmsadmin?action=addscreens'>
		$admin_addscreenstxt[9]
		<input type='hidden' name='itemnum' value='$_POST[itemnum]' />";

	# We will now generate the correct number of "new page" areas in the form #
	for($i=1;$i<=$_POST{'itemnum'};$i++) {
		$imgid = $i;
		$cgadmin .= "
		<fieldset>
		<fieldset>
			<label for='pageid$imgid'>$admin_addscreenstxt[6]</label>
			<select name='pageid[$imgid][]' id='pageid$imgid' multiple='multiple'>";
		# We now need to figure out which section this page belongs to #
		$sql_pageid = mysql_query("SELECT * FROM `idtable`",$link);
		while($rt = mysql_fetch_array($sql_pageid)) {
			$cgadmin .= "
				<option value='$rt[id]'>$rt[idname]</option>";
		}
		$cgadmin .= "
			</select>
			<br />
			<label for='location_thumb$imgid'>$admin_addscreenstxt[11]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'11a'})."\")' border='0' /></label>
			<input type='text' name='location_thumb[$imgid]' id='location_thumb$imgid' />
			<br />
			<label for='location$imgid'>$admin_addscreenstxt[7]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'7a'})."\")' border='0' /></label>
			<input type='text' name='location[$imgid]' id='location$imgid' />
			<br />
			<label for='title$pageid'>$admin_addscreenstxt[8]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'8a'})."\")' border='0' /></label>
			<input type='text' name='title[$imgid]' id='title$imgid' />
			<br />
			<label for='caption$pageid'>$admin_addscreenstxt[12]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($admin_addscreenstxt{'12a'})."\")' border='0' /></label>
			<input type='text' name='caption[$imgid]' id='caption$imgid' />
		</fieldset>
		</fieldset>";
	}
	$cgadmin .= "
		<input type='submit' name='savescreen' value='$admin_addscreenstxt[10]' class='submit' />
	</form>
	</div>";

}

# If the user has already gone through the screenshot editing form, then we need to update the page information #
elseif($_POST{'savescreen'} == $admin_ans[13]) {
	# We now need to update the information in our screenshots table #
	foreach($_POST['imgid'] as $item) {
		extract($_POST);
		$title = $title[$item];
		$caption = $caption[$item];
		$location = $location[$item];
		$location_thumb = $location_thumb[$item];
		$pageid = implode("|",$pageid[$item]);
		$sql_pull = mysql_query("SELECT * FROM `screenshots` WHERE `imgid` = $item",$link);
		while($rs = mysql_fetch_array($sql_pull)) {
			$oldlocation_thumb = $rs[location_thumb];
			$oldlocation = $rs[location];
			$oldtitle = $rs[title];
			$oldcaption = $rs[caption];
			$oldpageid = $rs[pageid];
		}

		$sql_update = "UPDATE `screenshots` SET `location_thumb` = '$location_thumb', `location` = '$location', `pageid` = '$pageid', `title` = '$title', `caption` = '$caption' WHERE `imgid` = $item LIMIT 1";
		if(!mysql_query($sql_update, $link)) {
			die("There was an error updating one or more of your screenshots.  Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error());
		}
		$cgadmin .= "<div>".removehtml($sql_update)."</div>";
	}
	$cgadmin .= "<div>The queries listed above were executed successfully.</div>";
}

# If the user has already been through the screenshot creation form, then we need to actually create the new screenshot(s) #
elseif($_POST{'savescreen'} == $admin_addscreenstxt[10]) {
	# We now need to build our queries and insert the information into our screenshots table #
	for($i=1;$i<=$_POST{'itemnum'};$i++) {
		extract($_POST);
		$title = $title[$i];
		$caption = $caption[$i];
		$location = $location[$i];
		$location_thumb = $location_thumb[$i];
		$pageid = implode("|",$pageid[$i]);
		$sql_insert = "INSERT INTO `screenshots` (`pageid`, `location_thumb`, `location`, `title`, `caption`) VALUES ('$pageid', '$location_thumb', '$location', '$title', '$caption')";
		if(!mysql_query($sql_insert, $link)) {
			die("There was an error inserting one or more of these screenshots.  Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error());
		}

		$cgadmin .= "<div>".removehtml($sql_insert)."</div>";
	}
	$cgadmin .= "<div>The queries listed above were executed successfully.</div>";
}

?>