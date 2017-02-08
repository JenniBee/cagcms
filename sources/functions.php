<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# functions.php                                                 #
# This file contains all of the necessary functions that we     #
# have written specifically for CAG CMS                         #
#################################################################

require_once($sourcepath.'/classes.php');

function md5_base64 ( $data )
{
	return preg_replace('/=+$/','',base64_encode(pack('H*',md5($data))));
}

function parseyabb($myyabbpaths) {
	$myyabbpaths = preg_replace("/\n1;/","",$myyabbpaths);
	$myyabbpaths = preg_replace("/#([a-fA-F0-9]{3,6})/","pound_$1",$myyabbpaths);
	$myyabbpaths = preg_replace("/#+?.*?\n/","\n",$myyabbpaths);
	$myyabbpaths = preg_replace("/pound_/","#",$myyabbpaths);
	$myyabbpaths = preg_replace("/\n{1,}/","\n",$myyabbpaths);
	$myyabbpaths = preg_replace("/q{1,}[\^~](.*?)[\^~];\s{0,}/","<<<VARS\n$1\nVARS;\n",$myyabbpaths);
	return $myyabbpaths;
}

class template {
	public $templatevars;
	public $template_name;

	private $filename;
	
	function __construct($template_name,$templatevars) {
		if(!empty($templatevars)) {
			$this->templatevars = $templatevars;
		}
		if(!empty($template_name)) {
			$this->template_name = $template_name;
		}
	}
	
	function buildtemplate($templatepath,$imgdir) {
		$this->filename = $templatepath.'/'.$this->template_name.'.php';
		$this->templatevars['cgimgdir'] = $imgdir;

		$templatecontent = file_get_contents($this->filename);
		$templatecontent = preg_replace("/<\/cgcms>/","",$templatecontent);
		foreach($this->templatevars as $key=>$val) {
			$tmpkey = array('<cgcms '.str_replace('cg','',$key).'>','{{cgcms '.str_replace('cg','',$key).'}}');
			$templatecontent = str_replace($tmpkey,$val,$templatecontent);
			
		}
		$templatecontent = str_replace(array('<?php','?>'),array('<!--','-->'),$templatecontent);
		return $templatecontent;
	}
}

function addhit($id,$pagetype) {

	global $link;

	if($pagetype == "cat") {
		$table = "categories";
		$row = "cathit";
		$row2 = "catid";
		$row3 = "catlink";
	}
	elseif($pagetype == "sec") {
		$table = "sections";
		$row = "sechit";
		$row2 = "secid";
		$row3 = "seclink";
	}
	else {
		$table = "content";
		$row = "contenthit";
		$row2 = "pageid";
		$row3 = "pagelink";
	}
	if(is_numeric($id)) {
		$sql_query = "UPDATE `$table` SET `$row` = `$row` + 1 WHERE `$row2` = $id OR `$row3` = $id LIMIT 1";
	}
	else {
		$sql_query = "UPDATE `$table` SET `$row` = `$row` + 1 WHERE `$row2` = '$id' OR `$row3` = '$id' LIMIT 1";
	}
	if(mysql_query($sql_query, $link)) {
		$debug = "The hit count was updated successfully for $id";
	}
	else {
		$debug = "There was an error updating the hit count for $id. ".mysql_error();
	}
}

function selfURL() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}
function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}
?>