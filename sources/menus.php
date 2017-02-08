<?
# We will now build our compmenu, which will display the names of the sections, categories and content pages on the site. #
	
$cgcompmenu = "
<ul class='compmenu'>";
	
if($newsok == 1) {
	if($id == "news") {
		$markerl = $act_open;
		$markerr = $act_close;
	}
	else {
		$markerl = "";
		$markerr = "";
	}
	$cgcompmenu .= "<li>$markerl<a href='$cagcmsurl?id=news'>$template_lng[newstitle]</a>$markerr</li>";
}
	
$sql2 = mysql_query("SELECT * FROM `sections` ORDER BY `secname`", $link);
while($ru = mysql_fetch_array($sql2)) {
	if($id == $ru[seclink] || $secid == $ru[secid]) {
		$markerl = $act_open;
		$markerr = $act_close;
	}
	else {
		$markerl = "";
		$markerr = "";
	}
	$ru[secname] = preg_replace("/'/","&#39;",$ru[secname]);
	$cgcompmenu .= "
	<li>
		$markerl
		<a href='$cagcmsurl?secid=$ru[seclink]' title='$ru[secname]'>$ru[secname]</a>
		<ul>";
	$sql3 = mysql_query("SELECT * FROM `categories` WHERE `secid` = '$ru[secid]' ORDER BY `catname`", $link);
	while($rt = mysql_fetch_array($sql3)) {
		$rt[1] = preg_replace("/'/","&#39;",$rt[catname]);
		$cgcompmenu .= "
			<li>
				<a href='$cagcmsurl?catid=$rt[catlink]' title='$rt[catname]'>$rt[catname]</a>";
		$sql4 = mysql_query("SELECT * FROM `content` WHERE `catid` = '$rt[catid]' ORDER BY `linktitle`", $link);
		if(mysql_num_rows($sql4) != 0) {
			$cgcompmenu .= "
				<ul>";
		}
		while($rv = mysql_fetch_array($sql4)) {
			$cgcompmenu .= "
					<li>
						<a href='$cagcmsurl?id=$rv[pagelink]' title='$rv[pagetitle]'>$rv[pagetitle]</a>
					</li>";
		}
		if(mysql_num_rows($sql4) != 0) {
			$cgcompmenu .= "
				</ul>";
		}
		$cgcompmenu .= "
			</li>";
	}
	$cgcompmenu .= "
		</ul>
		$markerr
	</li>";
}

if($contactok == 1) {
	$cgcompmenu .= "<li><a href='$cagcmsurl?id=contact' title='Contact Us'>Contact Us</a></li>";
}

$cgcompmenu .= "
</ul>";

# We will now build our submenu, which will only show the categories and content pages.  It will not show the sections of the Web site #
	
$cgsubmenu = "
<ul class='submenu'>";
	
$sql2 = mysql_query("SELECT * FROM `sections` ORDER BY `secname`", $link);
while($ru = mysql_fetch_array($sql2)) {
	$sql3 = mysql_query("SELECT * FROM `categories` WHERE `secid` = '$ru[secid]' ORDER BY `catname`", $link);
	while($rt = mysql_fetch_array($sql3)) {
		if($id == $rt[catlink] || $catid == $rt[catid]) {
			$markerl = $act_open;
			$markerr = $act_close;
		}
		else {
			$markerl = "";
			$markerr = "";
		}
		$rt[catname] = preg_replace("/'/","&#39;",$rt[catname]);
		$cgsubmenu .= "
	<li>
		$markerl
		<a href='$cagcmsurl?catid=$rt[catlink]' title='$rt[catname]'>$rt[catname]</a>";
		$sql4 = mysql_query("SELECT * FROM `content` WHERE `catid` = '$rt[catid]' ORDER BY `linktitle`", $link);
		if(mysql_num_rows($sql4) != 0) {
			$cgsubmenu .= "
		<ul>";
		}
		while($rv = mysql_fetch_array($sql4)) {
			$cgsubmenu .= "
			<li>
				<a href='$cagcmsurl?id=$rv[pagelink]' title='$rv[pagetitle]'>$rv[pagetitle]</a>
			</li>";
		}
		if(mysql_num_rows($sql4) != 0) {
			$cgsubmenu .= "
		</ul>";
		}
		$cgsubmenu .= "
		$markerr
	</li>";
	}
}
$cgsubmenu .= "
</ul>";

# We will now build our topmenu, which will display all of the sections and categories on the site. #

$cgtopmenu = "<ul class='topmenu'>";

if($newsok == 1) {
    if($id == "news") {
        $markerl = $act_open;
        $markerr = $act_close;
    }
    else {
        $markerl = "";
        $marekrr = "";
    }
    $cgtopmenu .= "<li>$markerl<a href='$cagcmsurl?id=news'>$template_lng[newstitle]</a>$markerr</li>";
}

