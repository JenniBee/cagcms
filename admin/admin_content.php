<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# admin_content.php                                             #
# This is the HTML code that makes up the front page of the     #
# CAGCMS admin center.                                          #
#################################################################

$cgadmin = "
	<h1>CAGCMS Admin Center</h1>
	<p>This is the administration center for your CAGCMS installation.  Within this admin center, you can adjust various settings for your CAGCMS.  The following settings are available to you:</p>
	<ul class='featurelist'>";
	$contarr = array(
		'settings'=>'You can turn various features on and off within this section.',
		'paths'=>'This is where you set your cookie preferences, database information, and various other paths.',
		'reports'=>'In this section, you can view various reports about your site, including click logs, user stats, etc.',
		'addsec'=>$section_txt[1],
		'addcat'=>$cat_txt[1],
		'addpage'=>'You can add, modify and remove site content pages in this area.',
		'adduser'=>'You can add, modify and remove your registered users in this area, assuming that you are not using YaBB for your user-base.',
		'templates'=>'You can modify your template files in this area.'
	);
	foreach($contarr as $key=>$val) {
		$cgadmin .= "
		<li>
			<h2>".$admin_mnu[$key]."</h2>
			<p>$val</p>
		</li>";
	}
	$cgadmin .= "
	</ul>";
?>