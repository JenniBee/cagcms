<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# admin_functions.php                                           #
# This file holds all of the functions specifically written for #
# the CAG CMS administration interface.                         #
#################################################################

require_once($sourcepath.'/classes.php');

$tmceinit = "
	<script language='javascript' type='text/javascript'>
		function popopen(tmp) {
			helpwindow = window.open('','name','height=350,width=400');
			var pagecontent = helpwindow.document;
			pagecontent.write('<html><head><title>Help Windows<\/title><\/head><body>');
			pagecontent.write(tmp);
			pagecontent.write('<\/body><\/html>');
			pagecontent.close();
		}
	</script>
	<script language='javascript' type='text/javascript' src='$tinymce/tiny_mce.js'></script>
	<script language='javascript' type='text/javascript'>
	tinyMCE.init({
		content_css : \"$stylepath/styles.css\",
		relative_urls : false,
		mode : \"textareas\",
		theme : \"advanced\",
		plugins : \"table,advhr,advlink,insertdatetime,searchreplace,contextmenu,paste,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,spellchecker\",
		theme_advanced_buttons1 : \"formatselect,fontsizeselect,separator,cut,copy,paste,pastetext,pasteword,separator,hr,removeformat,visualaid\",
		theme_advanced_buttons2:\"justifyleft,justifycenter,justifyright,justifyfull,separator,bold,italic,underline,strikethrough,sub,sup,forecolor,backcolor,separator,bullist,numlist,separator,outdent,indent,separator,charmap,insertdate,inserttime\",
		theme_advanced_buttons3:\"undo,redo,separator,tablecontrols,separator,link,unlink,anchor,cleanup,help\",
		theme_advanced_buttons4: \"search,replace,separator,cite,abbr,acronym,del,ins,attribs,code\",
		theme_advanced_toolbar_location : \"top\",
		theme_advanced_toolbar_align : \"left\",
		theme_advanced_path_location : \"bottom\",
	    plugin_insertdate_dateFormat : \"%Y-%m-%d\",
	    plugin_insertdate_timeFormat : \"%H:%M:%S\",
		extended_valid_elements : \"hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]\",
		external_link_list_url : \"example_link_list.js\",
		external_image_list_url : \"example_image_list.js\",
		flash_external_list_url : \"example_flash_list.js\",
		media_external_list_url : \"example_media_list.js\",
		template_external_list_url : \"example_template_list.js\",
		file_browser_callback : \"fileBrowserCallBack\",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
valid_elements : \"\"
+\"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name\"
  +\"|rel|rev\"
  +\"|shape<circle?default?poly?rect|style|tabindex|title|target|type],\"
+\"abbr[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"acronym[class|dir<ltr?rtl|id|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"address[class|align|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"applet[align<bottom?left?middle?right?top|alt|archive|class|code|codebase\"
  +\"|height|hspace|id|name|object|style|title|vspace|width],\"
+\"area[accesskey|alt|class|coords|dir<ltr?rtl|href|id|lang|nohref<nohref\"
  +\"|shape<circle?default?poly?rect|style|tabindex|title|target],\"
+\"base[href|target],\"
+\"basefont[color|face|id|size],\"
+\"bdo[class|dir<ltr?rtl|id|lang|style|title],\"
+\"big[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"blockquote[cite|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"body[alink|background|bgcolor|class|dir<ltr?rtl|id|lang|link\"
  +\"|style|title|text|vlink],\"
+\"br[class|clear<all?left?none?right|id|style|title],\"
+\"button[accesskey|class|dir<ltr?rtl|disabled<disabled|id|lang|name\"
  +\"|style|tabindex|title|type\"
  +\"|value],\"
+\"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"center[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"cite[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"code[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"col[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id\"
  +\"|lang\"
  +\"|span|style|title\"
  +\"|valign<baseline?bottom?middle?top|width],\"
+\"colgroup[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl\"
  +\"|id|lang\"
  +\"|span|style|title\"
  +\"|valign<baseline?bottom?middle?top|width],\"
+\"dd[class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"del[cite|class|datetime|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"dfn[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"dir[class|compact<compact|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"dl[class|compact<compact|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"dt[class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"em/i[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"fieldset[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],\"
+\"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang\"
  +\"|method<get?post|name\"
  +\"|style|title|target],\"
+\"frame[class|frameborder|id|longdesc|marginheight|marginwidth|name\"
  +\"|noresize<noresize|scrolling<auto?no?yes|src|style|title],\"
+\"frameset[class|cols|id|rows|style|title],\"
+\"h1[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"h2[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"h3[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"h4[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"h5[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"h6[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"head[dir<ltr?rtl|lang|profile],\"
+\"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade\"
  +\"|size|style|title|width],\"
+\"html[dir<ltr?rtl|lang|version],\"
+\"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id\"
  +\"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style\"
  +\"|title|width],\"
+\"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height\"
  +\"|hspace|id|ismap<ismap|lang|longdesc|name\"
  +\"|src|style|title|usemap|vspace|width],\"
+\"input[accept|accesskey|align<bottom?left?middle?right?top|alt\"
  +\"|checked<checked|class|dir<ltr?rtl|disabled<disabled|id|ismap<ismap|lang\"
  +\"|maxlength|name\"
  +\"|readonly<readonly|size|src|style|tabindex|title\"
  +\"|type<button?checkbox?file?hidden?image?password?radio?reset?submit?text\"
  +\"|usemap|value],\"
+\"ins[cite|class|datetime|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"isindex[class|dir<ltr?rtl|id|lang|prompt|style|title],\"
+\"kbd[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"label[accesskey|class|dir<ltr?rtl|for|id|lang\"
  +\"|style|title],\"
+\"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"li[class|dir<ltr?rtl|id|lang\"
  +\"|style|title|type\"
  +\"|value],\"
+\"link[charset|class|dir<ltr?rtl|href|hreflang|id|lang|media\"
  +\"|rel|rev|style|title|target|type],\"
+\"map[class|dir<ltr?rtl|id|lang|name\"
  +\"|style\"
  +\"|title],\"
+\"menu[class|compact<compact|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"meta[content|dir<ltr?rtl|http-equiv|lang|name|scheme],\"
+\"noframes[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"noscript[class|dir<ltr?rtl|id|lang|style|title],\"
+\"object[align<bottom?left?middle?right?top|archive|border|class|classid\"
  +\"|codebase|codetype|data|declare|dir<ltr?rtl|height|hspace|id|lang|name\"
  +\"|standby|style|tabindex|title|type|usemap\"
  +\"|vspace|width],\"
+\"ol[class|compact<compact|dir<ltr?rtl|id|lang\"
  +\"|start|style|title|type],\"
+\"optgroup[class|dir<ltr?rtl|disabled<disabled|id|label|lang\"
  +\"|style|title],\"
+\"option[class|dir<ltr?rtl|disabled<disabled|id|label|lang\"
  +\"|selected<selected|style|title|value],\"
+\"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"param[id|name|type|value|valuetype<DATA?OBJECT?REF],\"
+\"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang\"
  +\"|style|title|width],\"
+\"q[cite|class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"s[class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"samp[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"script[charset|defer|language|src|type],\"
+\"select[class|dir<ltr?rtl|disabled<disabled|id|lang|multiple<multiple|name\"
  +\"|size|style\"
  +\"|tabindex|title],\"
+\"small[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"strike[class|class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"strong/b[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"style[dir<ltr?rtl|lang|media|title|type],\"
+\"sub[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"sup[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title],\"
+\"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class\"
  +\"|dir<ltr?rtl|frame|height|id|lang\"
  +\"|rules\"
  +\"|style|summary|title|width],\"
+\"tbody[align<center?char?justify?left?right|char|class|charoff|dir<ltr?rtl|id\"
  +\"|lang\"
  +\"|style|title\"
  +\"|valign<baseline?bottom?middle?top],\"
+\"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class\"
  +\"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap\"
  +\"|rowspan|scope<col?colgroup?row?rowgroup\"
  +\"|style|title|valign<baseline?bottom?middle?top|width],\"
+\"textarea[accesskey|class|cols|dir<ltr?rtl|disabled<disabled|id|lang|name\"
  +\"|readonly<readonly|rows|style|tabindex|title],\"
+\"tfoot[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id\"
  +\"|lang\"
  +\"|style|title\"
  +\"|valign<baseline?bottom?middle?top],\"
+\"th[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class\"
  +\"|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap\"
  +\"|rowspan|scope<col?colgroup?row?rowgroup\"
  +\"|style|title|valign<baseline?bottom?middle?top|width],\"
+\"thead[align<center?char?justify?left?right|char|charoff|class|dir<ltr?rtl|id\"
  +\"|lang\"
  +\"|style|title\"
  +\"|valign<baseline?bottom?middle?top],\"
+\"title[dir<ltr?rtl|lang],\"
+\"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class\"
  +\"|rowspan|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title|valign<baseline?bottom?middle?top],\"
+\"tt[class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"u[class|dir<ltr?rtl|id|lang\"
  +\"|style|title],\"
+\"ul[class|compact<compact|dir<ltr?rtl|id|lang\"
  +\"|style|title|type],\"
+\"var[class|dir<ltr?rtl|id|lang\"
  +\"|style\"
  +\"|title]\",
		template_replace_values : {
			username : \"Jack Black\",
			staffid : \"991234\"
		}
	});
	
	function fileBrowserCallBack(field_name, url, type, win) {
		tinyMCE.openWindow({
			file : \"/news/styleguide/includes/upLoadStart.asp?type=\" + type,
			title : \"File Browser\",
			width : 400,  // Your dimensions may differ - toy around with them!
			height : 150,
			close_previous : \"no\"
		}, {
			window : win,
			input : field_name,
			resizable : \"yes\",
			inline : \"yes\",  // This parameter only has an effect if you use the inlinepopups plugin!
			editor_id : tinyMCE.getWindowArg(\"editor_id\")
		});
		return false;
	}
	</script>";

function makeselect($selectname,$select_vars) {

	global $admin_ans;
	global $$selectname;

	if(empty($select_vars)) {
		if($$selectname == 1 || $_POST{$selectname} == 1) {
			$selectyes = " selected='selected'";
			$selectno = "";
		}
		else {
			$selectyes = "";
			$selectno = " selected='selected'";
		}
		$select = "
			<select name='$selectname' id='$selectname'>
				<option value='0'$selectno>$admin_ans[1]</option>
				<option value='1'$selectyes>$admin_ans[2]</option>
			</select>";
	}

	return $select;

}

function removehtml($code) {
	$code = preg_replace('/</','&lt;',$code);
	$code = preg_replace("/'/",'&#39;',$code);
	$code = preg_replace('/"/','&#34;',$code);
	$code = preg_replace('/@/','&#64;',$code);
	$code = stripslashes($code);
	return $code;
}

function addhtml($code) {
	$code = preg_replace('/&lt;/','<',$code);
	return $code;
}

function removequotes($code) {
	$code = preg_replace("/'/",'&#39;',$code);
	$code = preg_replace('/"/','&#34;',$code);
	$code = preg_replace('/@/','&#64;',$code);
	$code = stripslashes($code);
	return $code;
}

function checklinks($code) {

	global $cagcmsurl;

#	$code = preg_replace('/href="\/\?id=(.*?)"/','href="&#46;\/\?id=$1"',$code);
#	$code = preg_replace('/href=&#34;\/\?id=(.*?)&#34;/','href="&#46;\/\?id=$1"',$code);
	$code = preg_replace('/href=(.*?)\/\?id=(.*?)"/',"href=$1$cagcmsurl\?id=$2\"",$code);
	return $code;
}

function storevars() {

	global $sourcepath;

	require_once($sourcepath.'/qwerty/config.php');
	$oldvars = array(
		"defaultid"=>$defaultid,
		"iconok"=>$iconok,
		"regok"=>$regok,
		"newsok"=>$newsok,
		"contactok"=>$contactok,
		"searchok"=>$searchok,
		"searchcode"=>$searchcode,
		"loginok"=>$loginok,
		"yabbok"=>$yabbok,
		"yabbpath"=>$yabbpath,
		"yabburl"=>$yabburl,
		"act_open"=>$act_open,
		"act_close"=>$act_close,
		"newscap"=>$newscap,
		"newsoff"=>$newsoff,
		"sitename"=>$sitename,
		"cagcmsurl"=>$cagcmsurl,
		"adminpath"=>$adminpath,
		"cagcmsadmin"=>$cagcmsadmin,
		"imgdir"=>$imgdir,
		"langpath"=>$langpath,
		"sourcepath"=>$sourcepath,
		"stylepath"=>$stylepath,
		"templatepath"=>$templatepath,
		"backuppath"=>$backuppath,
		"tinymce"=>$tinymce,
		"rooturl"=>$rooturl,
		"webmaster_email"=>$webmaster_email,
		"db"=>DB,
		"dbuser"=>DBUSER,
		"dbpass"=>DBPASS,
		"host"=>HOST,
		"cookieuser"=>$cookieuser,
		"cookiepass"=>$cookiepass,
		"cookiename"=>$cookiename,
		"cookielvl"=>$cookielvl,
		"cookielength"=>$cookielength,
	);

	return $oldvars;

}

function oldvars() {
	$varnames = array('defaultid','iconok','regok','newsok','contactok','searchok','searchcode','loginok','yabbok',
	'yabbpath','yabburl','act_open','act_close','newscap','newsoff','sitename','cagcmsurl','adminpath', 'cagcmsadmin',
	'imgdir','langpath','sourcepath','stylepath','templatepath','backuppath','tinymce','rooturl',
	'webmaster_email','db','dbuser','dbpass','host','cookieuser','cookiepass',
	'cookiename','cookielvl','cookielength');

	return $varnames;
}

function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);

    return $value;
}

?>