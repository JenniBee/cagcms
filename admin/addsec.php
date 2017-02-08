<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# addsec.php                                                    #
# This is the page that contains the various elements needed to #
# add new sections to the site.  This is also the page where we #
# will modify/remove existing sections.                         #
#################################################################

$cgadmin = "
	<h1>$admin_head[5]</h1>";

# If the administrator has not chosen any actions from the initial form, then we generate the initial form #
if(empty($_POST{'savesec'})) {
	$cgadmin .= "
	<form name='addsec' method='post' action='$cagcmsadmin?action=addsec'>
	<fieldset>
		<p style='clear: both;'>$section_txt[1]</p>";
	$sql_query = mysql_query("SELECT * FROM `sections`", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
		<div class='checkbox'>
			<input type='checkbox' class='check' name='secid[]' id='section$rs[secid]' value='$rs[secid]' />
			<label for='section$rs[secid]'>$rs[secname]</label>
		</div>";
	}
	$cgadmin .= "
		<fieldset>
			<legend>$section_txt[2]</legend>
			<div class='checkbox'>
				<input type='radio' name='secaction' id='secactionedit' value='edit' checked='checked' />
				<label for='secactionedit'>$admin_ans[5]</label>
			</div>
			<div class='checkbox'>
				<input type='radio' name='secaction' id='secactionremove' value='remove' />
				<label for='secactionremove'>$admin_ans[6]</label>
			</div>
		</fieldset>
		<input type='submit' name='savesec' value='$admin_ans[7]' class='submit' />
	</fieldset>
	<fieldset>
		<label for='secnum'><strong>$section_txt[5]:</strong></label>
		<input type='text' name='secnum' value='1' style='text-align: right;width: 30px !important;' />
		<input type='submit' name='savesec' value='$section_txt[5]' class='submit' />
	</fieldset>
	</form>
";
}

# If the user chose to edit or remove existing sections, we perform the following actions #
elseif($_POST{'savesec'} == $admin_ans[7]) {
	# If they chose to edit existing sections, we need to generate the edit form #
	if($_POST{'secaction'} == "edit") {

		# We need to make sure that the TinyMCE editor is initiated #
		if(!empty($tinymce)) {
			$cghead = $tmceinit;
		}

		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

		# We now need to build our editor form #
		$cgadmin .= "
	<form name='secedit' method='post' action='$cagcmsadmin?action=addsec'>
		$section_txt[3]";

		# We need to pull the information for each section being edited from the database #
		foreach($_POST{'secid'} as $section) {
			$sql_edit = mysql_query("SELECT * FROM `sections` WHERE `secid` = '$section' LIMIT 1;", $link);
			while($rs = mysql_fetch_array($sql_edit)) {
				$secid = $rs[secid];
				$secdesc = htmlentities($rs[secdesc]);
			$cgclose .= "
<script type=\"text/javascript\" language=\"javascript\">
	var where = document.getElementById('seclink$secid');
	var what = document.createElement('img');
	what.id = 'lul$secid';
	what.src = '$imgdir/lock.png';
	what.style.cssFloat = 'left';
	what.onclick = function() {lul('seclink$secid')};
	where.parentNode.insertBefore(what,where.nextSibling);
	lul('seclink$secid');
</script>";
				$cgadmin .= "
		<input type='hidden' name='secid[$secid]' value='$secid' />
		<fieldset>
			<legend>$rs[secname]</legend>
			<label for='secname$secid'>$section_txt[6]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'6a'})."\")' border='0' /></label>
			<input type='text' name='secname[$secid]' id='secname$secid' value='$rs[secname]' onchange=\"genlink(this.id,'seclink$secid');\" />
			<br />
			<label for='seclink$secid'>$section_txt[7]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'7a'})."\")' border='0' /></label>
			<input type='text' name='seclink[$secid]' id='seclink$secid' value='$rs[seclink]' />
			<br />
			<label for='secicon$secid'>$section_txt[9]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'9a'})."\")' border='0' /></label>
			<input type='text' name='secicon[$secid]' id='secicon$secid' value='$rs[secicon]' />
			<br />
			<label for='secdesc$secid'>$section_txt[8]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'8a'})."\")' border='0' /></label>
			<br />
			<textarea name='secdesc[$secid]' id='secdesc$secid' class='mceta'>$secdesc</textarea>
			<br />
		</fieldset>";
			}
		}
		$cgadmin .= "
		<input type='submit' name='savesec' value='$admin_ans[13]' class='submit' />
	</form>";
	}
	# If the user chose to remove existing sections, then we simply need to remove them #
	elseif($_POST{'secaction'} == "remove") {
		foreach($_POST{'secid'} as $section) {
			$sql_retrieveid = mysql_query("SELECT `seclink` FROM `sections` WHERE `secid` = '$section' LIMIT 1", $link);
			while($row = mysql_fetch_array($sql_retrieveid)) {
				$seclink = $row[seclink];
			}
			$sql_removeid = "DELETE FROM `idtable` WHERE `id` = '$seclink' LIMIT 1";
			if(!mysql_query($sql_removeid, $link)) {
				die("There was an error removing one or more of the sections from the id table.".mysql_error());
			}
			$cgadmin .= "<div>$sql_removeid</div>";
			$sql_remove = "DELETE FROM `sections` WHERE `secid` = '$section' LIMIT 1";
			if(!mysql_query($sql_remove, $link)) {
				die("There was an error removing one or more of the sections.".mysql_error());
			}
			$cgadmin .= "<div>$sql_remove</div>";
		}
		$cgadmin .= "<div>The actions listed above were executed successfully.</div>";
	}
}

