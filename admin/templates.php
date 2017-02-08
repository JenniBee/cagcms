<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# templates.php                                                 #
# This is the page we use to modify our templates.              #
#################################################################

$cgadmin = "
	<h1>$admin_head[10]</h1>";

# We need to generate a form to allow the user to choose which template to edit. #
$cgadmin .= "
	<form name='templatechoose' action='$cagcmsadmin?action=templates' method='post'>
		<div class='lineitem'>
			<div class='label'>
				<label for='templateselect'>$template_txt[1]</label>
			</div>
			<div class='input'>
				<select name='templateselect' id='templateselect' onchange='document.templatechoose.submit();'>
					<option value=''>$template_txt[2]</option>";

# We will now open the templates directory and scan it, to get a list of the files.  Then, we generate a selectbox from that list of files. #
$handle = opendir($templatepath);
while($file=readdir($handle)) {
	if ($file != "." && $file != "..") {
		if(!empty($_POST[templateselect]) && $_POST[templateselect] == $file) {
			$selected = " selected='selected'";
		}
		else {
			$selected = "";
		}
		$cgadmin .= "
					<option value='$file'$selected>$file</option>";
	}
}
closedir($handle);

$cgadmin .= "
				</select>
			</div>
		</div>
	</form>";

# If the user selected a template already, then we need to generate the form to edit the template #
if(!empty($_POST[templateselect])) {

	# First, we open the file that the user chose, and store its contents to a variable #
	$handle = fopen($templatepath."/".$_POST{'templateselect'},"r");
	$file = fread($handle, filesize($templatepath."/".$_POST{'templateselect'}));
	fclose($handle);

	# We now need to clean up the contents of that file, getting rid of the CAGCMS header so it doesn't confuse the user, and storing that header somewhere else #
	$comments = preg_replace('/<!--((.|\s)*?)-->(.|\s)*/',"$1",$file);
	$comments = removehtml($comments);
	$file = preg_replace('/<!--((.|\s)*?)-->((.|\s)*?)/',"$2",$file);
	$file = preg_replace('/\n\n/','',$file);
	$file = removehtml($file);

	# We will now generate the template editor form #
	$cgadmin .= "
	<form name='templateedit' action='$cagcmsadmin?action=templates' method='post'>
		<div class='lineitem'>
			<div class='label' style='display: block;'>
				<label for='templateeditor'>$template_txt[3]</label>
			</div>
			<div class='input'>
				<input type='hidden' name='templateselected' value='$_POST[templateselect]' />
				<input type='hidden' name='comments' value='$comments' />
				<textarea name='templateeditor' id='templateeditor' style='font-family: monospace;width: 98%;height: 300px;'>$file</textarea>
			</div>
			<input type='submit' name='savetemplate' value='$template_txt[4]' />
		</div>
	</form>";
}

# If the user has chosen to save their template changes, then we need to implement those changes. #
elseif($_POST[savetemplate] == $template_txt[4]) {

	# First, we will open our old template, and read its contents into a variable #
	$handle = fopen($templatepath."/".$_POST[templateselected],"r");
	$file = fread($handle, filesize($templatepath."/".$_POST[templateselected]));
	fclose($handle);

	# Now, we will write the contents of our old template file into a backup copy of that file #
	$filename = "$backuppath/$_POST[templateselected]";
	$handle = fopen($filename,"w+");
	fwrite($handle, stripslashes($file)) or die("There was a problem writing the backup file");
	chmod($filename,0600);
	fclose($handle);

	# Next, we write the new changes that the user made into our original file. #
	$filename = "$templatepath/$_POST[templateselected]";
	$handle = fopen($filename,"w+");
	$templateeditor = addhtml($_POST[templateeditor]);
	$filecontent = "<!--\n$_POST[comments]\n-->\n\n$templateeditor";
	fwrite($handle, stripslashes($filecontent)) or die("There was a problem writing the new file");
	chmod($filename,0644);
	fclose($handle);

	# Finally, we let our user know that the template edit was saved and backed up successfully. #
	$cgadmin .= "
	<div>
		A backup of your old template file was created in your backup directory, and the new changes were 
		saved in your template directory.
	</div>";

}

# If something is screwed up in my code, and I can't figure out what it is, this will show up. #
else {
	$cgadmin .= "$_POST[templateselect]<br />$_POST[savetemplate]";
}	

?>