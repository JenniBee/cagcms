<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# adminlng.php                                                  #
# This file contains the various language strings used in the   #
# administration center for CAGCMS                              #
#################################################################

$admin_mnu = array(
'settings'=>"General Preferences",
'paths'=>"Path and DB Settings",
'reports'=>"Site Reports",
'maintenance'=>"Perform Maintenance",
'addsec'=>"Site Sections",
'addcat'=>"Site Categories",
'addpage'=>"Site Content",
'adduser'=>"Users and Usergroups",
'templates'=>"Edit Your Templates",
'news'=>"Add/Edit Your News",
'addpoll'=>"Add/Edit Your Polls",
'addmenu'=>"Add/Edit Extra Menu Items",
'addscreens'=>"Add/Edit Screenshots",
);

$admin_ans = array (
'1'=>"No",
'2'=>"Yes",
'3'=>"Site News (if CAGCMS is used for news)",
'4'=>"Save These Settings",
'5'=>"Edit",
'6'=>"Remove",
'7'=>"Perform This Action",
'9'=>"Add Them",
'13'=>"Save These Changes",
);

$admin_head = array (
'0'=>"Admin Center",
'1'=>"General Preferences",
'2'=>"Cookie Settings",
'3'=>"Path Settings",
'4'=>"Database Settings",
'5'=>"Manage Site Sections",
'6'=>"Manage Site Categories",
'7'=>"Manage Site Content",
'8'=>"Member Controls",
'9'=>"Site Reports",
'10'=>"Edit Your Templates",
'11'=>"Manage Site Polls",
);

$cookie_txt = array(
'cookieuser'=>"Username cookie",
'cookiepass'=>"Password cookie",
'cookiename'=>"Real/Display Name cookie",
'cookielvl'=>"User Group/User Level cookie",
'cookielength'=>"Default cookie length (in seconds)",
);

$settings_txt = array(
'2'=>"Would you like to use CAGCMS to generate your site news?",
'3'=>"Which page do you want to use as your default home page?",
'4'=>"Would you like a top-level link to your contact page?",
'4a'=>"You must create a page with the ID \"contact\" in order for this to work",
'5'=>"Do you have search code that you would like to include on your pages?",
'5a'=>"If you do not, I would recommend signing up for a free search account at <a href='http://www.atomz.com/' target='_blank' title='Atomz Search'>atomz.com</a>",
'5b'=>"Please insert the search code you plan to use:",
'6'=>"Do you want users to be able to login to the site?",
'7'=>"What tags would you like to use to wrap the active menu item?",
'7a'=>"Opening tag:",
'7b'=>"Closing tag:",
'8'=>"Would you like to use your YaBB users and usergroups?",
'8a'=>"If so:",
'9'=>"Please enter the absolute path to your YaBB installation",
'10'=>"Please enter the URL to your YaBB installation",
'11'=>"Would you like to display page, category and section icons where defined?",
'12'=>"Would you like to allow users to register?",
'13'=>"How many news stories do you want to display?",
'14'=>"How long (in days) do you want news stories to stay on the front page?",
);

$paths_txt = array(
'0'=>"What is the <em>absolute path</em> to your",
'1'=>"What is the name of your web site?",
'2'=>"What is the URL of your CAGCMS installation (including \"index.php\")?",
'3'=>"What is the <em>absolute path</em> to your Admin directory?",
'4'=>"What is the URL to your Admin directory?",
'5'=>"What is the URL to your images directory?",
'6'=>"What is the <em>absolute path</em> to your Language directory?",
'7'=>"What is the <em>absolute path</em> to your Sources directory?",
'8'=>"What is the URL to your Styles directory?",
'9'=>"What is the <em>absolute path</em> to your Templates directory?",
'10'=>"What is the <em>absolute path</em> to your Backup directory?",
'11'=>"What is the URL to your tiny_mce directory?",
'12'=>"What is the root URL to your CAGCMS installation (do not include trailing slash)?",
'13'=>"What is the administrator's email address?",
);

$db_txt = array(
'1'=>"What is the name of your database?",
'2'=>"What is the username for your database?",
'3'=>"What is the password for your database?",
'4'=>"What is the name of the host on which your database is stored (usually \"localhost\")?",
);

