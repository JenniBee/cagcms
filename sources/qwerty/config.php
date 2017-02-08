<?php
	
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
	
	
	$defaultid = "news";
	$iconok = "1";
	$regok = "";
	$newsok = "1";
	$contactok = "1";
	$searchok = "1";
	$searchcode = "<div class='searchblock'>
<!-- Atomz HTML for Search -->
<form method='get' action='http://search.atomz.com/search/'>
<input type='hidden' name='sp_a' value='sp100318cc' />
<input type='text' size='15' name='sp_q' id='sp_q' />
<input type='image' src='http://siteurl.com/cagcms/images/gobutton.png' alt='Go' name='submitsearch' id='submitsearch' value='Search' />
<input type='hidden' name='sp_p' value='all' />
<input type='hidden' name='sp_f' value='ISO-8859-1' />
</form>
</div>";
	$loginok = "1";
	$yabbok = "";
	$yabbpath = "";
	$yabburl = "";
	$act_open = "<div class='activelink'>";
	$act_close = "</div>";
	$newscap = "10";
	$newsoff = "60";
	$sitename = "Site Name";
	$cagcmsurl = "http://siteurl.com/cagcms/index.php";
	$adminpath = "/cagcms-path/admin";
	$cagcmsadmin = "http://siteurl.com/cagcms/admin/index.php";
	$imgdir = "http://siteurl.com/cagcms/images";
	$langpath = "/cagcms-path/languages";
	$sourcepath = "/cagcms-path/sources";
	$stylepath = "/cagcms-path/styles";
	$templatepath = "/cagcms-path/templates";
	$backuppath = "/cagcms-path/backups";
	$tinymce = "http://siteurl.com/cagcms/tinymce/jscripts/tiny_mce";
	$rooturl = "http://siteurl.com";
	$webmaster_email = "webmaster@email.com";
	define("DB","database");
	define("DBUSER","databaseuser");
	define("DBPASS","databasepass");
	define("HOST","localhost");
	$cookieuser = "cookiesiteuser";
	$cookiepass = "cookiesitepass";
	$cookiename = "cookiesitename";
	$cookielvl = "cookiesitelvl";
	$cookielength = "1000000";
	
	?>
