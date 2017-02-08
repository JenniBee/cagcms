<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# admin.php                                                     #
# This is the main administration file.                         #
#################################################################

require_once('../sources/qwerty/config.php');
require_once($sourcepath.'/admin_functions.php');
require_once("$langpath/adminlng.php");
require_once($sourcepath.'/loginfunctions.php');

$link = mysql_connect(HOST,DBUSER,DBPASS);
mysql_select_db(DB, $link);

$returnto = $_SERVER['PHP_SELF'];
if(!empty($_GET)) {
	$returnto .= "?";
	$returntoarr = array();
	foreach($_GET as $key=>$value) {
		array_push($returntoarr,$key."=".$value);
	}
	$returnto = $returnto.implode("&amp;",$returntoarr);
}

if($yabbok && $_COOKIE[$cookieusername] && $_COOKIE[$cookiepassword]) {
	$tempuser = array('username'=>$_COOKIE[$cookieusername],'userpass'=>$_COOKIE[$cookiepassword]);
	if(!empty($tempuser[userpass]) && !empty($tempuser['username'])) {
		$filename = $memberdir.'/'.$tempuser['username'].'.vars';
		$uservars = getyabbuservars($filename);
		$user = new user(array(0,$tempuser['username'],$uservars['password'],$uservars['email'],$uservars['realname'],$uservars['position'],$uservars['lastips'],$uservars['regdate'],$uservars['lastonline'],$uservars['lastpost'],0));
	}
}
elseif(is_array($_COOKIE[$cookieuser])) {
	$tempuser = $_COOKIE[$cookieuser];
	
	# We need to check to make sure the user is still logged in #
	if(!empty($tempuser['userpass']) && !empty($tempuser['username'])) {
		$sql_user = mysql_query("SELECT * FROM `users` WHERE `username` = '".$tempuser['username']."'",$link);
		while($userinfo = mysql_fetch_array($sql_user)) {
			$user = new user($userinfo);
		}
		$sql = "SELECT `permissions` FROM `usergroups` WHERE `groupid` = ".$user->userlevel;
		$sql = mysql_query($sql,$link);
		while($userinfo = mysql_fetch_array($sql)) {
			$user->userlevel = $user->userperms(explode("|",$userinfo[0]));
		}
	}
	else {
		$iamadmin = 2;
	}
}
else {
	$iamadmin = 0;
}
if($tempuser && $tempuser['userpass'] != $user->userpass) {
	$cookielength = time()-100;
	require("$sourcepath/loginblock.php");
	$cgadminmenu = "";
	$cgadmin = $cglogin;
	$iamadmin = 0;
	unset($user);
	$loggedinout = logout($cookienames);
}

if($user->userlevel) {
	# We just checked to see if the user is an admin.  If they are, then we give them access to the admin section.
	$cgadminmenu = "<h1><a href='$cagcmsadmin'>$admin_head[0]</a></h1>";
	$menuitems = array();
	if($user->checkperms('pref',SETTINGS,CREATE)) {
		$menuitems['settings'] = "settings";
	}
	if($user->checkperms('pref',PATHS,CREATE)) {
		$menuitems['paths'] = "paths";
	}
	if($user->checkperms('pref',REPORTS,CREATE)) {
		$menuitems['reports'] = "reports";
	}
	if($user->checkperms('pref',MAINT,CREATE)) {
		$menuitems['maint'] = "maintenance";
	}
	if($user->checkperms('sec',SEC,CREATE) | $user->checkperms('sec',SEC,MODIFY) | $user->checkperms('sec',SEC,REMOVE)) {
		$menuitems['sec'] = "addsec";
	}
	if($user->checkperms('cat',CAT,CREATE) | $user->checkperms('cat',CAT,MODIFY) | $user->checkperms('cat',CAT,REMOVE)) {
		$menuitems['cat'] = "addcat";
	}
	if($user->checkperms('cont',CONT,CREATE) | $user->checkperms('cont',CONT,MODIFY) | $user->checkperms('cont',CONT,REMOVE)) {
		$menuitems['cont'] = "addpage";
	}
	$menuitems['user'] = "adduser";
	$menuitems['templates'] = "templates";
	$menuitems['news'] = "news";
	$menuitems['poll'] = "addpoll";
	
	# Here, we will add our Admin menu items for CAGCMS Add-Ons #
	
	array_push($menuitems,"addmenu");
	array_push($menuitems,"addscreens");
	
	# We are finished adding our Admin menu items for CAGCMS Add-Ons #
	
	$cgadminmenu .= "
		<ul>";
	foreach($menuitems as $menuitem) {
		if($_GET{'action'} == $menuitem) {
			$activehighlight = " class='activemenu'";
		}
		else {
			$activehighlight = "";
		}
		$admin_mnu_key = $menuitem;
		$cgadminmenu .= "
			<li onclick='javascript:document.location=\"$cagcmsadmin?action=$menuitem\";'$activehighlight><a href='$cagcmsadmin?action=$menuitem' title='$admin_mnu[$admin_mnu_key]'>$admin_mnu[$admin_mnu_key]</a></li>";
	}
		$cgadminmenu .= "
			<li onclick='javascript:document.logout.submit();'><form name='logout' action='$rooturl/login.php?action=logout' method='post'><input type='hidden' name='returnto' value='$cagcmsadmin' /><a href='javascript:document.logout.submit();' title='Logout'>Logout</a></form></li>
		</ul>";
	if(!empty($_GET{'action'})) {
		$filename = "$adminpath/".$_GET{'action'}.'.php';
		require_once($filename);
	}
	else {
		require_once("$adminpath/admin_content.php");
	}
}
else {
	# If the user is not verified as an admin, then we need to offer them a chance to log in.
	require("$sourcepath/loginblock.php");
	$cgadminmenu = "";
	$cgadmin = $cglogin;
}

if(empty($cghead)) {
	$cghead = "";
}
$cghead .= "
<script type=\"text/javascript\" language=\"javascript\">
	function genlink(what,where) {
		what = document.getElementById(what);
		where = document.getElementById(where);
		var myexp = \"[^a-zA-Z0-9]\";
		var re = new RegExp(myexp,\"g\");
		var who = what.value.replace(re,\"_\");
		var who = who.toLowerCase();
		where.value = who.substring(0,50);
	}
	function lul(what) {
		what = document.getElementById(what);
		if(what.className == 'disabled') {
			if(confirm('Are you sure you want to unlock the link?\\n\\nDoing so could cause potential problems linking to this page')) {
				what.className = 'text';
				what.onfocus = function() {};
				what.focus();
			}
		}
		else {
			what.className = 'disabled';
			what.onfocus = function() { what.blur() };
		}
	}
</script>";

$template_vars = array('cgsitename'=>$sitename,'cgadminmenu'=>$cgadminmenu,'cgadmin'=>$cgadmin,'cgadminstyle'=>"$stylepath/adminstyles.css",'cghead'=>$cghead,'cgclose'=>$cgclose,'cgcagcmsurl'=>$cagcmsurl,'cgstyledir'=>$stylepath,'cgimgdir'=>$imgdir);
$mytemplate = new template("admintemplate",$template_vars);
echo $mytemplate->buildtemplate($templatepath,$imgdir);

mysql_close($link);
?>