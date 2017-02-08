<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# addpage.php                                                   #
# This is the page that contains the various elements needed to #
# add new content pages to the site.  This is also the page     # 
# where we will modify/remove existing content pages.           #
#################################################################

$cgadmin = "
	<h1>$admin_head[7]</h1>";

# If the administrator has not chosen any actions from the initial form, then we generate the initial form #
if(empty($_POST{'savepage'})) {
	$cgadmin .= "
	<form name='addpage' method='post' action='$cagcmsadmin?action=addpage'>
		<p>
			$page_txt[1]
		</p>
		<fieldset>
			<select name='pageid[]' multiple='multiple' size='5'>";
		$sql_query = mysql_query("SELECT * FROM `content` ORDER BY linktitle", $link);
	while($rs = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
				<option value='$rs[pageid]'>$rs[linktitle]</option>";
	}
	$cgadmin .= "
			</select>
			<fieldset>
				<legend>$page_txt[2]</legend>
				<div class='checkbox'>
					<input type='radio' name='pageaction' id='pageactionedit' value='edit' checked='checked' />
					<label for='pageactionedit'>$admin_ans[5]</label>
				</div>
				<div class='checkbox'>
					<input type='radio' name='pageaction' id='pageactionremove' value='remove' />
					<label for='pageactionremove'>$admin_ans[6]</label>
				</div>
				<input type='submit' name='savepage' value='$admin_ans[7]' class='submit' />
			</fieldset>
		</fieldset>
		<fieldset>
			<label for='pagenum'><strong>$page_txt[5]:</strong></label>
			<input type='text' name='pagenum' value='1' style='text-align: right;width: 30px !important;' />
			<br />
			<input type='submit' name='savepage' value='$page_txt[5]' class='submit' />
		</fieldset>
	</form>
";
}

