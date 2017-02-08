<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# news.php                                                      #
# This is the page that allows users to add or edit CAGCMS news #
# items.                                                        #
#################################################################

$cgadmin = "";

function cgstripslashes_deep($value)
{
	if(get_magic_quotes_gpc()) {
	    $value = is_array($value) ?
	                array_map('stripslashes_deep', $value) :
	                stripslashes($value);
	}
    return $value;
}

if(isset($_POST)) {
	foreach($_POST as $k=>$v) {
		$_POST[$k] = cgstripslashes_deep($v);
	}
}

function buildnewsForm($new=true, $formvals=array(), $newsid=0) {
	global $news_txt;
	global $cagcmsadmin;
	if($new) {
		$submitbtn = $news_txt['savenew'];
		$header = $news_txt['addhead'];
	}
	else {
		$submitbtn = $news_txt['saveedit'];
		$header = $news_txt['edithead'];
	}
	return '
			<form name="newsedit" action="'.$cagcmsadmin.'?action=news" method="post">
				<input type="hidden" name="newsid" id="newsid" value="'.$newsid.'" />
				<fieldset>
					<legend>'.$header.'</legend>
					<label for="newsposter">
						'.$news_txt['poster'].'
					</label>
					<input type="text" name="newsposter" id="newsposter" value="'.$formvals['newsposter'].'" />
					<br />
					<label for="newsemail">
						'.$news_txt['email'].'
					</label>
					<input type="text" name="newsemail" id="newsemail" value="'.$formvals['newsemail'].'" />
					<br />
					<label for="newssubject">
						'.$news_txt['subject'].'<span class="reqd">*</span>
					</label>
					<input type="text" name="newssubject" id="newssubject" value="'.$formvals['newssubject'].'" />
					<br />
					<label for="newscontent">
						'.$news_txt['content'].'<span class="reqd">*</span>
					</label>
					<textarea name="newscontent" id="newscontent" class="mceta">'.$formvals['newscontent'].'</textarea>
				</fieldset>
				<input type="submit" name="savenews" id="savenews" class="submit" value="'.$submitbtn.'" />
			</form>';
}