# If the user chose to create new sections, then we need to set up that form #
elseif($_POST{'savesec'} == $section_txt[5]) {

	# We need to make sure that the TinyMCE editor is initiated #
	if(!empty($tinymce)) {
		$cghead .= $tmceinit;
	}

		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

	# We will now build our section creation form. #
	$cgadmin .= "
	<form name='secedit' method='post' action='$cagcmsadmin?action=addsec'>
		$section_txt[4]
		<input type='hidden' name='secnum' value='$_POST[secnum]' />";

	# We will now generate the correct number of "new section" areas in the form #
	for($i=1;$i<=$_POST{'secnum'};$i++) {
			$cgclose .= "
<script type=\"text/javascript\" language=\"javascript\">
	var where = document.getElementById('seclink$i');
	var what = document.createElement('img');
	what.id = 'lul$i';
	what.src = '$imgdir/lock.png';
	what.style.cssFloat = 'left';
	what.onclick = function() {lul('seclink$i')};
	where.parentNode.insertBefore(what,where.nextSibling);
	lul('seclink$i');
</script>";
				$cgadmin .= "
		<fieldset>
			<label for='secname$i'>$section_txt[6]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'6a'})."\")' border='0' /></label>
			<input type='text' name='secname[$i]' id='secname$i' onchange=\"genlink(this.id,'seclink$i');\" />
			<br />
			<label for='seclink$i'>$section_txt[7]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'7a'})."\")' border='0' /></label>
			<input type='text' name='seclink[$i]' id='seclink$i' />
			<br />
			<label for='secicon$i'>$section_txt[9]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'9a'})."\")' border='0' /></label>
			<input type='text' name='secicon[$i]' id='secicon$i' />
			<br />
			<label for='secdesc$i'>$section_txt[8]<span class='reqd'>*</span><img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($section_txt{'8a'})."\")' border='0' /></label>
			<br />
			<textarea name='secdesc[$i]' id='secdesc$i' class='mceta'></textarea>
		</fieldset>";
	}
	$cgadmin .= "
		<input type='submit' name='savesec' value='$section_txt[10]' class='submit' />
	</form>";
	
}

