<?php
define("ALL",1);
define("SEC",2);
define("CAT",4);
define("CONT",8);
define("CREATE",1);
define("MODIFY",16);
define("REMOVE",256);
define("VIEW",4096);
define("OWNNEWS",2);
define("USERGROUPS",2);
define("INDUSERS",4);
define("SELF",8);
define("SETTINGS",1);
define("PATHS",2);
define("MAINT",4);
define("REPORTS",8);
define("TOTAL",65535);

class user {
	function __construct($userinfo) {
		$this->userid = $userinfo[0];
		$this->username = $userinfo[1];
		$this->userpass = $userinfo[2];
		$this->useremail = $userinfo[3];
		$this->displayname = $userinfo[4];
		$this->userlevel = $userinfo[5];
		$this->userip = explode("|",$userinfo[6]);
		$this->userreg = $userinfo[7];
		$this->lastlogin = $userinfo[8];
		$this->lastclicktime = $userinfo[9];
		$this->lastclick = $userinfo[10];
	}
	function userperms($userperms) {
		$keys = array("sec","cat","cont","indusers","group","news","pref");
		$i = 0;
		foreach($keys as $key) {
			$temparray[$key] = (int)$userperms[$i];
			$i++;
		}
		return $temparray;
	}
	function checkperms($userperm, $check1, $check2) {
		$check = $check1*$check2;
		$userperm = $this->userlevel[$userperm];
		if($check&$userperm) {
			return true;
		}
		else {
			return false;
		}
	}
}
?>