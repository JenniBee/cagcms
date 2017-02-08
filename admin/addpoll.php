<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# addpoll.php                                                   #
# This is the page that contains the various elements needed to #
# add new polls to the site.  This is also the page where we    # 
# will modify/remove existing polls.                            #
#################################################################

$cgadmin = "
	<h1>$admin_head[11]</h1>";

# If the administrator has not chosen any actions from the initial form, then we generate the initial form #
if(empty($_POST{'savepoll'})) {
	$cgadmin .= "
	<form name='addpoll' method='post' action='$cagcmsadmin?action=addpoll'>
	<div>
		$poll_txt[1]
		<div class='lineitem'>
			<select name='pollid[]' multiple='multiple' size='5'>";
	$sql_query = mysql_query("SELECT * FROM `polls`",$link);
	while($pollform = mysql_fetch_array($sql_query)) {
		$cgadmin .= "
				<option value='$pollform[pollid]'>$pollform[pollname]</option>";
	}
	$cgadmin .= "
			</select>
		</div>
		$poll_txt[2]
		<label class='radio'><input type='radio' name='pollaction' value='edit' checked='checked' /> $admin_ans[5]</label>
		<label class='radio'><input type='radio' name='pollaction' value='remove' /> $admin_ans[6]</label>
		<input type='submit' name='savepoll' value='$admin_ans[7]' class='submit' />
	</div>
	<div class='submit'>
		<label><strong>$poll_txt[5]:</strong> <input type='text' name='pollnum' value='1' class='submit' style='text-align: right;width: 30px !important;' /></label>
		<input type='submit' name='savepoll' value='".$poll_txt{'5a'}."' class='submit' />
	</div>
	</form>
";
}

# If the user chose to edit or remove existing polls, we perform the following actions #
elseif($_POST[savepoll] == $admin_ans[7]) {
	# If they chose to edit the existing poll, we need to generate the edit form #
	if($_POST[pollaction] = 'edit') {
		# We now need to build our editor form #
		$cgadmin .= "
	<div>
	<form name='polledit' method='post' action='$cagcmsadmin?action=addpoll'>
		$poll_txt[3]";

		# We need to pull the information for each page being edited from the database #
		foreach($_POST{'pollid'} as $poll) {
			$sql_edit = mysql_query("SELECT * FROM `polls` WHERE `pollid` = $poll",$link);
			while($pollinfo = mysql_fetch_array($sql_edit)) {
				$cgadmin .= "
		<input type='hidden' name='pollid[$poll]' value='$poll' />
		<h3>$pollinfo[pollname]</h3>
		<dl>
			<dt><label for='pollname_$poll'>$poll_txt[6]</label></dt>
			<dd>
				<input type='text' name='pollname[$poll]' value='".removehtml($pollinfo[pollname])."' id='pollname_$poll' />
			</dd>
			<dt><label for='pollquestion_$poll'>$poll_txt[7]</label></dt>
			<dd>
				<input type='text' name='pollquestion[$poll]' value='".removehtml($pollinfo[pollquestion])."' id='pollquestion_$poll' />
			</dd>
			<dt><label for='startdate_$poll'>$poll_txt[9]</label></dt>
			<dd>
				<input type='text' name='startdate[$poll]' value='$pollinfo[startdate]' id='startdate_$poll' />
			</dd>
			<dt><label for='enddate_$poll'>$poll_txt[10]</label></dt>
			<dd>
				<input type='text' name='enddate[$poll]' value='$pollinfo[enddate]' id='enddate_$poll' />
			</dd>";
				$sql_options = mysql_query("SELECT * FROM `polloptions` WHERE `pollid` = $poll",$link);
				$i = 0;
				while($polloptions = mysql_fetch_array($sql_options)) {
					$i++;
					$cgadmin .= "
			<dt><label for='option_".$polloptions[optionid]."'>$poll_txt[8] $i</label></dt>
			<dd>
				<input type='text' name='option[".$polloptions[optionid]."]' value='".removehtml($polloptions[option])."' id='option_".$polloptions[optionid]."' />
			</dd>";
				}

				# We now need to allow the user to choose on which pages the poll will appear #

				$cgadmin .= "
			<dt><label for='pollpages_$poll'>$poll_txt[11]</label></dt>
			<dd>
				<select name='pollpages[$poll][]' id='pollpages_$poll' multiple='multiple' size='5'>";
				$pollpages = explode("|",$pollinfo[pollpages]);
				$selected = "";
				foreach($pollpages as $pollpage) {
					if($pollpage == "allcagcmspages") {
						$selected = " selected='selected'";
					}
				}
				$cgadmin .= "
					<option value='allcagcmspages'$selected>All Site Pages</option>";
				$sql_pages = mysql_query("SELECT * FROM `idtable`",$link);
				while($pages = mysql_fetch_array($sql_pages)) {
					$selected = "";
					foreach($pollpages as $pollpage) {
						if($pollpage == $pages[id]) {
							$selected = " selected='selected'";
						}
					}
					$cgadmin .= "
					<option value='$pages[id]'$selected>$pages[idname]</option>";
				}
				$cgadmin .= "
				</select>
			</dd>
		</dl>";
			}
		}

		$cgadmin .= "
		<input type='submit' name='savepoll' value='$admin_ans[13]' />
	</form>
	</div>";
	}

	# If the user chose to remove the poll, we need to delete it. #

	elseif($_POST[pollaction] == 'remove') {
		$sql_remove = "DELETE FROM `polls` WHERE `pollid` = $_POST[pollid]";
		if(!mysql_query($sql_remove)) {
			die("There was an error removing your poll.<br />".mysql_error());
		}
		$sql_remove = "DELETE FROM `polloptions` WHERE `pollid` = $_POST[pollid]";
		if(!mysql_query($sql_remove)) {
			die("There was an error removing your poll.<br />".mysql_error());
		}
		$sql_remove = "DELETE FROM `pollvotes` WHERE `pollid` = $_POST[pollid]";
		if(!mysql_query($sql_remove)) {
			die("There was an error removing your poll.<br />".mysql_error());
		}
	}
}