# If the user has already gone through the section editing form, then we need to update the section information #
elseif($_POST{'savesec'} == $admin_ans[13]) {
	# We now need to update the information in our sections table #
	foreach($secid as $section) {
		$errorArr = array();
		extract($_POST);
		if(!empty($secname[$section])) {
			$secname = mysql_real_escape_string($secname[$section],$link);
		}
		else {
			array_push($errorArr,$section_txt[6]);
		}
		if(!empty($seclink[$section])) {
			$seclink = $seclink[$section];
			$seclink = preg_replace("/[^a-zA-z0-9]/i",'_',$seclink);
			$seclink = strtolower($seclink);
			$seclink = mysql_real_escape_string($seclink,$link);
		}
		else {
			array_push($errorArr,$section_txt[7]);
		}
		$secicon = $secicon[$section];
		if(!empty($secdesc[$section])) {
			$secdesc = mysql_real_escape_string(removequotes($secdesc[$section]),$link);
		}
		else {
			array_push($errorArr,$section_txt[8]);
		}
		$seclastedit = time();
		if(!empty($errorArr)) {
			$cgadmin .= "
<div class=\"dbmessage\">
	<p>There were errors trying to process the information you submitted.  Following is a list of the errors that occurred.</p>
	<ul>";
			foreach($errorArr as $val) {
				$cgadmin .= "<li>The required field $val was blank</li>";
			}
			$cgadmin .= "
	</ul>
	<p>Please go back and fill in the missing information before trying to submit this form.</p>
</div>";
		}
		else {
			$sql_pull = mysql_query("SELECT `seclink`, `secname` FROM `sections` WHERE `secid` = $section",$link);
			while($rs = mysql_fetch_array($sql_pull)) {
				$oldseclink = $rs[seclink];
				$oldsecname = $rs[secname];
			}
			if($oldseclink != $seclink || $oldsecname != $secname) {
				$sql_update = "UPDATE `idtable` SET `idtable`.`id` = '$seclink', `idtable`.`idtype` = 'sec', `idtable`.`idname` = '$secname' WHERE `id` = '$oldseclink';";
				$sql_update2 = "UPDATE `sections` SET `sections`.`secname` = '$secname', `sections`.`seclink` = '$seclink', `sections`.`secdesc` = '$secdesc', `sections`.`seclastedit` = $seclastedit, `sections`.`secicon` = '$secicon' WHERE `secid` = $section;";
			}
			else {
				$sql_update = "UPDATE `sections` SET `secname` = '$secname', `seclink` = '$seclink', `secdesc` = '$secdesc', `seclastedit` = $seclastedit, `secicon` = '$secicon' WHERE `secid` = $section LIMIT 1";
			}
			if(mysql_query($sql_update, $link)) {
				if($sql_update2) {
					if(mysql_query($sql_update2,$link)) {
						$inserted = true;
					}
					else {
						$cgadmin .= "<div class=\"dbmessage\">There was an error updating one or more of your sections.  Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".sql_update2."</div></div>";
						$inserted = false;
					}
				}
				else {
					$inserted = true;
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating one or more of your sections.  Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_update."</div></div>";
				$inserted = false;
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">$secname was saved successfully</div>";
			}
		}
	}
}

# If the user has already been through the section creation form, then we need to actually create the new section(s) #
elseif($_POST{'savesec'} == $section_txt[10]) {
	# We now need to build our queries and insert the information into our sections table #
	for($i=1;$i<=$_POST{'secnum'};$i++) {
		$errorArr = array();
		extract($_POST);
		if(!empty($secname[$i])) {
			$secname = mysql_real_escape_string($secname[$i],$link);
		}
		else {
			array_push($errorArr,$section_txt[6]);
		}
		if(!empty($seclink[$i])) {
			$seclink = $seclink[$i];
			$seclink = preg_replace("/[^a-zA-z0-9]/i",'_',$seclink);
			$seclink = strtolower($seclink);
			$seclink = mysql_real_escape_string($seclink,$link);
		}
		else {
			array_push($errorArr,$section_txt[7]);
		}
		$secicon = $secicon[$i];
		if(!empty($secdesc[$i])) {
			$secdesc = mysql_real_escape_string(removequotes($secdesc[$i]),$link);
		}
		else {
			array_push($errorArr,$section_txt[8]);
		}
		$sectimestamp = time();
		$seclastedit = time();
		if(!empty($errorArr)) {
			$cgadmin .= "
<div class=\"dbmessage\">
	<p>There were errors trying to process the information you submitted.  Following is a list of the errors that occurred.</p>
	<ul>";
			foreach($errorArr as $val) {
				$cgadmin .= "<li>The required field $val was blank</li>";
			}
			$cgadmin .= "
	</ul>
	<p>Please go back and fill in the missing information before trying to submit this form.</p>
</div>";
		}
		else {
			$sql_insert = "INSERT INTO `idtable` (`idtable`.`id`, `idtable`.`idtype`, `idtable`.`idname`) VALUES ('$seclink','sec','$secname');";
			if(mysql_query($sql_insert,$link)) {
				$sql_insert = "INSERT INTO `sections` (`sections`.`secname`, `sections`.`seclink`, `sections`.`secdesc`, `sections`.`sectimestamp`, `sections`.`seclastedit`, `sections`.`secicon`) VALUES('$secname','$seclink','$secdesc','$sectimestamp','$seclastedit','$secicon');";
				if(mysql_query($sql_insert,$link)) {
					$inserted = true;
				}
				else {
					$cgadmin .= "<div class=\"dbmessage\">There was an error inserting this new information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div>";
					$inserted = false;
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error inserting this new information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div>";
				$inserted = false;
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">$secname was saved successfully</div>";
			}
		}
	}
}

?>