$section_txt = array(
'1'=>"Within this area, you can manage your site sections.  Add new sections, edit your existing sections, or remove sections that you no longer want.",
'2'=>"With Selected:",
'3'=>"You may now make any necessary changes to the sections that you selected.",
'4'=>"Please fill in the information below to create your new sections.",
'5'=>"Add Sections",
'6'=>"Section Name",
'6a'=>"This is the name that will show up in your menus, at the top of the section page, and in your browser's title bar.",
'7'=>"Section Link",
'7a'=>"This will be used as the section ID in most cases.",
'8'=>"Section Description",
'8a'=>"This is the main summary of the items found within this category.",
'9'=>"Section Icon",
'9a'=>"If icons are turned on, this should be the URL to the icon you want displayed on this section page.",
'10'=>"Create These Sections",
);

$cat_txt = array(
'1'=>"Within this area, you can manage your site categories.  Add new categories, edit your existing categories, or remove categories that you no longer want.",
'2'=>"With Selected:",
'3'=>"You may now make any necessary changes to the categories you selected.",
'4'=>"Please fill in the information below to create your new categories.",
'5'=>"Add Categories",
'6'=>"Category Name",
'6a'=>"This is the name that will show up in your menus, at the top of the category page, and in your browser's title bar.",
'7'=>"Category Link",
'7a'=>"This will be used as the category ID in most cases.",
'8'=>"Category Description",
'8a'=>"This is the main summary of the items found within this category.",
'9'=>"Category Icon",
'9a'=>"If icons are turned on, this should be the URL to the icon you want displayed on this category page.",
'10'=>"Add These Categories",
);

$page_txt = array(
'1'=>"Within this area, you can manage your site content.  Add new pages, edit your existing pages, or remove pages you no longer want.",
'2'=>"With Selected:",
'3'=>"You may now make any necessary changes to the pages that you selected.",
'4'=>"Please fill in the information below to create your new pages.",
'5'=>"Add Pages",
'6'=>"Page Link",
'6a'=>"This will be used as the page ID in most cases",
'7'=>"Link Title",
'7a'=>"This is the text that will be displayed in your menus",
'8'=>"Page Title",
'8a'=>"This will be displayed at the top of your page, and in the browser's title bar",
'9'=>"Page Description",
'9a'=>"This will be used when displaying the page summaries on a category page, and will be displayed at the top of the content page.",
'10'=>"Page Content",
'10a'=>"This will be displayed under the page description on this page.  This is the main content of the page.",
'11'=>"Page Icon",
'11a'=>"If icons are turned on, this should be the URL to the icon you want displayed on this content page.",
'12'=>"Create These Pages",
);

$poll_txt = array(
'1'=>"Within this area, you can manage the various polls you have on your site, and add new polls.",
'2'=>"With Selected:",
'3'=>"You may now make any necessary changes to the polls that you selected.",
'4'=>"Please fill in the information below to create your new poll.",
'5'=>"Add a poll with how many options?",
'5a'=>"Add Poll",
'6'=>"Poll Title",
'7'=>"Poll Question",
'8'=>"Poll Option #",
'9'=>"Poll Start Date",
'10'=>"Poll End Date",
'11'=>"On which pages should this poll appear?",
);

$user_txt = array(
'0'=>"You have chosen to use YaBB to manage your users and usergroups.  Please manage your users and usergroups through there.",
'1'=>"Within this area, you can manage your users and usergroups.",
'2'=>"Manage Your Members",
'3'=>"Edit/remove existing members",
'4'=>"Manage Your Usergroups",
'5'=>"Edit/remove existing usergroups",
'6'=>"Table of currently registered users",
'7'=>"With Selected:",
'8'=>"Add Users",
'9'=>"Add Groups",
'10'=>"Username",
'11'=>"Email",
'12'=>"Real Name",
'13'=>"Level",
'14'=>"IP Addresses",
'15'=>"Registered",
'16'=>"Last Login",
'17'=>"Save These Changes",
'18'=>"Save New Users",
'19'=>"Password",
'20'=>"Group name",
'21'=>"Switch these users to this group:",
'22'=>"Save New Groups",
'23'=>"Currently, users can only belong to one group at a time, so if you select the same user for more than one usergroup, that user will only be added to the last membergroup selected.",
'24'=>"Sorry, but you have tried to remove the Administrators usergroup.  You are not allowed to remove this usergroup, therefore, it was not removed.",
'25'=>"Sorry, but you have tried to remove the original administrator.  You are not allowed to remove this user, therefore, it was not removed.",
);