# If the user chose to edit or remove existing content, we perform the following actions #
elseif($_POST{'savepage'} == $admin_ans[7]) {
	# If they chose to edit existing content, we need to generate the edit form #
	if($_POST{'pageaction'} == "edit") {
		$cghead = "";
		# We need to make sure that the TinyMCE editor is initiated #
		if(!empty($tinymce)) {
			$cghead .= $tmceinit;
		}

		$cgclose = "";
		# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
		$cgclose .= "
	<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
	</script>";

		# We now need to build our editor form #
		$cgadmin .= "
	<form name='pageedit' method='post' action='$cagcmsadmin?action=addpage'>
		<input type='hidden' name='pageids' id='pageids' value='".implode(',',$_POST['pageid'])."' />
		$page_txt[3]";

		# We need to pull the information for each page being edited from the database #
		foreach($_POST{'pageid'} as $page) {
			$sql_edit = mysql_query("SELECT * FROM `content` WHERE `pageid` = '$page' LIMIT 1;", $link);
			while($rs = mysql_fetch_array($sql_edit)) {
				$pageid = $rs[pageid];
				$pagedesc = removehtml($rs[pagedesc]);
				$cgadmin .= "
		<input type='hidden' name='pageid[$pageid]' value='$pageid' />
		<fieldset>
			<legend>$rs[pagetitle]</legend>
			<label for='secid$pageid'>$section_txt[6]</label>
			<select name='secid[$pageid]' id='secid$pageid' onchange='popList(this.id,\"catid$pageid\"),\"".$rs['catid']."\";'>";
				# We now need to figure out which section this page belongs to #
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
	var where = document.getElementById('pagelink$pageid');
	var what = document.createElement('img');
	what.id = 'lul$pageid';
	what.src = '$imgdir/lock.png';
	what.style.cssFloat = 'left';
	what.onclick = function() {lul('pagelink$pageid')};
	where.parentNode.insertBefore(what,where.nextSibling);
	lul('pagelink$pageid');
	popList('secid$pageid','catid$pageid','".$rs['catid']."');
</script>";
				$cgadmin .= "
			</select>
			<br />
			<label for='catid$pageid'>$cat_txt[6]</label>
			<select name='catid[$pageid]' id='catid$pageid'>";
				# We now need to figure out which category this page belongs to #
				$sql_catid = mysql_query("SELECT * FROM `categories`",$link);
				while($rt = mysql_fetch_array($sql_catid)) {
					if($rt[catid] == $rs[catid]) {
						$selected = " selected='selected'";
						$cghead .= '<script type="text/javascript" language="javascript">var catid_'.$pageid.'='.$rt['catid'].';</script>';
					}
					else {
						$selected = "";
					}
					$cgadmin .= "
				<option value='$rt[catid]'$selected>$rt[catname]</option>";
				}
				$cgadmin .= "
			</select>
			<img src='$imgdir/linked.png' alt='Category and Section are linked' style='float: left; margin-top: -1.5em;' />
			<br />
			<label for='pagelink$pageid'>$page_txt[6]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'6a'})."\")' border='0' /></label>
			<input type='text' name='pagelink[$pageid]' id='pagelink$pageid' value='$rs[pagelink]' />
			<br />
			<label for='linktitle$pageid'>$page_txt[7]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'7a'})."\")' border='0' /></label>
			<input type='text' name='linktitle[$pageid]' id='linktitle$pageid' value='$rs[linktitle]' />
			<br />
			<label for='pagetitle$pageid'>$page_txt[8]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'8a'})."\")' border='0' /></label>
			<input type='text' name='pagetitle[$pageid]' id='pagetitle$pageid' value='$rs[pagetitle]' />
			<br />
			<label for='pageicon$pageid'>$page_txt[11]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'11a'})."\")' border='0' /></label>
			<input type='text' name='pageicon[$pageid]' id='pageicon$pageid' value='$rs[pageicon]' />
			<br />
			<label for='pagedesc$pageid'>$page_txt[9]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'9a'})."\")' border='0' /></label>
			<br />
			<textarea name='pagedesc[$pageid]' id='pagedesc$pageid' class='mceta'>".removehtml($rs[pagedesc])."</textarea>
			<br />
			<label for='pagecontent$pageid'>$page_txt[10]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'10a'})."\")' border='0' /></label>
			<br />
			<textarea name='pagecontent[$pageid]' id='pagecontent$pageid' class='mceta'>".removehtml($rs[pagecontent])."</textarea>
		</fieldset>";
			}
		}
		$cgadmin .= "
		<input type='submit' name='savepage' value='$admin_ans[13]' class='submit' />
	</form>";

	}

	# If the user chose to remove existing content, then we simply need to remove them #
	elseif($_POST{'pageaction'} == "remove") {
		foreach($_POST{'pageid'} as $page) {
			$sql_retrieveid = mysql_query("SELECT `pagelink`, `catid`, `secid` FROM `content` WHERE `pageid` = '$page' LIMIT 1", $link);
			while($row = mysql_fetch_array($sql_retrieveid)) {
				$pagelink = $row[pagelink];
				$catid = $row[catid];
				$secid = $row[secid];
			}
			$sql_remove = "DELETE FROM `content` WHERE `pageid` = $page LIMIT 1";
			if(mysql_query($sql_remove,$link)) {
				$sql_remove = "DELETE FROM `idtable` WHERE `id` = '$pagelink' LIMIT 1";
				if(mysql_query($sql_remove,$link)) {
					# We now need to update the number of content items within the appropriate category and section #
					mysql_query("UPDATE `categories` SET `catitems` = `catitems`-1 WHERE `catid` = '$catid'",$link);
					mysql_query("UPDATE `sections` SET `secitems` = `secitems`-1 WHERE `secid` = '$secid'",$link);			
					$inserted = true;
				}
				else {
					$cgadmin .= "<div class=\"dbmessage\">There was an error updating your information.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_remove."</div></div>";
					$inserted = false;
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating your information.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_remove."</div></div>";
				$inserted = false;
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">The item was removed successfully.</div>";
			}
		}
	}
}

