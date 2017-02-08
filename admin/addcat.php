<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# addcat.php                                                    #
# This is the page that contains the various elements needed to #
# add new categories to the site.  This is also the page where  #
# we will modify/remove existing categories.                    #
#################################################################

$cgadmin = "
	<h1>$admin_head[6]</h1>";

# If the administrator has not chosen any actions from the initial form, then we generate the initial form #
if(empty($_POST{'savecat'})) {
	$cgadmin .= "
	<form name='addcat' method='post' action='$cagcmsadmin?action=addcat'>
		$cat_txt[1]
		<fieldset>";
	$sql_query = mysql_query("SELECT * FROM `categories`", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
			<div class='checkbox'>
				<input type='checkbox' class='check' name='catid[]' id='cat$rs[catid]' value='$rs[catid]' />
				<label for='cat$rs[0]'>$rs[catname]</label>
			</div>";
	}
	$cgadmin .= "
			<fieldset>
				<legend>$cat_txt[2]</legend>
				<div class='checkbox'>
					<input type='radio' name='cataction' id='catactionedit' value='edit' checked='checked' />
					<label for='catactionedit'>$admin_ans[5]</label>
				</div>
				<div class='checkbox'>
					<input type='radio' name='cataction' id='catactionremove' value='remove' />
					<label for='catactionremove'>$admin_ans[6]</label>
				</div>
				<input type='submit' name='savecat' value='$admin_ans[7]' class='submit' />
			</fieldset>
		</fieldset>
		<fieldset>
			<label for='catnum'><strong>$cat_txt[5]:</strong></label>
			<input type='text' name='catnum' id='catnum' value='1' class='submit' style='text-align: right;width: 30px !important;' />
			<input type='submit' name='savecat' value='$cat_txt[5]' class='submit' />
		</fieldset>
	</form>
";
}

# If the user chose to edit or remove existing categories, we perform the following actions #
elseif($_POST{'savecat'} == $admin_ans[7]) {
	# If they chose to edit existing categories, we need to generate the edit form #
	if($_POST{'cataction'} == "edit") {
		# We need to make sure that the TinyMCE editor is initiated #
		if(!empty($tinymce)) {
			$cghead = $tmceinit;
		}

		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'></script>";

		# We now need to build our editor form #
		$cgadmin .= "
	<form name='catedit' method='post' action='$cagcmsadmin?action=addcat'>
		$cat_txt[3]";

		# We need to pull the information for each category being edited from the database #
		foreach($_POST{'catid'} as $category) {
			$sql_edit = mysql_query("SELECT * FROM `categories` WHERE `catid` = '$category' LIMIT 1;", $link);
			while($rs = mysql_fetch_array($sql_edit)) {
				$catid = $rs[catid];
				$catdesc = removequotes($rs[catdesc]);
				$cgadmin .= "
		<input type='hidden' name='catid[$catid]' value='$catid' />
		<fieldset>
			<legend>$rs[catname]</legend>
			<label for='secid$catid'>$section_txt[6]<span class='reqd'>*</span></label>
			<select name='secid[$catid]' id='secid$catid'>";
			# We now need to figure out which section this category belongs to #
			$sql_secid = mysql_query("SELECT * FROM `sections`",$link);
			while($rt = mysql_fetch_array($sql_secid)) {
				if($rt[secid] == $rs[secid]) {
					$selected = " selected='selected'";
				}
				else {
					$selected = "";
				}
				$cgadmin .= "
				<option value='$rt[secid]'$selected>$rt[secname]</option>";
			}
			$cgclose .= "
<script type=\"text/javascript\" language=\"javascript\">
	var where = document.getElementById('catlink$catid');
	var what = document.createElement('img');
	what.id = 'lul$catid';
	what.src = '$imgdir/lock.png';
	what.style.cssFloat = 'left';
	what.onclick = function() {lul('catlink$catid')};
	where.parentNode.insertBefore(what,where.nextSibling);
	lul('catlink$catid');
</script>";
			$cgadmin .= "
			</select>
			<br />
			<label for='catname$catid'>$cat_txt[6]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'6a'})."\")' border='0' /></label>
			<input type='text' name='catname[$catid]' id='catname$catid' value='$rs[catname]' />
			<br />
			<label for='catlink$catid'>$cat_txt[7]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'7a'})."\")' border='0' /></label>
			<input type='text' name='catlink[$catid]' id='catlink$catid' value='$rs[catlink]' />
			<br />
			<label for='caticon$catid'>$cat_txt[9]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'9a'})."\")' border='0' /></label>
			<input type='text' name='caticon[$catid]' id='caticon$catid' value='$rs[caticon]' />
			<br />
			<label for='catdesc$catid'>$cat_txt[8]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'8a'})."\")' border='0' /></label>
			<br />
			<textarea name='catdesc[$catid]' id='catdesc$catid' class='mceta'>$catdesc</textarea>
		</fieldset>";
			}
		}
		$cgadmin .= "
		<input type='submit' name='savecat' value='$admin_ans[13]' class='submit' />
	</form>";

	}

	# If the user chose to remove existing categories, then we simply need to remove them #
	elseif($_POST{'cataction'} == "remove") {
		foreach($_POST{'catid'} as $category) {
			$sql_retrieveid = mysql_query("SELECT `catlink` FROM `categories` WHERE `catid` = '$category' LIMIT 1", $link);
			while($row = mysql_fetch_array($sql_retrieveid)) {
				$catlink = $row[catlink];
			}
			$sql_remove = "DELETE FROM `categories` WHERE `catid` = $category LIMIT 1;";
			$sql_removeid = "DELETE FROM `idtable` WHERE `id` = '$catlink' LIMIT 1";
			if(mysql_query($sql_remove, $link)) {
				if(mysql_query($sql_removeid, $link)) {
					$inserted = true;
				}
				else {
					$cgadmin .= "<div class=\"dbmessage\">There was an error removing one or more of the categories from the id table.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_removeid."</div></div>";
					$inserted = false;
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error removing one or more of the categories from the id table.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_remove."</div></div>";
				$inserted = false;
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">The category $catname was removed successfully.</div>";
			}
		}
	}
}

