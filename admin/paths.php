<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# paths.php                                                     #
# This is the page we use to modify our path and database       #
# settings.                                                     #
#################################################################

if($user->checkperms('pref',PATHS,CREATE)) {
	if(empty($_POST{'savepath'})) {
	
	# We need to start off our page, and open our form #
		$cgadmin = "
		<form name='cgpaths' method='post' action='$cagcmsadmin?action=paths'>
		<fieldset>
			<h1>$admin_head[3]</h1>";
	# We have finished opening our page #
	
	# We will now begin our paths/url block #
		$path_array = array("sitename","cagcmsurl","adminpath","cagcmsadmin","imgdir","langpath","sourcepath","stylepath","templatepath","backuppath","tinymce","rooturl","webmaster_email");
		$i = 1;
		foreach($path_array as $path_item) {
			$cgadmin .= "		
				<label for='$path_item'>$paths_txt[$i]</label>";
			if(!empty($_POST{$path_item})) {
				$$path_item = $_POST{$path_item};
			}
			$$path_item = removehtml($$path_item);
			$k = $$path_item;
			$cgadmin .= "
					<input type='text' name='$path_item' id='$path_item' value='$k' class='text' />
					<br />";
			$i++;
		}
		$cgadmin .= "
			</fieldset>";
	# We have finished the paths/url block #
	
	# We will now begin the database settings block #
		# Because this section contains a password, we'll just go ahead and do all of the form elements individually, rather than setting up a loop #
		$cgadmin .= "
		<fieldset>
			<h1>$admin_head[4]</h1>";
		if(!empty($_POST{'db'})) {
			$db = $_POST{'db'};
		}
		$cgadmin .= "
				<label for='db'>$db_txt[1]</label>
				<input type='text' name='db' id='db' value='".DB."' class='text' />
				<br />";
		if(!empty($_POST{'dbuser'})) {
			$dbuser = $_POST{'dbuser'};
		}
		$cgadmin .= "
				<label for='dbuser'>$db_txt[2]</label>
				<input type='text' name='dbuser' id='dbuser' value='".DBUSER."' class='text' />
				<br />";
		if(!empty($_POST{'dbpass'})) {
			$dbpass = $_POST{'dbpass'};
		}
		$cgadmin .= "
				<label for='dbpass'>$db_txt[3]</label>
				<input type='password' name='dbpass' id='dbpass' value='".DBPASS."' class='text' />
				<br />";
		if(!empty($_POST{'host'})) {
			$host = $_POST{'host'};
		}
		$cgadmin .= "
				<label for='host'>$db_txt[4]</label>
				<input type='text' name='host' id='host' value='".HOST."' class='text' />
				<br />";
		$cgadmin .= "
			</fieldset>";
	# We have finished the database settings block #
	
	# We will now begin the cookie settings block, as long as the user hasn't chosen to use YaBB as their user-base #
		if($yabbok != 1) {
			$cgadmin .= "
			<fieldset>
				<h1>$admin_head[2]</h1>";
			$cookienames = array("cookieuser","cookiepass","cookielvl","cookiename","cookielength");
			foreach($cookienames as $cookietext) {
				if(!empty($_POST{$cookietext})) {
					$$cookietext = $cookietext;
				}
				$k = $$cookietext;
				$cgadmin .= "
				<label for='$cookietext'>$cookie_txt[$cookietext]</label>
				<input type='text' name='$cookietext' id='$cookietext' value='$k' class='text' />
				<br />";
			}
			$cgadmin .= "
			</fieldset>";
		}
	# We have finished the cookie settings block #
	
	# We now close up our page and add a submit button #
		$cgadmin .= "
			<input type='submit' name='savepath' value='$admin_ans[4]' class='submit' />
		</form>";
	# We have finished setting up our page #
	
	}
	elseif($_POST{'savepath'} == $admin_ans[4]) {
		# This is where we process and store our variables
		# First, we must collect all of the information stored in our config.php file #
		$oldvars = storevars();
	
		# Now, we need to process all of the variables we defined in the form, and compare them to our old variables #
		$formels = array("sitename","cagcmsurl","adminpath","cagcmsadmin","imgdir","langpath","sourcepath","stylepath","templatepath","backuppath","tinymce","rooturl","webmaster_email","db","dbuser","dbpass","host",
			"cookieuser","cookiepass","cookielvl","cookiename","cookielength");
		foreach($formels as $form_el) {
			if($oldvars[$form_el] != $_POST{$form_el}) {
				$$form_el = $_POST{$form_el};
			}
			else {
				$$form_el = $oldvars{$form_el};
			}
		}
	
		$varnames = oldvars();
	
		# We will now generate the content of our new config.php file #
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