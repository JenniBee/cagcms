<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# screenshots.php                                               #
# This page generates the screenshot block, known as            #
# $cgscreenblock, for the various pages on the site.            #
#################################################################

$pagetitle = preg_replace("/$sitename - /","",$cgtitle);

$sql_screenblock = mysql_query("SELECT * FROM `screenshots`",$link);

$i = 0;
while($screenitems = mysql_fetch_array($sql_screenblock)) {
	$pageids = explode("|",$screenitems[pageid]);
	foreach($pageids as $pageid) {
		if($pageid == $id) {
			$cgscreenblock .= "
		<div class='thumbnail'>
			<a href='$screenitems[location]' title='$menuitems[title]'><img src='$screenitems[location_thumb]' alt='$screenitems[title]' /></a>
			<div class='screencaption'>$screenitems[caption]</div>
		</div>";
			$i++;
		}
	}
}

if(!empty($i)) {
$cgscreenblock = "
	<div id='screenblock'>
		<h1>Screens</h1>
		".$cgscreenblock."
	</div>";
}
else {
	$cgscreenblock = "
	<div id='screenblock'>
		<h1>Screens</h1>
		<div class='thumbnail'>";
	$cgscreenblock .= '<h2 style="text-align: center">Random Images</h2>';
	ob_start();
#	@readfile('http://dchelp.net/artwork/main.php?g2_view=imageblock.External&g2_blocks=randomImage&g2_show=none&g2_maxSize=120&g2_itemId=20');
	@readfile('http://dchelp.net/artwork/main.php?g2_view=imageblock.External&g2_blocks=randomImage&g2_show=none&g2_maxSize=120&g2_itemId=5029');
	$cgscreenblock .= ob_get_contents();
	ob_end_clean();
	$cgscreenblock .= "
		</div>
	</div>";
}

/* 			<img src='$imgdir/screenshots2.png' alt='Screenshots' width='20' height='136' style='border:0;margin-right:5px;margin-bottom:5px;' /> */
?>