$reports_txt = array(
'1'=>"Within this area, you can view various information about your site, its content, and your visitors.",
'1a'=>"User Information",
'1b'=>"Content Information",
'2'=>"You have a total of ",
'3'=>" registered members.",
'4'=>"The ",
'5'=>" membergroup currently has a total of ",
'6'=>" members within it.",
'7'=>" content item(s)",
'8'=>"Item Name",
'9'=>"Content Items",
'10'=>"# of Hits",
'11'=>" is the most recent registered member.",
);

$template_txt = array(
'1'=>"Please choose a template to edit",
'2'=>"-- Choose One --",
'3'=>"Edit This Template",
'4'=>"Save These Template Changes",
);

$maint_txt = array(
'1'=>"Recount Section Totals",
'2'=>"Recount Category Totals",
'3'=>"Recount Membergroup Totals",
'4'=>"Choose Which Tables You Wish To Back-Up",
'5a'=>"Select All",
'5b'=>"Deselect All",
'6'=>"What would you like to call this file?",
'7'=>"Please do not include a file extension",
'8'=>"In which format do you want to generate this file?",
'9'=>"What would you like to back up?",
'10'=>"Structure Only",
'11'=>"Data Only",
'12'=>"Structure and Data",
'13'=>"For which MySQL version would you like to export your data?",
'14'=>"Backup Your Database",
);

$error_txt = array(
'1'=>"You do not have appropriate permissions to access this area.",
'2'=>"Only administrators of this site have access to this page.  If you are an administrator, please log in.  If you are not an administrator, or you feel you reached this page in error, please <a href='javascript:history.go(-1);'>go back</a> and try again.",
);

# Here, we begin defining our add-on language items #

$admin_addmenutxt = array(
'1'=>"Manage Extra Menu Items",
'2'=>"Within this area, you can add new extra menu items and edit or remove existing extra menu items.",
'3'=>"With Selected:",
'4'=>"Add Menu Items",
'5'=>"You may now make any necessary changes to the menu items you selected.",
'6'=>"On which pages do you want to show this menu item?",
'7'=>"Location of menu item",
'7a'=>"Please type a valid URL that leads to the page or item you want this link to lead to.",
'8'=>"Menu Item Title",
'8a'=>"Please type the text that you want to appear in the menu wherever this menu item appears on the site.",
'9'=>"Please fill in the appropriate information to create a new Extra Menu item:",
'10'=>"Create These Menu Items",
'11'=>"Administrative Title",
'11a'=>"Give this menu item a unique title to be used to identify this particular item within administrative functions.",
);

$admin_addscreenstxt = array(
'1'=>"Manage Screenshot Items",
'2'=>"Within this area, you can add new screenshot items and edit or remove existing screenshot items.",
'3'=>"With Selected:",
'4'=>"Add Screenshots",
'5'=>"You may now make any necessary changes to the screenshots you selected.",
'6'=>"On which pages do you want to show this screenshot?",
'7'=>"Location of screenshot",
'7a'=>"Please type a valid URL that leads to either the fullsize screenshot, or an HTML page that shows the fullsize screenshot.",
'8'=>"Screenshot Title",
'8a'=>"Please type the text that you want to appear in the case that the screenshot thumbnail does not load properly.",
'9'=>"Please fill in the appropriate information to create a new Screenshot entry:",
'10'=>"Create These Screenshot Entries",
'11'=>"Location of thumbnail",
'11a'=>"Please type a valid URL that leads to the thumbnail of the screenshot you want to display.  This thumbnail will be displayed on all selected pages.",
'12'=>"Screenshot caption",
'12a'=>"Please type the text that you want to appear as the caption underneath the thumbnail.",
);

$news_txt = array(
'poster'=>'Guest Poster:',
'email'=>'Guest Poster\'s Email:',
'subject'=>'News Title:',
'content'=>'News Content:',
'savenew'=>'Save Your News',
'saveedit'=>'Save These Edits',
'addhead'=>'Add a news item',
'edithead'=>'Edit a news item',
'postdate'=>'Posted:',
);

# We are finished defining our add-on language items #

?>