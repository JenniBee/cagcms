<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# extramenus.php                                                #
# This page generates the extra menus, known as $cgdlmenu, for  #
# the various pages on the site.                                #
#################################################################

$pagetitle = preg_replace("/$sitename - /","",$cgtitle);

$cgdlmenu = "";

$sql_dlmenu = mysql_query("SELECT * FROM `extramenus` ORDER BY title",$link);

$i = 0;
while($menuitems = mysql_fetch_array($sql_dlmenu)) {
	$pageids = explode("|",$menuitems[pageid]);
	foreach($pageids as $pageid) {
		if($pageid == $id) {
			$cgdlmenu .= "
		<li>
			<a href='clicks.php?itemid=".$menuitems['itemid']."' title='$menuitems[admintitle]'>$menuitems[title]</a>
		</li>";
			$i++;
		}
	}
}

if(!empty($i)) {
	$cgdlmenu = "
	<ul class='dlmenu'>".$cgdlmenu."
		<li class='cleared'>&nbsp;</li>
	</ul>";
}

?>