# If the user chose to create a new category, then we need to build the category creation form #
elseif($_POST{'savecat'} == $cat_txt[5]) {

	# We need to make sure that the TinyMCE editor is initiated #
	if(!empty($tinymce)) {
		$cghead = $tmceinit;
	}

		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'></script>";

	# We will now build our category creation form. #
	$cgadmin .= "
	<form name='catedit' method='post' action='$cagcmsadmin?action=addcat'>
		$cat_txt[4]
		<input type='hidden' name='catnum' value='$_POST[catnum]' />";

	# We will now generate the correct number of "new category" areas in the form #
	for($i=1;$i<=$_POST{'catnum'};$i++) {
				$cgadmin .= "
		<fieldset>
			<label for='secid$i'>$section_txt[6]<span class='reqd'>*</span></label>
			<select name='secid[$i]' id='secid$i'>";
			# We now need to figure out which section this category belongs to #
			$sql_secid = mysql_query("SELECT * FROM `sections`",$link);
			while($rt = mysql_fetch_array($sql_secid)) {
				$cgadmin .= "
				<option value='$rt[secid]'>$rt[secname]</option>";
			}
			$cgclose .= "
<script type=\"text/javascript\" language=\"javascript\">
	var where = document.getElementById('catlink$i');
	var what = document.createElement('img');
	what.id = 'lul$i';
	what.src = '$imgdir/lock.png';
	what.style.cssFloat = 'left';
	what.onclick = function() {lul('catlink$i')};
	where.parentNode.insertBefore(what,where.nextSibling);
	lul('catlink$i');
</script>";
			$cgadmin .= "
			</select>
			<br />
			<label for='catname$i'>$cat_txt[6]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'6a'})."\")' border='0' /></label>
			<input type='text' name='catname[$i]' id='catname$i' onchange=\"genlink(this.id,'catlink$i');\" />
			<br />
			<label for='catlink$i'>$cat_txt[7]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'7a'})."\")' border='0' /></label>
			<input type='text' name='catlink[$i]' id='catlink$i' />
			<br />
			<label for='caticon$i'>$cat_txt[9]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'9a'})."\")' border='0' /></label>
			<input type='text' name='caticon[$i]' id='caticon$i' />
			<br />
			<label for='catdesc$i'>$cat_txt[8]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($cat_txt{'8a'})."\")' border='0' /></label>
			<br />
			<textarea name='catdesc[$i]' id='catdesc$i' class='mceta'></textarea>
		</fieldset>";
	}
	$cgadmin .= "
		<input type='submit' name='savecat' value='$cat_txt[10]' class='submit' />
	</form>";

}

