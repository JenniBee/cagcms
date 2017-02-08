<?
$breadcrumb = array("blank");
if($pagetype == "page") {
	$sql_bc = mysql_query("SELECT * FROM `content` WHERE `pageid` = '$id' OR `pagelink` = '$id' LIMIT 1", $link);
	while($rsbc = mysql_fetch_array($sql_bc)) {
		$add_array = "<a href='?id=$rsbc[pagelink]' title='$rsbc[linktitle]'>$rsbc[linktitle]</a>";
		$rsbc_id = $rsbc[catid];
	}
	array_unshift($breadcrumb,$add_array);
	unset($add_array, $sql_bc);
	$sql_bc = "SELECT * FROM `categories` WHERE `catid` = '$rsbc_id'";
}
if($pagetype == "cat") {
	$sql_bc = "SELECT * FROM `categories` WHERE `catid` = '$id' OR `catlink` = '$id'";
}
if($pagetype == "page" || $pagetype == "cat") {
	$sql_bc = mysql_query($sql_bc, $link);
	while($rsbc = mysql_fetch_array($sql_bc)){
		$add_array = "<a href='?id=$rsbc[catlink]' title='$rsbc[catname]'>$rsbc[catname]</a>";
		$rsbc_id = $rsbc[secid];
	}
	array_unshift($breadcrumb,$add_array);
	unset($add_array, $sql_bc);
	$sql_bc = "SELECT * FROM `sections` WHERE `secid` = '$rsbc_id'";
}
if($pagetype == "sec") {
	$sql_bc = "SELECT * FROM `sections` WHERE `secid` = '$id' OR `seclink` = '$id'";
}
if(isset($pagetype)) {
	$sql_bc = mysql_query($sql_bc, $link);
	while($rsbc = mysql_fetch_array($sql_bc)) {
		$add_array = "<a href='?id=$rsbc[seclink]' title='$rsbc[secname]'>$rsbc[secname]</a>";
	}
	array_unshift($breadcrumb,$add_array);
	unset($add_array, $sql_bc);
}
else {
	array_unshift($breadcrumb,"<a href='?id=$id' title='$cgpagetitle'>$cgpagetitle</a>");
}

array_unshift($breadcrumb,"<a href='$cagcmsurl' title='$sitename'>$sitename</a>");
array_pop($breadcrumb);

$breadcrumb[(count($breadcrumb)-1)] = str_replace("' title='","' class='active' title='",$breadcrumb[(count($breadcrumb)-1)]);

$cgbreadcrumb = implode($template_lng[breadcrumbsep],$breadcrumb);

?>