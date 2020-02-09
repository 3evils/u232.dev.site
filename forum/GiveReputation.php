<?php
/************************************
*	GiveReputation.php				*
*************************************
*	A penny for your thoughts!		*
*************************************
*		How to use this file:	    *
*									*
*	Upload into your forum's base	*
*	directory, alongside index.php	*
*	and SSI.php, and navigate to	*
*	it in your browser. Delete it	*
*	when you're done with it.		*
************************************/

include_once('SSI.php');

if(!$user_info['is_admin'])
	die('Admins only!');

$output = '';

// Want to do more than 100 at a time? Change this variable.
$amount = 100;

$smf2 = isset($smcFunc);
$smcFunc = isset($smcFunc) ? $smcFunc : array();

function updateReputation($memID){
	global $user_profile, $modSettings, $smcFunc, $output, $db_prefix, $smf2;

	// If this was a delete member, skip it
	if(!loadMemberData($memID))
		return;

	$total_posts = $user_profile[$memID]['posts'];
	$current_karma = $user_profile[$memID][$smf2 ? 'karma_good' : 'karmaGood'];

	// Number of topics started.
	// !!!SLOW This query is sorta slow...
	if($smf2){
		$result = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}topics
			WHERE id_member_started = {int:current_member}' . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? '
				AND id_board != {int:recycle_board}' : ''),
			array(
				'current_member' => $memID,
				'recycle_board' => $modSettings['recycle_board'],
			)
		);

		list ($topics) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);
	} else {
		$result = db_query("
			SELECT COUNT(*)
			FROM {$db_prefix}topics
			WHERE ID_MEMBER_STARTED = {$memID}" . (!empty($modSettings['recycle_enable']) && ($modSettings['recycle_board'] > 0) ? ("
				AND ID_BOARD != " . $modSettings['recycle_board']) : ""), __FILE__, __LINE__);

		list ($topics) = mysql_fetch_row($result);
		mysql_free_result($result);
	}

	// No double dipping!
	$posts = $total_posts - $topics;

	$pointsTotal = $modSettings['karmaRegistration'] + $current_karma + ($posts * $modSettings['karmaValuePost']) + ($topics * $modSettings['karmaValueThread']);

	if($smf2){
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}members
			SET karma_good = {int:newkarma},
				karma_bad = 0
			WHERE id_member = {int:member}',
			array (
				'newkarma' => $pointsTotal,
				'member' => $memID,
			)
		);
	} else {
		db_query("
			UPDATE {$db_prefix}members
			SET karmaGood = {$pointsTotal},
				karmaBad = 0
			WHERE id_member = {$memID}", __FILE__, __LINE__);
	}

	$output .= "User #" . $memID . " received " . $pointsTotal . " reputation points for " . $topics . " topics and " . $total_posts . " posts.<br />\n";
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Reputation Jumpstarter</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex" />
	<style type="text/css">
		body {
			text-align: left;
			background-color: #efefef;
			color: black;
			font: normal 80% "Lucida Grande", Verdana, Arial, sans-serif;
			}
		#directory {
			background-color: #ddd;
			border: 1px solid black;
			margin: 2em auto 1em;;
			padding: 0px;
			width: 751px;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			}
		#title {
			background-color: #bdbdbd;
			border-top: 2px solid #d2d2d2;
			border-bottom: 1px solid #999;
			font-size: 95%;
			margin: 0px;
			padding: 5px;
			color: black;
			-moz-border-radius-topleft: 5px;
			-moz-border-radius-topright: 5px;
			-webkit-border-radius-topleft: 5px;
			-webkit-border-radius-topright: 5px;
			}
		#title span {
			color: #507508;
			}
		#listing {
			padding: 10px;
			}
		a, a:link, a:visited, a:hover, a:focus {
			text-decoration: none;
			outline: 0;
			color: #507508;
			}
	</style>
</head>
<body>
<div id="directory">
	<h1 id="title" title="Power is truth. Shinra is the future."><span>Reputation Jumpstarter</span></h1>
	<div id="listing">
<?php
if (isset($_POST['continue'])){

	if (isset($_POST['start']) && ((int) $_POST['start']) > 0){
		$start = (int) $_POST['start'];
		$end = $start + $amount;
	} else {
		$start = 1;
		$end = $amount;
	}

	if ($smf2){
		$result = $smcFunc['db_query']('', '
			SELECT MAX(id_member)
			FROM {db_prefix}members',
			array()
		);

		list ($memberCount) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);
	} else {
		$result = db_query("
			SELECT MAX(ID_MEMBER)
			FROM {$db_prefix}members", __FILE__, __LINE__);

		list ($memberCount) = mysql_fetch_row($result);
		mysql_free_result($result);
	}

	$end = min($end, $memberCount);

	for($i = $start; $i <= $end; $i++)
		updateReputation($i);

	if ($memberCount > $end)
		echo '
		<form action="./GiveReputation.php" method="post">
			<input type="hidden" name="start" value="', ($end + 1), '" />
			Members ', $start, ' through ', $end, ' have been updated. <input type="submit" name="continue" value="Continue" />
		</form><br />';
	else
		echo '
		Members ', $start, ' through ', $end, ' have been updated.<br />';

	echo '
		<br />
		<b>Debugging Information:</b><br />
		', $output;

} else {

	echo '
		<form action="./GiveReputation.php" method="post">
			<p>Welcome to the Reputation Jumpstarter. This script will guide you through the process for giving reputation based on prior posts and topics.</p>
			<p>These are the settings that will be used to give reputation to your members. You can change these settings in your admin center. After you\'re done, just press &quot;Continue&quot; and we\'ll be on our way.</p>
			<div style="height: 40px;"></div>
			<table width="100%">
				<tr>
					<td width="60%">
						<b>Give everyone</b><br />
						<span class="smalltext">How many points should everyone automatically get?</span>
					</td>
					<td>
						', $modSettings['karmaRegistration'], ' Points
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
					</td>
				</tr>
				<tr>
					<td><b>Points per post</b></td>
					<td>
						', $modSettings['karmaValuePost'], ' Points
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<hr />
					</td>
				</tr>
				<tr>
					<td><b>Points per topic</b></td>
					<td>
						', $modSettings['karmaValueThread'], ' Points
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style="height: 40px;"></div>
					</td>
				<tr>
					<td colspan="2" align="right">
						<input type="submit" id="continue" name="continue" value="Continue" />
					</td>
				</tr>
			</table>
		</form>';
}
?>
	</div>
</div>
</body>
</html>