# If the user chose to create a new poll, we need to generate that form #

elseif($_POST[savepoll] == $poll_txt{'5a'}) {

	# We will now build our page creation form. #
	$cgadmin = "
	<div>
	<form name='polledit' method='post' action='$cagcmsadmin?action=addpoll'>
		$page_txt[4]
		<input type='hidden' name='pollnum' value='$_POST[pollnum]' />
		<dl>
			<dt><label for='pollname'>$poll_txt[6]</label></dt>
			<dd>
				<input type='text' name='pollname' id='pollname' />
			</dd>
			<dt><label for='pollquestion'>$poll_txt[7]</label></dt>
			<dd>
				<input type='text' name='pollquestion' id='pollquestion' />
			</dd>
			<dt><label for='startdate'>$poll_txt[9]</label></dt>
			<dd>
				<input type='text' name='startdate' id='startdate' value='".date("Y-m-d H:i:s",time())."' />
			</dd>
			<dt><label for='enddate'>$poll_txt[10]</label></dt>
			<dd>
				<input type='text' name='enddate' id='enddate' value='";
	$enddate = date("Y",time());
	$enddate = $enddate + 1;
	$enddate = $enddate."-".date("m-d H:i:s",time());
	$cgadmin .= "$enddate' />
			</dd>";

	# We will now generate the correct number of poll options in the form #

	for($option=1;$option<=$_POST[pollnum];$option++) {
		$cgadmin .= "
			<dt><label for='option_$option'>$poll_txt[8] $option</label></dt>
			<dd>
				<input type='text' name='option[$option]' id='option_$option' />
			</dd>";
	}

	# We now need to allow the user to choose on which pages the poll will appear #
	$cgadmin .= "
			<dt><label for='pollpages'>$poll_txt[11]</label></dt>
			<dd>
				<select name='pollpages[]' id='pollpages' multiple='multiple' size='5'>
					<option value='allcagcmspages'>All Site Pages</option>";
	$sql_pages = mysql_query("SELECT * FROM `idtable`",$link);
	while($pollpages = mysql_fetch_array($sql_pages)) {
		$cgadmin .= "
					<option value='$pollpages[id]'>$pollpages[idname]</option>";
	}
	$cgadmin .= "
				</select>
			</dd>
		</dl>
		<input type='submit' name='savepoll' value='$admin_ans[9]' />
	</form>
	</div>";
}

# If the user has already gone through the page editing form, then we need to update the page information #
elseif($_POST{'savepoll'} == $admin_ans[13]) {
	# We now need to update the information in our content table #
}

# If the user has already been through the page creation form, then we need to actually create the new page(s) #
elseif($_POST{'savepoll'} == $admin_ans[9]) {
	# We now need to build our queries and insert the information into our content table #
	$pollpages = implode("|",$_POST[pollpages]);
	$sql_edit = "INSERT INTO `polls` (`pollname`,`pollquestion`,`startdate`,`enddate`,`pollpages`) VALUES('$_POST[pollname]','$_POST[pollquestion]','$_POST[startdate]','$_POST[enddate]','$pollpages')";
	if(mysql_query($sql_edit,$link)) {
		$sql_grab = mysql_query("SELECT `pollid` FROM `polls` WHERE `startdate` = '$_POST[startdate]'",$link);
		while($pollid = mysql_fetch_array($sql_grab)) {
			$option = $_POST[option];
			for($i=1;$i<=$_POST[pollnum];$i++) {
				$sql_insertoption = "INSERT INTO `polloptions` (`pollid`, `option`) VALUES ($pollid[pollid], '$option[$i]')";
				if(!mysql_query($sql_insertoption,$link)) {
					die("You fucked up".mysql_error()."<p>$sql_insertoption");
				}
			}
		}
	}
}