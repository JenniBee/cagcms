<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# index.php                                                     #
# This is the main file that parses all of the content and      #
# variables.                                                    #
#################################################################

unset($id);

require_once('cagcms/sources/qwerty/config.php');
require_once($sourcepath.'/loginfunctions.php');
require_once("$langpath/english.php");

if(!empty($_GET{'action'})) {
	if($_GET{'action'} == 'sendmail') {
		require_once("$sourcepath/contact.php");
		$mail_vars = array('to'=>$webmaster_email,'subject'=>$_POST{'subject'},'message'=>$_POST{'message'},'name'=>$_POST{'name'},'email'=>$_POST{'email'});
		$message = sendcontact($mail_vars);
		if($message == 1) {
			header("Location: $cagcmsurl?id=success");
		}
		elseif($message == 2) {
			header("Location: $cagcmsurl?id=failure");
		}
		elseif($message == 3) {
			header("Location: $cagcmsurl?id=emailfailure");
		}
		elseif($message == 0) {
			header("Location: $cagcmsurl?id=sendfailure");
		}
		else {
			$_POST{'name'} = $mail_vars{'name'};
			$_POST{'subject'} = $mail_vars{'subject'};
			$_POST{'message'} = $mail_vars{'message'};
			$_POST{'email'} = $mail_vars{'email'};
			header("Location: $cagcmsurl?id=contact");
		}
	}
	elseif($_GET{'action'} == 'login') {
		require_once('login.php');
	}
}
if($searchok == 1) {
	$cgsearchblock = $searchcode;
}
else {
	$cgsearchblock = "";
}

# We need to set our $link variable and select our database to make it easier to connect to the database.

$link = mysql_connect(HOST,DBUSER,DBPASS);
mysql_select_db(DB, $link);

$debug = "";

# We need to set $id to $_GET{'id'}.  We are doing this as a single variable, rather than extracting $_GET so we avoid possible script hacking.  While we're at it, we will set $id to the default if it is currently empty.  We never want to extract $_GET, as that makes it a lot easier to hack the script by plugging insecure, uninitialized variables into the query string.

if(empty($_GET{'id'}) && empty($_GET{'catid'}) && empty($_GET{'secid'})) {
	if(isset($action)) {
		$pagetype = "action";
	}
	else {
		$id = $defaultid;
		unset($pagetype);
	}
}
elseif($_GET{'id'} == "success") {
	$id = 'contact';
	$pagetype = "page";
	$success = 1;
	$message = "<h1 align='center' style='color: #f00;'>Your message was sent successfully.</h1>";
}
elseif($_GET{'id'} == "failure") {
	$id = 'contact';
	$pagetype = "page";
	$success = 0;
	$message = "<h1 align='center' style='color: #f00;'>There was an error sending your message.  It seems that you failed to fill in one of the required fields.  Please try again.</h1>";
}
elseif($_GET{'id'} == "emailfailure") {
	$id = 'contact';
	$pagetype = "page";
	$success = 0;
	$message = "<h1 align='center' style='color: #f00;'>It appears that the email address you specified does not match standard email address format.  Please try again.</h1>";
}
elseif($_GET{'id'} == "sendfailure") {
	$id = 'contact';
	$pagetype = "page";
	$success = 0;
	$message = "<h1 align='center' style='color: #f00;'>It appears that there is some sort of error sending email from our server.  Please try contacting us the old-fashioned way, or try using the contact form again later.  Thank you, and we are sorry for the inconvenience.</h1>";
}	
elseif(isset($_GET{'catid'})) {
	$id = $_GET{'catid'};
	$pagetype = "cat";
}
elseif(isset($_GET{'secid'})) {
	$id = $_GET{'secid'};
	$pagetype = "sec";
}
else {
	$id = $_GET{'id'};
	unset($pagetype);
}

# If we have not already determined what type of page this is, then we need to figure out whether it's a content page, a category page, or a section page #

