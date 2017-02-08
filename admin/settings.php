<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# settings.php                                                  #
# This file contains the various PHP elements needed to set our #
# system-wide variables.                                        #
#################################################################

if($user->checkperms('pref',SETTINGS,CREATE)) {
	if(empty($_POST{'savesettings'})) {
		$cgadmin = "
		<form name='cgsettings' method='post' action='$cagcmsadmin?action=settings'>
		<fieldset>
			<h1>$admin_head[1]</h1>
				<label for='newsok'>$settings_txt[2]</label>";
		$cgadmin .= makeselect('newsok','');
		$cgadmin .= "
				<br />
				<label for='defaultid'>$settings_txt[3]</label>
				<select name='defaultid' id='defaultid'>";
		if(empty($defaultid) && empty($_POST{'defaultid'})) {
			$selectnone = " selected='selected'";
		}
		$cgadmin .= "
					<option value=''$selectnone>-- Please select a page --</option>";
		if($defaultid == 'news' || $_POST{'defaultid'} == 'news') {
			$selectnews = " selected='selected'";
		}
		else {
			$selectnews = "";
		}
		$cgadmin .= "
					<option value='news'$selectnews>$admin_ans[3]</option>";
		$sql_ids = mysql_query("SELECT * FROM `content`", $link);
		while($rs = mysql_fetch_array($sql_ids)) {
			if($defaultid == $rs[0] || $_POST{'defaultid'} == $rs[0]) {
				$select = " selected='selected'";
			}
			else {
				$select = "";
			}
			$cgadmin .= "
					<option value='$rs[3]'$select>$rs[5]</option>";
		}
		$cgadmin .= "
				</select>
				<br />
				<label for='iconok'>$settings_txt[11]</label>";
		$cgadmin .= makeselect('iconok','');
		$cgadmin .= "
				<br />
				<label for='regok'>$settings_txt[12]</label>";
		$cgadmin .= makeselect('regok','');
		$cgadmin .= "
				<br />
				<label for='contactok'>
					$settings_txt[4]
					<span class='note'>
						";
		$cgadmin .= $settings_txt{'4a'};
		$cgadmin .= "
					</span>
				</label>";
		$cgadmin .= makeselect('contactok','');
		$cgadmin .= "
				<br />
				<label for='searchok'>
					$settings_txt[5]
					<span class='note'>
						";
		$cgadmin .= $settings_txt{'5a'};
		$cgadmin .= "
					</span>
				</label>";
		$cgadmin .= makeselect('searchok','');
		$cgadmin .= "
				<br />
				<label for='searchcode'>
					".$settings_txt{'5b'}."
				</label>
				<textarea name='searchcode' id='searchcode'>";
		if(!empty($_POST{'searchcode'})) {
			$searchcode = removehtml($_POST{'searchcode'});
			$cgadmin .= $_POST{'searchcode'};
		}
		else {
			$searchcode = removehtml($searchcode);
			$cgadmin .= $searchcode;
		}
		$cgadmin .= "</textarea>
				<br />";
		$cgadmin .= "
				<label for='loginok'>$settings_txt[6]</label>";
		$cgadmin .= makeselect('loginok','');
		$cgadmin .= "
				<br />
				<label for='yabbok'>$settings_txt[8]</label>";
		$cgadmin .= makeselect('yabbok','');
		$cgadmin .= "
				<br />
				<label for='yabbpath'>$settings_txt[9]</label>
				<input type='text' name='yabbpath' id='yabbpath' value='$yabbpath' />
				<br />
				<label for='yabburl'>$settings_txt[10]</label>
				<input type='text' name='yabburl' id='yabburl' value='$yabburl' />
				<br />";
		if(!empty($_POST{'act_open'})) {
			$act_open = $_POST{'act_open'};
		}
		if(!empty($_POST{'act_close'})) {
			$act_close = $_POST{'act_close'};
		}
		$act_open = removehtml($act_open);
		$act_close = removehtml($act_close);
		$cgadmin .= "
				<fieldset>
					<legend>$settings_txt[7]</legend>
					<label for='act_open'>".$settings_txt{'7a'}."</label>
					<input type='text' name='act_open' id='act_open' value='$act_open' />
					<br />
					<label for='act_close'>".$settings_txt{'7b'}."</label>
					<input type='text' name='act_close' id='act_close' value='$act_close' />
				</fieldset>
				<label for='newscap'>$settings_txt[13]</label>
				<input type='text' name='newscap' id='newscap' value='$newscap' />
				<br />
				<label for='newsoff'>$settings_txt[14]</label>
				<input type='text' name='newsoff' id='newsoff' value='$newsoff' />
			</fieldset>";
		$cgadmin .= "
			<input type='submit' name='savesettings' value='$admin_ans[4]' class='submit' />
		</form>";
	}
	elseif($_POST{'savesettings'} == $admin_ans[4]) {
		# This is where we process and store these variables #
		# First, we must collect all of the information stored in our config.php file #
		$oldvars = storevars();
	
		# Now, we need to process all of the variables we defined in the form, and compare them to our old variables #
		$formels = array("defaultid","iconok","regok","newsok","contactok","searchok","searchcode","loginok","yabbok","act_open","act_close","newscap","newsoff");
		foreach($formels as $form_el) {
			if($oldvars[$form_el] != $_POST{$form_el}) {
				$$form_el = $_POST{$form_el};
			}
			else {
				$$form_el = $oldvars{$form_el};
			}
		}
	
		# We will now store the names of all of our variables into an array #
		$varnames = oldvars();
	
		# We will now build the content of our config.php file #
		$cgsettings = "<?php
	
	#################################################################
	# CAGCMS - CAG's Content Management System                      #
	# Developed by Curtiss Grymala for private use and for use by   #
	# supporters of open-source freeware.                           #
	#################################################################
	# Version 0.3 beta                                              #
	# Released: September 2, 2006                                   #
	#################################################################
	# config.php                                                    #
	# This is where all of our system variables are stored.         #
	#################################################################
	
	";
		$myConstants = array("db","dbuser","dbpass","host");
		foreach($varnames as $varname) {
			if(!empty($$varname)) {
				$k = $$varname;
				$k = stripslashes($k);
				if(in_array($varname,$myConstants)) {
					$cgsettings .= "
	define(\"".strtoupper($varname)."\",\"$k\");";
				}
				else {
					$cgsettings .= "
	\$$varname = \"$k\";";
				}
			}
			else {
				$k = $oldvars{$varname};
				$k = stripslashes($k);
				if(in_array($varname,$myConstants)) {
					$cgsettings .= "
	define(\"".strtoupper($varname)."\",\"$k\");";
				}
				else {
					$cgsettings .= "
	\$$varname = \"$k\";";
				}
			}
		}
	
		$cgsettings .= "
	
	?>";
	
		# We now will blank the config.php file and write our new settings #
		$handle = fopen($sourcepath."/qwerty/config.php",w);
		if(fwrite($handle, $cgsettings)) {
			chmod($sourcepath."/qwerty/config.php",0600);
			header("Location: $cagcmsadmin");
			$cgadmin = "The settings were saved successfully.";
		}
		else {
			$cgadmin = "There was an error saving your settings.  Please <a href='javascript:history.go(-1)'>go back</a> try again.";
		}
	}
}
else {
	$cgadmin = $error_txt[1];
}
?>