# If the user chose to create a new page, then we need to build the page creation form #
elseif($_POST{'savepage'} == $page_txt[5]) {

	# We need to make sure that the TinyMCE editor is initiated #
	if(!empty($tinymce)) {
		$cghead = $tmceinit;
	}

	$cgclose = "";
	# We now need to include our tooltip javascript, so the user can see the tooltips associated with the form fields. #
	$cgclose .= "
<script language='javascript' type='text/javascript' src='$stylepath/wz_tooltip.js'>
</script>";

	# We will now build our page creation form. #
	$cgadmin = "
	<form name='pageedit' method='post' action='$cagcmsadmin?action=addpage'>
		$page_txt[4]
		<input type='hidden' name='pagenum' value='$_POST[pagenum]' />";

	# We will now generate the correct number of "new page" areas in the form #
	for($i=1;$i<=$_POST{'pagenum'};$i++) {
		$pageid = $i;
		$cgadmin .= "
		<fieldset>
			<label for='secid$pageid'>$section_txt[6]</label>
			<select name='secid[$pageid]' id='secid$pageid' onchange='popList(this.id,\"catid$pageid\",0);'>";
		# We now need to figure out which section this page belongs to #
		$sql_secid = mysql_query("SELECT * FROM `sections`",$link);
		while($rt = mysql_fetch_array($sql_secid)) {
			$cgadmin .= "
				<option value='$rt[secid]'>$rt[secname]</option>";
		}
			$cgclose .= "
<script type=\"text/javascript\" language=\"javascript\">
	var where = document.getElementById('pagelink$i');
	var what = document.createElement('img');
	what.id = 'lul$i';
	what.src = '$imgdir/lock.png';
	what.style.cssFloat = 'left';
	what.onclick = function() {lul('pagelink$i')};
	where.parentNode.insertBefore(what,where.nextSibling);
	lul('pagelink$i');
	popList('secid$pageid','catid$pageid',0);
</script>";
		$cgadmin .= "
			</select>
			<br />
			<label for='catid$pageid'>$cat_txt[6]</label>
			<select name='catid[$pageid]' id='catid$pageid'>";
		# We now need to figure out which category this page belongs to #
		$sql_catid = mysql_query("SELECT * FROM `categories`",$link);
		while($rt = mysql_fetch_array($sql_catid)) {
			$cgadmin .= "
				<option value='$rt[catid]'>$rt[catname]</option>";
		}
		$cgadmin .= "
			</select>
			<img src='$imgdir/linked.png' alt='Category and Section are linked' style='float: left; margin-top: -1.5em;' />
			<br />
			<label for='pagelink$pageid'>$page_txt[6]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'6a'})."\")' border='0' /></label>
			<input type='text' name='pagelink[$pageid]' id='pagelink$pageid' />
			<br />
			<label for='linktitle$pageid'>$page_txt[7]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'7a'})."\")' border='0' /></label>
			<input type='text' name='linktitle[$pageid]' id='linktitle$pageid' onchange=\"genlink(this.id,'pagelink$pageid');\" />
			<br />
			<label for='pagetitle$pageid'>$page_txt[8]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'8a'})."\")' border='0' /></label>
			<input type='text' name='pagetitle[$pageid]' id='pagetitle$pageid' />
			<br />
			<label for='pageicon$pageid'>$page_txt[11]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'11a'})."\")' border='0' /></label>
			<input type='text' name='pageicon[$pageid]' id='pageicon$pageid' />
			<br />
			<label for='pagedesc$pageid'>$page_txt[9]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'9a'})."\")' border='0' /></label>
			<br />
			<textarea name='pagedesc[$pageid]' id='pagedesc$pageid' class='mceta'></textarea>
			<br />
			<label for='pagecontent$pageid'>$page_txt[10]<img src='$imgdir/question.png' alt='?' onmouseover='return escape(\"".removequotes($page_txt{'10a'})."\")' border='0' /></label>
			<br />
			<textarea name='pagecontent[$pageid]' id='pagecontent$pageid' class='mceta'></textarea>
		</fieldset>";
	}
	$cgadmin .= "
		<input type='submit' name='savepage' value='$page_txt[12]' class='submit' />
	</form>";

}

