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
<!-- Google Custom Search -->
<script>
  (function() {
    var cx = 'Search Engine ID';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<gcse:search></gcse:search>";
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
