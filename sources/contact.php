<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# contact.php                                                   #
# This file is called when someone uses the contact form on     #
# the contact page.  Unfortunately, at the moment, this file is #
# developed specifically for the default form in CAGCMS.  In    #
# the future, it will be more versatile, so that it can handle  #
# custom form validation.                                       #
#################################################################

function sendcontact($mail_vars) {

	$header = "FROM: ".$mail_vars{'name'}." <".$mail_vars{'email'}.">";
	unset($message);

	$email_input = $mail_vars{'email'};
	
	if(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$", $email_input)) {
		$message = 3;
	}

	foreach($mail_vars as $mail_var) {
		if(empty($mail_var) || $mail_var == "") {
			$message = 2;
		}
	}

	if(empty($message) || $message <= 1) {
		if(mail($mail_vars{'to'}, $mail_vars{'subject'}, $mail_vars{'message'}, $header)) {
			$message = 1;
		}
		else {
			$message = 0;
		}
	}
	
	return $message;
}

?>