$sql2 = mysql_query("SELECT * FROM `sections` ORDER BY `secname`", $link);
while($ru = mysql_fetch_array($sql2)) {
	if($id == $ru[secid] || $id == $ru[seclink]) {
    	$markerl = $act_open;
        $markerr = $act_close;
    }
    else {
    	$markerl = "";
        $markerr = "";
    }
    $cgtopmenu .= "<li>$markerl";
    $ru[secname] = preg_replace("/'/","&#39;",$ru[secname]);
    $cgtopmenu .= "<a href='$cagcmsurl?secid=$ru[seclink]' title='$ru[secname]'>$ru[secname]</a><ul class='secsub'>";
    $sql3 = mysql_query("SELECT * FROM `categories` WHERE `secid` = '$ru[secid]' ORDER BY `catname`", $link);
    while($rt = mysql_fetch_array($sql3)) {
        $rt[1] = preg_replace("/'/","&#39;",$rt[catname]);
        $cgtopmenu .= "<li><a href='$cagcmsurl?catid=$rt[catlink]' title='$rt[catname]'>$rt[catname]</a></li>";
    }
    $cgtopmenu .= "</ul>$markerr</li>";
}

if($contactok == 1) {
    $cgtopmenu .= "<li><a href='$cagcmsurl?id=contact' title='Contact Us'>$template_lng[contactus]</a></li>";
}
$cgtopmenu .= "</ul>";

$cgtitle = "$sitename - Site News";

# We will now build our secmenu, which will display only the names of the sections on the site. #

$cgsecmenu = "<ul class='secmenu'>
    <li class='menutitle'>$template_lng[sitenav]</li>";
    
if($newsok == 1) {
    if($id == "news") {
        $markerl = $act_open;
        $markerr = $act_close;
    }
    else {
        $markerl = "";
        $marekrr = "";
    }
    $cgsecmenu .= "<li>$markerl<a href='$cagcmsurl?id=news'>$template_lng[newstitle]</a>$markerr</li>";
}

$sql2 = mysql_query("SELECT * FROM `sections` ORDER BY `secname`", $link);
while($ru = mysql_fetch_array($sql2)) {
	if($id == $ru[secid] || $id == $ru[seclink]) {
    	$markerl = $act_open;
        $markerr = $act_close;
    }
    else {
    	$markerl = "";
        $markerr = "";
    }
    $ru[secname] = preg_replace("/'/","&#39;",$ru[secname]);
    $cgsecmenu .= "<li>$markerl<a href='$cagcmsurl?secid=$ru[seclink]' title='$ru[secname]'>$ru[secname]</a>$markerr</li>";
}

if($contactok == 1) {
    if($id == "contact") {
        $markerl = $act_open;
        $markerr = $act_close;
    }
    else {
        $markerl = "";
        $marekrr = "";
    }
    $cgsecmenu .= "<li>$markerl<a href='$cagcmsurl?id=contact' title='Contact Us'>$template_lng[contactus]</a>$markerr</li>";
}

$cgsecmenu .= "</ul>";

# We will now build our catmenu, which will link to all of the categories on the site. #

$cgcatmenu = "
<ul class='catmenu'>";

$sql2 = mysql_query("SELECT * FROM `categories` ORDER BY `secid`, `catname`", $link);
while($ru = mysql_fetch_array($sql2)) {
	if($id == $ru[catlink] || $catid == $ru[catid]) {
		$markerl = $act_open;
		$markerr = $act_close;
	}
	else {
		$markerl = "";
		$markerr = "";
	}
	$ru[catname] = preg_replace("/'/","&#39;",$ru[catname]);
	$cgcatmenu .= "
	<li>
		$markerl
		<a href='$cagcmsurl?catid=$ru[catlink]' title='$ru[catname]'>$ru[catname]</a>
		$markerr
	</li>";
}

$cgcatmenu .= "
</ul>";
			
# We will now build our pagemenu, which will only display the pages in our site. #

$sql2 = mysql_query("SELECT * FROM `content` ORDER BY `secid`, `catid`, `linktitle`", $link);
if(mysql_num_rows($sql2)) {
	$cgpagemenu = "<ul class='pagemenu'>";
}
while($ru = mysql_fetch_array($sql2)) {
	if($id == $ru[pagelink] || $id == $ru[pageid]) {
    	$markerl = $act_open;
        $markerr = $act_close;
    }
    else {
    	$markerl = $act_open;
        $markerr = $act_close;
    }
    $cgpagemenu .= "<li>$markerl$ru[pagetitle]$markerr</li>";
}
if(mysql_num_rows($sql2)) {
	$cgpagemenu .= "</ul>";
}

?>