if(!empty($user->userlevel['news'])) {
	if(empty($_POST[savenews])) {
		# The user just loaded this page.  We need to find out what they want to do
		$cgadmin = '
			<form name="newsedit" id="newsedit" action="'.$cagcmsadmin.'?action=news" method="post">';
		if($user->checkperms('news', MODIFY, ALL) || $user->checkperms('news', REMOVE, ALL)) {
			# If the user has permission to modify or remove all news items, we need to retrieve a list of all of the news items that have been posted
			$sql_getnews = "SELECT newstime, username, newsposter, newssubject FROM news ORDER BY newstime";
		}
		elseif($user->checkperms('news',MODIFY,OWN) || $user->checkperms('news',REMOVE,OWN)) {
			# If the user has permission to modify or remove their own items, we need to retrieve a list of those news items
			$sql_getnews = "SELECT newstime, username, newsposter, newssubject FROM news WHERE username='".$user->username."' ORDER BY newstime";
		}
		if(!empty($sql_getnews)) {
			$cgadmin .= '
				<fieldset>
					<legend>'.$news_txt['edithead'].'</legend>
					<table class="newslist">
						<thead>
							<tr>
								<th>&nbsp;</th>
								<th>'.$news_txt['postdate'].'</th>
								<th>'.$news_txt['poster'].'</th>
								<th>'.$news_txt['subject'].'</th>
							</tr>
						</thead>
						<tbody>';
			$sql_getnews = mysql_query($sql_getnews,$link);
			# We now need to generate the list of all of the news items that can be modified or removed
			while($newsinfo = mysql_fetch_array($sql_getnews)) {
				if(!empty($newsinfo['username'])) {
					if($yabbok) {
						$uservars = getyabbuservars($memberdir."/".$newsinfo['username'].".vars");
						$poster = new user(array(0,$uservars['username'],NULL,$uservars['email'],$uservars['realname'],NULL,NULL,NULL,NULL,NULL,0));
					}
					else {
						$sql_user = "SELECT * FROM `users` WHERE `username` = '".$newsinfo['username']."'";
						$sql_user = mysql_query($sql_user,$link);
						while($userinfo = mysql_fetch_array($sql_user)) {
							$poster = new user($userinfo);
						}
					}
				}
				else {
					$poster = new user(array(0,NULL,NULL,NULL,$newsinfo['newsposter'],NULL,NULL,NULL,NULL,NULL,0));
				}

				$cgadmin .= '
							<tr>
								<td><input type="radio" name="newsid" id="newsid_'.$newsinfo['newstime'].'" value="'.$newsinfo['newstime'].'" /></td>
								<td>'.date("F d, Y",$newsinfo['newstime']).'</td>
								<td>'.$poster->displayname.'</td>
								<td>'.$newsinfo['newssubject'].'</td>
							</tr>';
			}
			$cgadmin .= '
						</tbody>
					</table>
					<fieldset>
						<legend>'.$cat_txt[2].'</legend>';
			if($user->checkperms('news',MODIFY,ALL) || $user->checkperms('news',MODIFY,OWN)) {
				# If the user has permission to modify news items, we need to generate the button that allows them to do that
				$cgadmin .= '
						<div class="checkbox">
							<input type="radio" name="newsaction" id="newsactionedit" value="edit" checked="checked" />
							<label for="newsactionedit">'.$admin_ans[5].'</label>
						</div>';
			}
			if($user->checkperms('news',REMOVE,ALL) || $user->checkperms('news',REMOVE,OWN)) {
				# If the user has permission to remove news items, we need to generate the button that allows them to do that
				$cgadmin .= '
						<div class="checkbox">
							<input type="radio" name="newsaction" id="newsactionremove" value="remove" />
							<label for="newsactionremove">'.$admin_ans[6].'</label>
						</div>';
			}
			$cgadmin .= '
						<input type="submit" name="savenews" value="'.$admin_ans[7].'" class="submit" />
					</fieldset>
				</fieldset>';
		}
		if($user->checkperms('news',CREATE,OWN) || $user->checkperms('news',CREATE,ALL)) {
			# If the user has permission to create news items, we need to generate the button that allows them to do that
			$cgadmin .= '
				<fieldset>
					<legend>'.$news_txt['addhead'].'</legend>
					<input type="submit" name="savenews" value="'.$news_txt['addhead'].'" class="submit" />
				</fieldset>';
		}
		$cgadmin .= '
			</form>';
	}
	elseif($_POST['savenews'] == $news_txt['addhead']) {
		# The user has chosen to add a news item
		$cghead = "";
		# We need to make sure that the TinyMCE editor is initiated #
		if(!empty($tinymce)) {
			$cghead .= $tmceinit;
		}
		# We now generate the news creation form #
		$cgadmin .= buildnewsForm(true);
	}
	elseif($_POST['savenews'] == $admin_ans[7] && $_POST['newsaction'] == 'remove') {
		# The user has chosen to remove the selected news item
		if(empty($_POST['newsid'])) {
			$inserted = false;
			$cgadmin .= '<div class="dbmessage">You did not select a news item to remove.  Please go back and try again.</div>';
		}
		if(is_array($_POST['newsid'])) {
			$newsid = $_POST['newsid'][0];
		}
		else {
			$newsid = $_POST['newsid'];
		}
		if(!empty($newsid)) {
			$sql_remove = "DELETE FROM news WHERE newstime = '".$_POST['newsid']."' LIMIT 1";
			if(mysql_query($sql_remove,$link)) {
				$inserted = true;
			}
			else {
				$cgadmin .= "<div class=\"dbmessage\">There was an error removing the news item you selected.".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_remove."</div></div>";
				$inserted = false;
			}
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The news item that was posted on ".date("F d, Y",$_POST['newsid'])." was removed successfully.</div>";
		}
	}
	elseif($_POST['savenews'] == $admin_ans[7] && $_POST['newsaction'] == 'edit') {
		# The user has chosen to edit an existing news item
		$cghead = "";
		# We need to make sure that the TinyMCE editor is initiated #
		if(!empty($tinymce)) {
			$cghead .= $tmceinit;
		}
		
		# We need to retrieve the content of the selected news item from the database
		if(!empty($_POST['newsid'])) {
			if(is_array($_POST['newsid'])) {
				$newsid = $_POST['newsid'][0];
			}
			else {
				$newsid = $_POST['newsid'];
			}
			$sql_retrieve = "SELECT * FROM news WHERE newstime='$newsid' LIMIT 1";
			$sql_retrieve = mysql_query($sql_retrieve);
			$formvals = mysql_fetch_array($sql_retrieve);

			# We now generate the news editing form #
			$cgadmin .= buildnewsForm(false,$formvals,$newsid);
		}
		else {
			$cgadmin .= '<div class="dbmessage">You did not select a news item to modify.  Please go back and try again.</div>';
		}
	}
	elseif($_POST['savenews'] == $news_txt['savenew'] || $_POST['savenews'] == $news_txt['saveedit']) {
	
		$errorArr = array();
		if(empty($_POST['newssubject'])) {
			$errorArr['newssubject'] = $news_txt['subject'];
		}
		if(empty($_POST['newscontent'])) {
			$errorArr['newscontent'] = $news_txt['content'];
		}
		
		if(count($errorArr)) {
			$inserted = false;
			$cgadmin .= "<div class=\"dbmessage\">There was an error updating or inserting this information. Following is a list of the required information that was missing:</div><ul>";
			foreach($errorArr as $val) {
				$cgadmin .= "<li>".$val."</li>";
			}
			$cgadmin .= "</ul>";
		}
		else {
	
			$link = mysql_connect(HOST,DBUSER,DBPASS);
			mysql_select_db(DB, $link);
	
			if(!empty($_POST['newsid'])) {
				$newstime = $_POST['newsid'];
				$sql_retrieve = mysql_query("SELECT * FROM news WHERE newstime = '$newstime' LIMIT 1",$link);
				$oldnews = mysql_fetch_array($sql_retrieve);
				$newsvals = $_POST;
				foreach($newsvals as $key=>$val) {
					if(isset($oldnews[$key])) {
						if($oldnews[$key] == $newsvals[$key]) {
							unset($newsvals[$key]);
						}
						elseif(!is_numeric($val)) {
							$insertvals[] = $key." = '".mysql_real_escape_string($val,$link)."'";
						}
						else {
							$insertvals[] = $key.' = '.$val;
						}
					}
					else {
						unset($newsvals[$key]);
					}
				}
				if(count($insertvals)) {
					$sql_insert = "UPDATE news SET ".implode(', ',$insertvals)." WHERE newstime=".$newstime;
				}
			}
			else {
				$newstime = time();
				$newsvals = $_POST;
				if(!empty($_POST['newsposter'])) {
					$guestposter = "'".mysql_real_escape_string($_POST['newsposter'],$link)."'";
				}
				else {
					$guestposter = "NULL";
				}
				if(!empty($_POST['newsemail'])) {
					$newsemail = "'".mysql_real_escape_string($_POST['newsemail'],$link)."'";
				}
				else {
					$newsemail = "NULL";
				}
				if($guestposter == "NULL" && !empty($user->username)) {
					$username = "'".mysql_real_escape_string($user->username,$link)."'";
				}
				else {
					$username = "NULL";
				}
				$sql_insert = "INSERT INTO `news` VALUES($newstime,$username,$guestposter,$newsemail,'".mysql_real_escape_string($_POST['newssubject'],$link)."','".mysql_real_escape_string($_POST['newscontent'],$link)."',NULL)";
			}
			if(mysql_query($sql_insert, $link)) {
				$inserted = true;
			}
			else {
				$inserted = false;
				$cgadmin .= "<div class=\"dbmessage\">There was an error updating or inserting this information. Please <a href='javascript:history.go(-1);' title='Back'>go back</a> and try again.<br />".mysql_error()."</div><div>Following is the query you attempted to execute:<div>".$sql_insert."</div></div>";
			}
		}
		if($inserted) {
			$cgadmin .= "<div class=\"dbmessage\">The news item was updated or inserted successfully.</div>";
		}
	}
}
else {
	$cgadmin .= $error_txt[1];
}

?>