# If the user has already gone through the page editing form, then we need to update the page information #
elseif($_POST{'savepage'} == $admin_ans[13]) {
	# We now need to update the information in our content table #
	$pageid = explode(',',$_POST['pageids']);
	if(get_magic_quotes_gpc()) {
		$_POST = stripslashes_deep($_POST);
	}
	foreach($pageid as $page) {
		$errorArr = array();
		$mytext = array('secid'=>$section_txt[6],'catid'=>$cat_txt[6],'pagelink'=>$page_txt[6],'linktitle'=>$page_txt[7],'pagetitle'=>$page_txt[8],'pagedesc'=>$page_txt[9]);
		if(get_magic_quotes_gpc()) {
			$_POST = stripslashes_deep($_POST);
		}
		extract($_POST);
		$pagetitle = mysql_real_escape_string($pagetitle[$page], $link);
		$pagelink = mysql_real_escape_string($pagelink[$page], $link);
		$pagedesc = mysql_real_escape_string(checklinks($pagedesc[$page]),$link);
		$pagecontent = mysql_real_escape_string(checklinks($pagecontent[$page]),$link);
		$secid = mysql_real_escape_string($secid[$page],$link);
		$catid = mysql_real_escape_string($catid[$page],$link);
		$linktitle = mysql_real_escape_string($linktitle[$page],$link);
		$pagelastedit = time();
		foreach($mytext as $key=>$val) {
			if(empty($$key)) {
				array_push($errorArr,$val);
			}
		}
		if(!empty($pagelink)) {
			$pagelink = preg_replace("/[^a-zA-z0-9]/i",'_',$pagelink);
			$pagelink = strtolower($pagelink);
			$pagelink = mysql_real_escape_string($pagelink,$link);
		}
		if(!empty($errorArr)) {
			$cgadmin .= "<div class=\"dbmessage\">There was an error processing this request.  Following is a list of the errors that occurred:</div><ul>";
			foreach($errorArr as $myerror) {
				$cgadmin .= "<li>$myerror</li>";
			}
			$cgadmin .= "<div class=\"dbmessage\">Please go back and try again.</div>";
		}
		else {
			$sql_pull = mysql_query("SELECT `pagelink`, `pagetitle`, `secid`, `catid` FROM `content` WHERE `pageid` = $page",$link);
			while($rs = mysql_fetch_array($sql_pull)) {
				$oldpagelink = $rs[pagelink];
				$oldpagetitle = $rs[pagetitle];
				$oldcatid = $rs[catid];
				$oldsecid = $rs[secid];
			}
			if($oldpagelink != $pagelink || $oldpagetitle != $pagetitle) {
				$sql_update = "UPDATE `idtable` SET `id` = '$pagelink', `idtype` = 'page', `idname` = '$pagetitle' WHERE `id` = '$oldpagelink'";
				$sql_update2 = "UPDATE `content` SET `catid` = '$catid', `secid` = '$secid', `pagelink` = '$pagelink', `linktitle` = '$linktitle', `pagetitle` = '$pagetitle', `pagedesc` = '$pagedesc', `pagecontent` = '$pagecontent', `contentlastedit` = $pagelastedit, `pageicon` = '".mysql_real_escape_string($pageicon[$page],$link)."' WHERE `pageid` = $page LIMIT 1";
			}
			else {
				$sql_update = "UPDATE `content` SET `catid` = '$catid', `secid` = '$secid', `pagelink` = '$pagelink', `linktitle` = '$linktitle', `pagetitle` = '$pagetitle', `pagedesc` = '$pagedesc', `pagecontent` = '$pagecontent', `contentlastedit` = $pagelastedit, `pageicon` = '".mysql_real_escape_string($pageicon[$page],$link)."' WHERE `pageid` = $page LIMIT 1";
			}
			if(mysql_query($sql_update,$link)) {
				if($sql_update2) {
					if(mysql_query($sql_update2,$link)) {
						$inserted = true;
					}
					else {
						$cgadmin .= "<div class=\"dbmessage\">There was an error updating your information.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_update2."</div></div>";
						$inserted = false;
					}
				}
				else {
					$inserted = true;
				}
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating your information.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_update."</div></div>";
				$inserted = false;
			}
			if($inserted) {
				# We now need to update the number of content items within the appropriate categories and sections.
				if($secid != $oldsecid) {
					mysql_query("UPDATE `sections` SET `secitems` = `secitems`-1 WHERE `secid` = '$oldsecid' LIMIT 1", $link);
					mysql_query("UPDATE `sections` SET `secitems` = `secitems`+1 WHERE `secid` = '$secid' LIMIT 1",$link);
				}
				if($catid != $oldcatid) {
					mysql_query("UPDATE `categories` SET `catitems` = `catitems`-1 WHERE `catid` = '$oldcatid' LIMIT 1", $link);
					mysql_query("UPDATE `categories` SET `catitems` = `catitems`+1 WHERE `catid` = '$catid' LIMIT 1",$link);
				}
				$cgadmin .= "<div class=\"dbmessage\">$pagetitle was updated successfully.</div>";
			}
		}
	}
}