if(empty($pagetype)) {
	$sql1 = mysql_query("SELECT * FROM `content` WHERE `pageid` = '$id' OR `pagelink` = '$id'", $link);
	if(mysql_num_rows($sql1) == 0) {
		$sql2 = mysql_query("SELECT * FROM `categories` WHERE `catid` = '$id' OR `catlink` = '$id'", $link);
		if(mysql_num_rows($sql2) == 0) {
			$sql3 = mysql_query("SELECT * FROM `sections` WHERE `secid` = '$id' OR `seclink` = '$id'", $link);
			if(mysql_num_rows($sql3) == 0) {
				$cgmain = "We apologize, but the page you requested does not seem to exist.";
			}
			else {
				$pagetype = "sec";
			}
		}
		else {
			$pagetype = "cat";
			while($rs = mysql_fetch_array($sql2)) {
				$secid = $rs[secid];
			}
		}
	}
	else {
		$pagetype = "page";
		while($rs = mysql_fetch_array($sql1)) {
			$secid = $rs[secid];
			$catid = $rs[catid];
		}
	}
}

if($pagetype == "cat") {
	addhit($id,'cat');
	$sql_cat = mysql_query("SELECT `catid` FROM `categories` WHERE `catid` = '$id' OR `catlink` = '$id'", $link);
	while($rt = mysql_fetch_array($sql_cat)) {
		$sql = mysql_query("SELECT `pagelink` FROM `content` WHERE `catid` = '$rt[catid]'", $link);
		if(mysql_num_rows($sql) == 0) {
			$pagetype == "page";
			while($rs = mysql_fetch_array($sql)) {
				$id = $rs[pagelink];
			}
			header("Location: $cagcmsurl?id=$id");
		}
	}
}
elseif($pagetype == "sec") {
	addhit($id,'sec');
	$sql_sec = mysql_query("SELECT `secid` FROM `sections` WHERE `secid` = '$id' OR `seclink` = '$id'", $link);
	while($rt = mysql_fetch_array($sql_sec)) {
		$sql = mysql_query("SELECT `pagelink` FROM `content` WHERE `secid` = '$rt[secid]'", $link);
		if(mysql_num_rows($sql) == 0) {
			$pagetype == "page";
			while($rs = mysql_fetch_array($sql)) {
				$id = $rs[pagelink];
			}
			header("Location: $cagcmsurl?id=$id");
		}
	}
}

# We need to take the following steps:
# 1)  We need to define all of the templated variables.
#    a)  CACMS Title
#    b)  CACMS CatMenu
#    c)  CACMS PageMenu
#    d)  CACMS Description
#    e)  CACMS Content
# 2)  We need to open the appropriate template, and parse all of its variables.
# 3)  We need to print the entire page.

# We will now include our menu file and build our various menus #
require_once("$sourcepath/menus.php");

# If the user has specified that they will be using CACMS to generate their news, we need to parse the news differently than the rest of our content #

if($id == 'news' || $id == 'newsarchive' && $newsok == 1) {	

	$cgnews = "";

	$sql_query = "SELECT * FROM `news` WHERE `newstime` >= '";
	$sql_query .= time()-($newsoff*24*60*60);
	$sql_query .= "' ORDER BY `newstime` DESC";
	if($id == 'news' && mysql_num_rows(mysql_query($sql_query, $link)) < $newscap || $id == 'newsarchive') {
		$sql_query = "SELECT * FROM `news` ORDER BY `newstime` DESC";
	}
	$sql_query = mysql_query($sql_query, $link);
	$newscnt = 0;
	while($rs = mysql_fetch_array($sql_query)) {
		if($id == 'news' && $newscnt < $newscap || $id == 'newsarchive') {
			if(!empty($rs['username'])) {
				if($yabbok) {
					$uservars = getyabbuservars($memberdir."/".$rs['username'].".vars");
					$poster = new user(array(0,$uservars['username'],NULL,$uservars['email'],$uservars['realname'],NULL,NULL,NULL,NULL,NULL,0));
				}
				else {
					$sql_user = "SELECT * FROM `users` WHERE `username` = '".$rs['username']."'";
					$sql_user = mysql_query($sql_user,$link);
					while($userinfo = mysql_fetch_array($sql_user)) {
						$poster = new user($userinfo);
					}
				}
			}
			else {
				$poster = new user(array(0,NULL,NULL,$rs['newsemail'],$rs['newsposter'],NULL,NULL,NULL,NULL,NULL,0));
			}
			if(!empty($poster->useremail)) {
				$poster->displayname = "<a href='mailto.php?id=".$rs['newstime']."' target='emailwindow'>".$poster->displayname."</a>";
			}
			$mytempvars = array('cgnewssubject'=>$rs['newssubject']);
			$mytempvars['cgnewsdate'] = date("F d, Y",$rs['newstime']);
			$mytempvars['cgnewsposter'] = $poster->displayname;
			$mytempvars['cgnewscontent'] = $rs['newscontent'];
			$template_vars = $mytempvars;
			$mytemplate = new template('newstemplate', $template_vars);
			$cgnews .= $mytemplate->buildtemplate($templatepath,$imgdir);
			$newscnt++;
		}
	}
	
	if($id == 'news')
	{
		$cgmain = $cgnews;
		$cgpagetitle = $template_lng[newstitle];
		$cgtitle = $cgpagetitle.' | '.$sitename;
	}

	if($id == 'newsarchive')
	{
		$cgmain = $cgnews;
		$cgpagetitle = $template_lng[archivetitle];
		$cgtitle = $cgpagetitle.' | '.$sitename;
	}

}	

