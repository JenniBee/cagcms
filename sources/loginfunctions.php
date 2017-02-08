<?

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# loginfunctions.php                                            #
# This file contains the login, logout and yabblogin functions  #
# to allow the user to login and logout                         #
#################################################################

require_once($sourcepath.'/functions.php');

if($yabbok) {
	$myyabbpaths = file_get_contents($yabbpath."/Paths.pl");
	$myyabbpaths = str_replace("./",$yabbpath.'/',$myyabbpaths);
	$myyabbpaths = parseyabb($myyabbpaths);
	eval($myyabbpaths);
	$myyabbpaths = file_get_contents($vardir."/Settings.pl");
	$myyabbpaths = parseyabb($myyabbpaths);
	eval($myyabbpaths);
}

if($yabbok) {
	$cookienames = array('cookieusername'=>$cookieusername,'cookiepassword'=>$cookiepassword,'cookiesession_name'=>$cookiesession_name);
}
else {
	$cookienames = array('cookieuser'=>$cookieuser);
}

function logout($cookienames) {
	if(count($cookienames) === 1) {
		$cookieuser = $cookienames['cookieuser'];
		$yabbok = false;
	}
	else {
		$cookieusername = $cookienames['cookieusername'];
		$cookiepassword = $cookienames['cookiepassword'];
		$cookiesession_name = $cookienames['cookiesession_name'];
		$yabbok = true;
	}
	$cookielength = time()-100;
	if(!$yabbok) {
		foreach($_COOKIE[$cookieuser] as $key=>$value) {
			setcookie($cookieuser.'['.$key.']',"",$cookielength);
		}
	}
	else {
		setcookie($cookieusername,"",$cookielength);
		setcookie($cookiepassword,"",$cookielength);
		setcookie($cookiesession_name,"",$cookielength);
	}
	return true;
}

function yabblogin($loginvars) {
	# $loginvars is an array of the following values:  array of cookienames, username, YaBB memberdir, cookie_length, CAGCMS rooturl
	$cookienames = $loginvars['cookienames'];
	$username = $loginvars['username'];
	$password = $loginvars['password'];
	$memberdir = $loginvars['memberdir'];
	$cookielength = $loginvars['cookielength'];
	$rooturl = $loginvars['rooturl'];
	$uservars = getyabbuservars($memberdir.'/'.$username.'.vars');
	if(md5_base64($password) == $uservars['password']) {
		$userip = $_SERVER['REMOTE_ADDR'];
		$cookieusername = $cookienames['cookieusername'];
		$cookiepassword = $cookienames['cookiepassword'];
		$cookiesession_name = $cookienames['cookiesession_name'];
		setcookie($cookieusername,$loginvars['username'],time()+$cookielength);
		setcookie($cookiepassword,$uservars['password'],time()+$cookielength);
		setcookie($cookiesession_name,md5_base64($userip),time()+$cookielength);
		return true;
	}
	else {
		return false;
	}
}

function getyabbuservars($filename) {
	if(file_exists($filename)) {
		$sql_user = file_get_contents($filename);
	}
	else {
		echo "$filename<br />";
		die("The file does not exist");
	}
	$user = split("\n",$sql_user);
	foreach($user as $temp) {
		if(preg_match("/['\"](.*?)['\"],['\"](.*?)['\"]/",$temp,$matches)) {
			$uservars[$matches[1]] = $matches[2];
		}
	}
	unset($sql_user);
	if($uservars['position'] == "Administrator") {
		for($i=0;$i<=6;$i++) {
			$tempvars[$i] = TOTAL;
		}
		$uservars['position'] = join("|",$tempvars);
	}
	return $uservars;
}

function login($cookieuser, $cookielength) {
	$link = mysql_connect(HOST,DBUSER,DBPASS);
	mysql_select_db(DB, $link);

	$sqluser = $_POST{'username'};
	$sql = "SELECT * FROM `users` WHERE `username` = '$sqluser'";
	$sql = mysql_query($sql, $link);
	if(mysql_num_rows($sql) == 0) {
		echo "<div>Sorry, that username does not exist in our database.</div>";
		return false;
	}
	else {
		while($logs = mysql_fetch_array($sql)) {
			$testpass = md5_base64($_POST{'passwrd'});
			$cookielength = time()+$cookielength;
			if($logs[2] == $testpass) {
				$user = new user($logs);
				$sql = "SELECT `permissions` FROM `usergroups` WHERE `groupid` = ".$user->userlevel;
				$sql = mysql_query($sql,$link);
				while($userinfo = mysql_fetch_array($sql)) {
					$user->userlevel = $userinfo[0];
				}
				$timestamp = time();
				$sql_ip = mysql_query("SELECT `userip` FROM `users` WHERE `userid` = '".$user->userid."'", $link);
				while($ips = mysql_fetch_array($sql_ip)) {
					$ip_adds = explode("|",$ips[userip]);
					$iplist = array($REMOTE_ADDR);
					for($i=0;$i<count($ip_adds);$i++) {
						if($ip_adds[$i] != $REMOTE_ADDR && !empty($ip_adds[$i])) {
							$j = $i+1;
							$iplist[$j] = $ip_adds[$i];
						}
					}
					if(count($iplist) >= 3) {
						$iplist = array_slice($iplist,0,3);
					}
					$user->userip = $iplist;
					$ip_adds = implode("|",$iplist);
				}
				setcookie($cookieuser.'[username]',$user->username,$cookielength);
				setcookie($cookieuser.'[userpass]',$user->userpass,$cookielength);
				if(!mysql_query("UPDATE `users` SET `userip` = '$ip_adds', `userlastclicktime` = '$timestamp', `userlogin` = '$timestamp' WHERE `username` = '$logs[1]' LIMIT 1", $link)) { die("There was an error updating the user's record in the database.".mysql_error()); }
				return true;
			}
			else {
				echo "<div>Sorry, the password you entered does not match the password in our database.  Please try again.</div>";
				return false;
			}
		}
	}
}

?>