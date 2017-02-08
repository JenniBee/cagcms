<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# loginblock.php                                                #
# This file generates the login block that is then inserted     #
# into the site template, so that users can login to the site.  #
# If a user is already logged in, then this file generates a    #
# welcome message instead.                                      #
#################################################################

$returnto = $_SERVER['PHP_SELF'];
if(!empty($_GET)) {
	$returnto .= "?";
	$returntoarr = array();
	foreach($_GET as $key=>$value) {
		array_push($returntoarr,$key."=".$value);
	}
	$returnto = $returnto.implode("&amp;",$returntoarr);
}

if(!empty($_COOKIE[$cookieuser])) {
	$usercook = new user($_COOKIE[$cookieuser]);
}

if(!is_object($user)) {
	$cglogin = "
	<div class='welcome'>
		<h3>Login To Our Site</h3>
		<form name='login' action='$rooturl/login.php' method='post'>
			<label for='username'><span class='label' style='font-weight: normal;'>Username:</span></label>
			<input type='text' name='username' id='username' />
			<label for='passwrd'><span class='label' style='font-weight: normal;'>Password:</span></label>
			<input type='password' name='passwrd' id='passwrd' />
			<input type='hidden' name='cookielength' id='cookielength' value='$Cookie_Length' />
			<input type='hidden' name='returnto' value='$returnto' />
			<p>
			<input type='submit' name='loginsubmit' class='submit' value='Login' />
			</p>
		</form>
	</div>";
	$template_vars['cglogin']=$cglogin;
}
else {
	$realname = $usercook->displayname;
	$cglogin = "
		<div class='welcome'>
			<form action='$rooturl/login.php?action=logout' name='logout' method='post'>
				<input type='hidden' name='returnto' value='$returnto' />
				<h3>Welcome to our site</h3>
				$realname
				<br /><a href='javascript:document.logout.submit();'>Logout</a>
			</form>
		</div>";
	$template_vars['cglogin']=$cglogin;
}

?>