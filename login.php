<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# login.php                                                     #
# This file authenticates users when they try to login to the   #
# site.                                                         #
#################################################################

require_once('cagcms/sources/qwerty/config.php');
require_once($sourcepath.'/loginfunctions.php');

$returnto = $_POST{'returnto'};

$loggedinout = false;

if($_GET{'action'} == 'logout') {
	$loggedinout = logout($cookienames);
	if($loggedinout) {
		header("Location: $returnto");
	}
	else {
		die("There was an error logging out");
	}
}
elseif($yabbok) {
	$loggedinout = yabblogin(array('cookienames'=>$cookienames, 'username'=>$_POST['username'], 'password'=>$_POST['passwrd'], 'memberdir'=>$memberdir, 'cookielength'=>1000000, 'rooturl'=>$rooturl));
	if($loggedinout) {
		header("Location: $returnto");
	}
	else {
		die("There was an error logging you in.");
	}
}
else {
	$loggedinout = login($cookieuser, $cookielength);
	if($loggedinout) {
		header("Location: $returnto");
	}
	else {
		die("There was an error logging you in.");
	}
}

echo $debug;
?>