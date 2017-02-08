<?php

#################################################################
# CAGCMS - CAG's Content Management System                      #
# Developed by Curtiss Grymala for private use and for use by   #
# supporters of open-source freeware.                           #
#################################################################
# Version 0.3 beta                                              #
# Released: September 2, 2006                                   #
#################################################################
# polls.php                                                     #
# This is the main file that parses all of the content and      #
# variables.                                                    #
#################################################################

unset($cgpollblock);
$cgpollblock = "";

# If the user just voted in the poll, we need to store that vote in the database #

if($_POST[pollsubmit] == 'Vote') {
	foreach($_POST as $key=>$value) {
		if(empty($value)) {
			unset($$key);
		}
	}
	foreach($_POST[pollchoice] as $polloption) {
		if(!empty($value)) {
			$pollchoice = $polloption;
		}
	}
	$sql_insertpoll = "UPDATE `polloptions` SET `votes` = `votes` + 1 WHERE `optionid` = '$polloption'";
#	echo $sql_insertpoll;
	if(!mysql_query($sql_insertpoll)) {
		die("There was an error storing your poll vote.  Please go back and try again.".mysql_error());
	}
	unset($sql_insertpoll);
	$sql_insertpoll = "INSERT INTO `pollvotes` (`pollid`, `optionid`, `voterip`) VALUES ('$_POST[pollid]','$polloption','$_POST[voterip]')";
#	echo  $sql_insertpoll;
	if(!mysql_query($sql_insertpoll)) {
		die("There was an error storing your poll vote.  Please go back and try again.".mysql_error());
	}
	unset($sql_insertpoll);
}

# Let's begin pulling our poll information from the database #

$sql = mysql_query("SELECT * FROM `polls`",$link);
while($polls = mysql_fetch_array($sql)) {

	# We first need to check to see if this poll is within it's set timeframe #

	if(empty($polls[startdate])) {
		$starttime = time();
	}
	else {
		$starttime = strtotime($polls[startdate]);
	}
	if(empty($polls[enddate])) {
		$endtime = time();
		$endtime = $endtime + 1000;
	}
	else {
		$endtime = strtotime($polls[enddate]);
	}
	$curtime = time();
	if($curtime >= $starttime && $curtime <= $endtime) {
		$timeok = 1;
	}
	else {
		unset($timeok);
	}

	# Now, we'll check to see if this poll belongs on this page #

	$pollpages = explode("|",$polls[pollpages]);
	foreach($pollpages as $pollpage) {
		if(($pollpage == "allcagcmspages" || $pollpage == $id) && !empty($timeok)) {
			$cgpollblock .= "
			<h1>Polls</h1>
			<ul class='pollblock'>
				<li class='polltitle'>
					$polls[pollname]
				</li>
				<li>";

	# Now, we check to see if this user has already voted in this poll #

			$sqlvotes = mysql_query("SELECT * FROM `pollvotes` WHERE `pollid` = '$polls[pollid]'",$link);
			unset($voted);
			while($pollvotes = mysql_fetch_array($sqlvotes)) {
				if($pollvotes[voterip] == $REMOTE_ADDR) {
					$voted = 1;
					$voted_for = $pollvotes[optionid];
				}
			}

	# If the user has not already voted in this poll, we'll generate the poll for the user to vote in #

			if(empty($voted)) {
				$cgpollblock .= "
			<form name='poll' action='".selfURL()."' method='post'>
			<input type='hidden' name='pollid' value='$polls[pollid]' />
			<input type='hidden' name='voterip' value='$REMOTE_ADDR' />
			<table class='polloptions'>
				<caption>$polls[pollquestion]</caption>";
				$sql_polls = mysql_query("SELECT * FROM `polloptions` WHERE `pollid` = $polls[pollid]", $link);
				while($polloptions = mysql_fetch_array($sql_polls)) {
					$cgpollblock .= "
				<tr valign='top'>
					<td>
						<input type='radio' name='pollchoice[]' id='polloption_$polloptions[optionid]' value='$polloptions[optionid]' class='radio' />
					</td>
					<td>
						<label for='polloption_$polloptions[optionid]'>$polloptions[option]</label>
					</td>
				</tr>";
				}
				$cgpollblock .= "
				<tr>
					<td colspan='2' align='right'>
						<input type='submit' value='Vote' name='pollsubmit' class='submit' />
					</td>
				</tr>
			</table>
			</form>
			</li>
		</ul>";
			}

	# If the user has already voted, we'll generate the poll results #

			else {
				$cgpollblock .= "
			<table class='polloptions'>
				<caption>$polls[pollquestion]</caption>
				<thead>
				<tr valign='top'>
					<td>
						Option
					</td>
					<td>
						Votes
					</td>
				</tr>
				</thead>";
				$sql_polls = mysql_query("SELECT * FROM `polloptions` WHERE `pollid` = '$polls[pollid]' ORDER BY `votes` DESC",$link);
				while($pollresults = mysql_fetch_array($sql_polls)) {
					if($voted_for == $pollresults[optionid]) {
						$active = "<img src='$imgdir/voted.gif' alt='You voted for this poll option' class='voted' />";
					}
					else {
						$active = "";
					}
					$cgpollblock .= "
				<tr>
					<td>$active $pollresults[option]</td>
					<td>$pollresults[votes]</td>
				</tr>";
				}
				$cgpollblock .= "
			</table>
			</li>
		</ul>";
			}
		}
	}
}

?>