# If the page is not to be built from CACMS News, then we need to build it properly. #

else {

	addhit($id,'page');

	# If the pagetype is a content page, we need to build the page appropriately. #
	
	if($pagetype == "page") {
	
		$sql_query = mysql_query("SELECT * FROM `content` WHERE `pageid` = '$id' OR `pagelink` = '$id' LIMIT 1;", $link);
		while($rs = mysql_fetch_array($sql_query)) {
		
			# We now need to parse the template and build the actual page from that template. #
			
			$cgpagetitle = $rs[pagetitle];
			$cgtitle = $cgpagetitle.' | '.$sitename;

			# If the administrator has icons activated, and an icon is defined for this page, we will add it to our template variables #
			if($iconok == 1 && !empty($rs[pageicon])) {
				$cgpageicon = "<img src='$rs[pageicon]' alt='$rs[pagetitle]' border='0' class='pageicon' />";
			}
			else {
				$cgpageicon = "";
			}

			$template_vars = array('cgcatid'=>$rs[catid],'cgsecid'=>$rs[secid],'cgtitle'=>$cgtitle,'cgpagetitle'=>$cgpagetitle,'cgdescription'=>$rs[pagedesc].$message,'cgcontent'=>$rs[pagecontent],'cgcreated'=>date("F d, Y",$rs[contenttime]),'cgedited'=>date("F d, Y", $rs[contentlastedit]),'cgtopmenu'=>$cgtopmenu,'cgpagemenu'=>$cgpagemenu,'cgsecmenu'=>$cgsecmenu,'cgcatmenu'=>$cgcatmenu,'cgcompmenu'=>$cgcompmenu,'cgsubmenu'=>$cgsubmenu,'cgicon'=>$cgpageicon,);
			
			$mytemplate = new template('pageblock', $template_vars);
			$cgmain = $mytemplate->buildtemplate($templatepath,$imgdir);
		
		}
	}
	elseif($pagetype == "cat") {
		$cgmain = "This will be a category page.  Our ID is $id";
		
		# We need to retrieve the appropriate information for a category display #
		$sql_query = mysql_query("SELECT * FROM `categories` WHERE `catid` = '$id' OR `catlink` = '$id' LIMIT 1;", $link);
		while($rs = mysql_fetch_array($sql_query)) {
			$cgcatid = $rs[catid];
			$cgcatname = $rs[catname];
			$cgcatitems = $rs[catitems];
			$cgcatdesc = $rs[catdesc];
			$cgsecid = $rs[secid];
			$cgcatcreated = $rs[cattimestamp];
			$cgcatedited = $rs[catlastedit];
			$cgeval = $rs{'eval'};
		}

		# We need to build our catblock, which will display information from the pages within this category #	
		$sql2 = mysql_query("SELECT * FROM `categories` WHERE `catid` = '$cgcatid'", $link);
		while($rt = mysql_fetch_array($sql2)) {
			$cgmain = "
	$rt[catdesc]
";
		}
		$sql2 = mysql_query("SELECT `pagetitle`, `pagedesc`, `contenttime`, `contentlastedit`, `pagelink`, `evaldesc` FROM `content` WHERE `catid` = '$cgcatid' ORDER BY `contentlastedit` DESC", $link);
		while($rt = mysql_fetch_array($sql2)) {
			# We will now template our catblock #
			$rt[pagetitle] = preg_replace("/'/","&#39;",$rt[pagetitle]);
			$template_vars = array('cgpagetitle'=>"<a href='$cagcmsurl?id=$rt[pagelink]' title='$rt[pagetitle]'>$rt[pagetitle]</a>",'cgpagedesc'=>"$rt[pagedesc]",'cgcontenttime'=>date("F d, Y",$rt[contenttime]),'cgcontentlastedit'=>date("F d, Y",$rt[contentlastedit]),'cgeval'=>$rt[evaldesc]);
			
			$mytemplate = new template('catblock', $template_vars);
			$cgmain .= $mytemplate->buildtemplate($templatepath,$imgdir);
		}

		$cgpagetitle = $cgcatname;
		$cgtitle = $cgpagetitle.' | '.$sitename;
		
	}
	elseif($pagetype == "sec") {
		$cgmain = "This will be a section page.  Our ID is $id";

		# We need to retrieve the appropriate information for a section display #
		$sql_query = mysql_query("SELECT `secid`, `secname`, `eval` FROM `sections` WHERE `secid` = '$id' OR `seclink` = '$id'", $link);
		while($rs = mysql_fetch_array($sql_query)) {
			$cgsecid = $rs[secid];
			$cgpagetitle = "$rs[secname]";
			$cgtitle = $cgpagetitle.' | '.$sitename;
			$cgeval = $rs{'eval'};
		}

		# We need to build our catblock, which will display information from the categories within this section #
		$sql2 = mysql_query("SELECT * FROM `sections` WHERE `secid` = '$cgsecid'", $link);
		while($rt = mysql_fetch_array($sql2)) {
			$cgmain = "
	$rt[secdesc]
";
		}
		$sql2 = mysql_query("SELECT `catname`, `catdesc`, `cattimestamp`, `catlastedit`, `catlink`, `eval` FROM `categories` WHERE `secid` = '$cgsecid' ORDER BY `catlastedit` DESC", $link);
		while($rt = mysql_fetch_array($sql2)) {
			# We will now template our catblock #
			$rt[catname] = preg_replace("/'/","&#39;",$rt[catname]);
			$template_vars = array('cgpagetitle'=>"<a href='$cagcmsurl?id=$rt[catlink]' title='$rt[catname]'>$rt[catname]</a>",'cgpagedesc'=>"$rt[catdesc]<br /><a href='$cagcmsurl?id=$rt[catlink]' title='$rt[catname]'>Read More</a>",'cgcontenttime'=>date("F d, Y",$rt[cattimestamp]),'cgcontentlastedit'=>date("F d, Y",$rt[catlastedit]),'cgeval'=>$rt{'eval'});
			
			$mytemplate = new template('catblock', $template_vars);
			$cgmain .= $mytemplate->buildtemplate($templatepath,$imgdir);
		}
	}
	
	$debug .= $cgmain;

}