# If the user has already gone through the category editing form, then we need to update the category information #
elseif($_POST{'savecat'} == $admin_ans[13]) {
	# We now need to update the information in our categories table #
	if(get_magic_quotes_gpc()) {
		$_POST = stripslashes_deep($_POST);
	}
	foreach($_POST['catid'] as $category) {
		$errorArr = array();
		extract($_POST);
		if(!empty($catname[$category])) {
			$catname = mysql_real_escape_string($catname[$category],$link);
		}
		else {
			array_push($errorArr,$cat_txt[6]);
		}
		if(!empty($catlink[$category])) {
			$catlink = $catlink[$category];
			$catlink = preg_replace("/[^a-zA-z0-9]/i",'_',$catlink);
			$catlink = strtolower($catlink);
			$catlink = mysql_real_escape_string($catlink,$link);
		}
		else {
			array_push($errorArr,$cat_txt[7]);
		}
		$caticon = mysql_real_escape_string($caticon[$category],$link);
		if(!empty($catdesc[$category])) {
			$catdesc = mysql_real_escape_string($catdesc[$category],$link);
		}
		else {
			array_push($errorArr,$cat_txt[8]);
		}
		if(!empty($secid[$category])) {
			$secid = mysql_real_escape_string($secid[$category],$link);
		}
		else {
			array_push($errorArr,$section_txt[6]);
		}
		$catlastedit = time();
		if(!empty($errorArr)) {
			$cgadmin .= "<div class=\"dbmessage\">There was an error processing this request.  Following is a list of the errors that occurred:</div><ul>";
			foreach($errorArr as $myerror) {
				$cgadmin .= "<li>$myerror</li>";
			}
			$cgadmin .= "<div class=\"dbmessage\">Please go back and try again.</div>";
		}
		else {
			$sql_pull = mysql_query("SELECT `catlink`, `catname` FROM `categories` WHERE `catid` = $category",$link);
			while($rs = mysql_fetch_array($sql_pull)) {
				$oldcatlink = $rs[catlink];
				$oldcatname = $rs[catname];
			}
			if($oldcatlink != $catlink || $oldcatname != $catname) {
				$sql_update = "UPDATE `idtable` SET `id` = '$catlink', `idtype` = 'cat', `idname` = '$catname' WHERE `id` = '$oldcatlink'";
				$sql_update2 = "UPDATE `categories` SET `catname` = '$catname', `secid` = '$secid', `catlink` = '$catlink', `catdesc` = '$catdesc', `catlastedit` = $catlastedit, `caticon` = '$caticon' WHERE `catid` = $category LIMIT 1";
			}
			else {
				$sql_update = "UPDATE `categories` SET `catname` = '$catname', `secid` = '$secid', `catlink` = '$catlink', `catdesc` = '$catdesc', `catlastedit` = $catlastedit, `caticon` = '$caticon' WHERE `catid` = $category LIMIT 1";
			}
			if(mysql_query($sql_update, $link)) {
				if($sql_update2) {
					if(mysql_query($sql_update2,$link)) {
						$inserted = true;
					}
					else {
						$inserted = false;
						$cgadmin .= "<div class=\"dbmessage\">There was an error updating this information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_update2."</div></div>";
					}
				}
				else {
					$inserted=true;
				}
			}
			else {
				$inserted = false;
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating this information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_update."</div></div>";
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">$catname was updated successfully.</div>";
			}
		}
	}
}

# If the user has already been through the category creation form, then we need to actually create the new category(s) #
elseif($_POST{'savecat'} == $cat_txt[10]) {
	# We now need to build our queries and insert the information into our categories table #
	if(get_magic_quotes_gpc()) {
		$_POST = stripslashes_deep($_POST);
	}
	for($i=1;$i<=$_POST{'catnum'};$i++) {
		$errorArr = array();
		extract($_POST);
		$catname = $catname[$i];
		$catlink = $catlink[$i];
		$caticon = $caticon[$i];
		$secid = $secid[$i];
		$catdesc = $catdesc[$i];
		if(!empty($catname)) {
			$catname = mysql_real_escape_string($catname,$link);
		}
		else {
			array_push($errorArr,$cat_txt[6]);
		}
		if(!empty($catlink)) {
			$catlink = preg_replace("/[^a-zA-z0-9]/i",'_',$catlink);
			$catlink = strtolower($catlink);
			$catlink = mysql_real_escape_string($catlink,$link);
		}
		else {
			array_push($errorArr,$cat_txt[7]);
		}
		if(!empty($catdesc)) {
			$catdesc = mysql_real_escape_string($catdesc,$link);
		}
		else {
			array_push($errorArr,$cat_txt[8]);
		}
		if(!empty($secid)) {
			$secid = mysql_real_escape_string($secid,$link);
		}
		else {
			array_push($errorArr,$section_txt[6]);
		}
		$cattimestamp = time();
		$catlastedit = time();
		$sql_insert = "INSERT INTO `idtable` (`id`, `idtype`, `idname`) VALUES ('$catlink','cat','$catname')";
		if(!empty($errorArr)) {
			$cgadmin .= "<div class=\"dbmessage\">There was an error processing this request.  Following is a list of the errors that occurred:</div><ul>";
			foreach($errorArr as $myerror) {
				$cgadmin .= "<li>$myerror</li>";
			}
			$cgadmin .= "<div class=\"dbmessage\">Please go back and try again.</div>";
		}
		else {
			if(mysql_query($sql_insert,$link)) {
				$sql_insert = "INSERT INTO `categories` (`catname`, `catlink`, `secid`, `catdesc`, `cattimestamp`, `catlastedit`, `caticon`) VALUES ('$catname','$catlink','$secid','$catdesc','$cattimestamp','$catlastedit','$caticon')";
				if(mysql_query($sql_insert,$link)) {
					$inserted = true;
				}
				else {
					$inserted = false;
					$cgadmin .= "<div class=\"dbmessage\">There was an error inserting this new information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_insert."</div></div>";
				}
			}
			else {
				$inserted = false;
				$cgadmin .= "<div class=\"dbmessage\">There was an error inserting this new information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_insert."</div></div>";
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">$catname was updated successfully.</div>";
			}
		}
	}
}

?>