# If the user has already been through the page creation form, then we need to actually create the new page(s) #
elseif($_POST{'savepage'} == $page_txt[12]) {
	# We now need to build our queries and insert the information into our content table #
	if(get_magic_quotes_gpc()) {
		$_POST = stripslashes_deep($_POST);
	}
	for($i=1;$i<=$_POST{'pagenum'};$i++) {
		$page = $i;
		$errorArr = array();
		$mytext = array('secid'=>$section_txt[6],'catid'=>$cat_txt[6],'pagelink'=>$page_txt[6],'linktitle'=>$page_txt[7],'pagetitle'=>$page_txt[8],'pagedesc'=>$page_txt[9]);
		extract($_POST);
		$pagetitle = mysql_real_escape_string($pagetitle[$page], $link);
		$pagelink = mysql_real_escape_string($pagelink[$page], $link);
		$pagedesc = mysql_real_escape_string(checklinks($pagedesc[$page]),$link);
		$pagecontent = mysql_real_escape_string(checklinks($pagecontent[$page]),$link);
		$secid = mysql_real_escape_string($secid[$page],$link);
		$catid = mysql_real_escape_string($catid[$page],$link);
		$linktitle = mysql_real_escape_string($linktitle[$page],$link);
		$pagetimestamp = time();
		foreach($mytext as $key=>$val) {
			if(empty($$key)) {
				array_push($errorArr,$val);
			}
		}
		if(!empty($pagelink)) {
			$pagelink = preg_replace("/[^a-zA-z0-9]/i",'_',$pagelink);
			$pagelink = strtolower($pagelink);
			$pagelink = mysql_real_escape_string($pagelink,$link);
		}
		if(!empty($errorArr)) {
			$cgadmin .= "<div class=\"dbmessage\">There was an error processing this request.  Following is a list of the errors that occurred:</div><ul>";
			foreach($errorArr as $myerror) {
				$cgadmin .= "<li>$myerror</li>";
			}
			$cgadmin .= "<div class=\"dbmessage\">Please go back and try again.</div>";
		}
		else {
			$sql_insert = "INSERT INTO `idtable` (`id`, `idtype`, `idname`) VALUES ('$pagelink','page','$pagetitle')";
			if(mysql_query($sql_insert,$link)) {
				$sql_insert = "INSERT INTO `content` (`catid`, `secid`, `pagelink`, `linktitle`, `pagetitle`, `pagedesc`, `pagecontent`, `contenttime`, `contentlastedit`, `pageicon`) VALUES ('$catid', '$secid', '$pagelink', '$linktitle', '$pagetitle', '$pagedesc', '$pagecontent', '$pagetimestamp', '$pagetimestamp', '".mysql_real_escape_string($pageicon[$i],$link)."')";
				if(mysql_query($sql_insert, $link)) {
					$inserted = true;
				}
				else {
					$cgadmin .= "<div class=\"dbmessage\">There was an error updating your information.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_insert."</div></div>";
					$inserted = false;
				}
		
				# We will now update the number of content items within this category and section #
				mysql_query("UPDATE `sections` SET `secitems` = `secitems`+1 WHERE `secid` = '$secid' LIMIT 1",$link);
				mysql_query("UPDATE `categories` SET `catitems` = `catitems`+1 WHERE `catid` = '$catid' LIMIT 1",$link);
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating your information.<br />".mysql_error()."</div><div>Following is the query that you attempted to execute:<div>".$sql_insert."</div></div>";
				$inserted = false;
			}
			if($inserted) {
				$cgadmin .= "<div class=\"dbmessage\">$pagetitle was updated successfully.</div>";
			}
		}
	}
}

$sql_sec = mysql_query("SELECT `secid`, `secname` FROM `sections`",$link);
$sql_cat = mysql_query("SELECT `catid`, `catname`, `secid` FROM `categories` ORDER BY `secid`",$link);
$secs = array();
$cats = array();
while($secids = mysql_fetch_array($sql_sec)) {
	$secs[$secids['secid']] = $secids['secname'];
}

while($catids = mysql_fetch_array($sql_cat)) {
	$cats[$catids['secid']][$catids['catid']]=$catids['catname'];
}
echo "<!--\n";
var_dump($cats);
echo "\n-->";

$cghead .= "
<script type=\"text/javascript\" language=\"javascript\">
	var cats = Array();
	var secs = Array();";
	
foreach($cats as $key=>$val) {
	$cghead .= "
	cats[$key] = Array();";
	foreach($val as $key2=>$val2) {
		$cghead .= "
	cats[$key][$key2] = '$val2';";
	}
}
foreach($secs as $key=>$val) {
	$cghead .= "
	secs[$key] = '$val';";
}

$cghead .= '
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == \'object\') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == \'object\') { //If it is an array,
				dumped_text += level_padding + "\'" + item + "\' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "\'" + item + "\' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
function popList(what,where,catid) {
	what = document.getElementById(what);
	var whatval = what.options[what.selectedIndex].value;
	where = document.getElementById(where);
	while(where.options.length!=0) {
		where.removeChild(where.options[0]);
	}
	if(cats[whatval]) {
		a = cats[whatval];
		for(k in a) {
			o = document.createElement("option");
			o.value = k;
			o.appendChild(document.createTextNode(a[k]));
			where.appendChild(o);
		}
		where.disabled = false;
	}
	else {
		o = document.createElement("option");
		o.value = "";
		o.appendChild(document.createTextNode("-- You need to create a cat in this sec --"));
		where.appendChild(o);
		where.disabled = "disabled";
	}
	if(catid != 0) {
		for(k=0;k<where.options.length;k++) {
			if(catid == where.options[k].value) {
				where.selectedIndex = k;
			}
		}
	}

}
</script>';

?>