$template_vars = array('cgmain'=>"$cgmain",'cgtopmenu'=>$cgtopmenu,'cgcatmenu'=>"$cgcatmenu",'cgsecmenu'=>"$cgsecmenu",'cgpagemenu'=>"$cgpagemenu",'cgcompmenu'=>$cgcompmenu,'cgsubmenu'=>$cgsubmenu,'cgtitle'=>"$cgtitle",'cgpagetitle'=>"$cgpagetitle",'cgsearchblock'=>"$cgsearchblock",'cgstylesheet'=>"$stylepath/styles.css");
if($loginok == 1) {
	require_once("$sourcepath/loginblock.php");
}

# Here, we will include our extra source files for CAGCMS Add-Ons #

include_once("$sourcepath/extramenus.php");
$template_vars[cgdlmenu]=$cgdlmenu;
include_once("$sourcepath/screenshots.php");
$template_vars[cgscreenblock]=$cgscreenblock;
include_once("$sourcepath/polls.php");
$template_vars[cgpollblock]=$cgpollblock;
include_once("$sourcepath/breadcrumb.php");
$template_vars[cgbreadcrumb]=$cgbreadcrumb;
$cgclose = "This site is powered by <a href=\"https://github.com/JenniBee/cagcms/\">CAGCMS</a>.<br />&copy;2007-2017 <a href=\"http://ten-321.com/\">Ten-321 Enterprises</a>.";
$template_vars[cgclose]=$cgclose;

# We are done including our extra source files for CAGCMS Add-Ons #

$template_vars[cgcagcmsurl]=$cagcmsurl;

$mytemplate = new template('template', $template_vars);
echo $mytemplate->buildtemplate($templatepath,$imgdir);

mysql_close($link);

?>