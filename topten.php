<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                                            |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5                                            |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.               |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
//==Topten by thehippy Updated for 09
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once INCL_DIR . 'html_functions.php';
dbconn(true);
$lang = array_merge(load_language('global') , load_language('topten'));
$HTMLOUT = '';
function mysql_fetch_rowsarr($result, $numass = MYSQLI_BOTH)
{
    $i = 0;
    $keys = array_keys(mysqli_fetch_array($result, $numass));
    mysqli_data_seek($result, 0);
    while ($row = mysqli_fetch_array($result, $numass)) {
        foreach ($keys as $speckey) {
            $got[$i][$speckey] = $row[$speckey];
        }
        $i++;
    }
    return $got;
}

function size($bytes) {
    $bytes = max(0, (int)$bytes);
    if ($bytes < 1024000) return round(($bytes / 1024000) * 100).'%'; //kb
	elseif ($bytes < 1048576000) return round(($bytes / 1048576000) * 100).'%';//mb
    elseif ($bytes < 1073741824000) return round(($bytes / 1073741824000) * 100).'%';//gb
    elseif ($bytes < 1099511627776000) return round(($bytes / 1099511627776000) * 100).'%';//tb
    elseif ($bytes < 1125899906842624000) return round(($bytes / 1125899906842624000) * 1000).'%';//pb
    elseif ($bytes < 1152921504606846976000) return round(($bytes / 1152921504606846976000) * 1000).'%';//exabyte
    elseif ($bytes < 1180591620717411303424000) return round(($bytes / 1180591620717411303424000),10000).'%'; //zettabyte
    elseif ($bytes < 1208925819614629174706176) return round(($bytes / 1208925819614629174706176),10000).'%'; //yottabyte
	return number_format($bytes);
}
$HTMLOUT.= "<div class='callout'>";
$HTMLOUT.= "<a href='topten.php'>Users</a> | <a href='topten.php?view=t'>Torrents</a> | <a href='topten.php?view=c'>Countries</a>";
if (isset($_GET['view']) && $_GET['view'] == "t") {
    $view = strip_tags(isset($_GET["t"]));
    // Top Torrents
    $HTMLOUT.= "<h2>Top 10 Most Active Torrents</h2>";
    $result = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY seeders + leechers DESC, seeders DESC, added ASC LIMIT 10");
    $counted = mysqli_num_rows($result);
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $tor1 = $arr[0]["name"];
        $tot1 = $arr[0]["leechers"] + $arr[0]["seeders"];
        $tor2 = $arr[1]["name"];
        $tot2 = $arr[1]["leechers"] + $arr[1]["seeders"];
        $tor3 = $arr[2]["name"];
        $tot3 = $arr[2]["leechers"] + $arr[2]["seeders"];
        $tor4 = $arr[3]["name"];
        $tot4 = $arr[3]["leechers"] + $arr[3]["seeders"];
        $tor5 = $arr[4]["name"];
        $tot5 = $arr[4]["leechers"] + $arr[4]["seeders"];
        $tor6 = $arr[5]["name"];
        $tot6 = $arr[5]["leechers"] + $arr[5]["seeders"];
        $tor7 = $arr[6]["name"];
        $tot7 = $arr[6]["leechers"] + $arr[6]["seeders"];
        $tor8 = $arr[7]["name"];
        $tot8 = $arr[7]["leechers"] + $arr[7]["seeders"];
        $tor9 = $arr[8]["name"];
        $tot9 = $arr[8]["leechers"] + $arr[8]["seeders"];
        $tor10 = $arr[9]["name"];
        $tot10 = $arr[9]["leechers"] + $arr[9]["seeders"];
        $HTMLOUT.= "$imgstartpie&amp;chd=t:$tot1,$tot2,$tot3,$tot4,$tot5,$tot6,$tot7,$tot8,$tot9,$tot10&amp;chl=$tor1($tot1)|$tor2($tot2)|$tor3($tot3)|$tor4($tot4)|$tor5($tot5)|$tor6($tot6)|$tor7($tot7)|$tor8($tot8)|$tor9($tot9)|$tor10($tot10)\" alt='' />";
    } else {
        $HTMLOUT.= "<h4>Insufficient Torrents (" . $counted . ")</h4>";
    }
    $HTMLOUT.= "<h2>Top 10 Most Snatched Torrents</h2>";
    $result = sql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY times_completed DESC LIMIT 10");
    $counted = mysqli_num_rows($result);
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $tor1 = $arr[0]["name"];
        $tot1 = $arr[0]["times_completed"];
        $tor2 = $arr[1]["name"];
        $tot2 = $arr[1]["times_completed"];
        $tor3 = $arr[2]["name"];
        $tot3 = $arr[2]["times_completed"];
        $tor4 = $arr[3]["name"];
        $tot4 = $arr[3]["times_completed"];
        $tor5 = $arr[4]["name"];
        $tot5 = $arr[4]["times_completed"];
        $tor6 = $arr[5]["name"];
        $tot6 = $arr[5]["times_completed"];
        $tor7 = $arr[6]["name"];
        $tot7 = $arr[6]["times_completed"];
        $tor8 = $arr[7]["name"];
        $tot8 = $arr[7]["times_completed"];
        $tor9 = $arr[8]["name"];
        $tot9 = $arr[8]["times_completed"];
        $tor10 = $arr[9]["name"];
        $tot10 = $arr[9]["times_completed"];
        $HTMLOUT.= "$imgstartpie&amp;chd=t:$tot1,$tot2,$tot3,$tot4,$tot5,$tot6,$tot7,$tot8,$tot9,$tot10&amp;chl=$tor1($tot1)|$tor2($tot2)|$tor3($tot3)|$tor4($tot4)|$tor5($tot5)|$tor6($tot6)|$tor7($tot7)|$tor8($tot8)|$tor9($tot9)|$tor10($tot10)\" alt='' />";
    } else {
        $HTMLOUT.= "<h4>Insufficient Torrents (" . $counted . ")</h4>";
    }
    echo stdhead($lang['head_title']) . $HTMLOUT . stdfoot();
    die();
}
if (isset($_GET['view']) && $_GET['view'] == "c") {
    $view = strip_tags(isset($_GET["c"]));
    // Top Countries
    $HTMLOUT.= "<h2>Top 10 Countries (users)</h2>";
    $result = sql_query("SELECT name, flagpic, COUNT(users.country) as num FROM countries LEFT JOIN users ON users.country = countries.id GROUP BY name ORDER BY num DESC LIMIT 10");
    $counted = mysqli_num_rows($result);
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $name1 = $arr[0]["name"];
        $num1 = $arr[0]["num"];
        $name2 = $arr[1]["name"];
        $num2 = $arr[1]["num"];
        $name3 = $arr[2]["name"];
        $num3 = $arr[2]["num"];
        $name4 = $arr[3]["name"];
        $num4 = $arr[3]["num"];
        $name5 = $arr[4]["name"];
        $num5 = $arr[4]["num"];
        $name6 = $arr[5]["name"];
        $num6 = $arr[5]["num"];
        $name7 = $arr[6]["name"];
        $num7 = $arr[6]["num"];
        $name8 = $arr[7]["name"];
        $num8 = $arr[7]["num"];
        $name9 = $arr[8]["name"];
        $num9 = $arr[8]["num"];
        $name10 = $arr[9]["name"];
        $num10 = $arr[9]["num"];
	$HTMLOUT.= "<ul class='bar-graph'>
		<li class='bar-graph-axis'>
			<div class='bar-graph-label'>100%</div>
			<div class='bar-graph-label'>80%</div>
			<div class='bar-graph-label'>60%</div>
			<div class='bar-graph-label'>40%</div>
			<div class='bar-graph-label'>20%</div>
			<div class='bar-graph-label'>0%</div>
		</li>
		<li class='bar primary' style='height: " .size($num1) . ";' title='" . size($num1) . "'>
			<div class='percent'>" . mksize($num1) . "</div>
			<div class='description'>$name1</div>
		</li>
		<li class='bar secondary' style='height: " .size($num2) . ";' title='" . size($num2) . "'>
			<div class='percent'>" . mksize($num2) . "</div>
			<div class='description'>$name2</div>
		</li>
		<li class='bar success' style='height: " .size($num3) . ";' title='" . size($num3) . "'>
			<div class='percent'>" . mksize($num3) . "</div>
			<div class='description'>$name3</div>
		</li>
		<li class='bar warning' style='height: " . size($num4) . ";' title='" . size($num4) . "'>
			<div class='percent'>" . mksize($num4) . "</div>
			<div class='description'>$name4</div>
		</li>
		<li class='bar alert' style='height: " . size($num5) . ";' title='" . size($num5) . "'>
			<div class='percent'>" . mksize($num5) . "</div>
			<div class='description'>$name5</div>
		</li>
		<li class='bar primary' style='height: " .size($num6) . ";' title='" . size($num6) . "'>
			<div class='percent'>" . mksize($num6) . "</div>
			<div class='description'>$name6</div>
		</li>
		<li class='bar secondary' style='height: " .size($num7) . ";' title='" . size($num7) . "'>
			<div class='percent'>" . mksize($num7) . "</div>
			<div class='description'>$name7</div>
		</li>
		<li class='bar success' style='height: " .size($num8) . ";' title='" . size($num8) . "'>
			<div class='percent'>" . mksize($num8) . "</div>
			<div class='description'>$name8</div>
		</li>
		<li class='bar warning' style='height: " . size($num9) . ";' title='" . size($num9) . "'>
			<div class='percent'>" . mksize($num9) . "</div>
			<div class='description'>$name9</div>
		</li>
		<li class='bar alert' style='height: " . size($num10) . ";' title='" . size($num10) . "'>
			<div class='percent'>" . mksize($num10) . "</div>
			<div class='description'>$name10</div>
		</li>
	</ul>"; 
    } else {
        $HTMLOUT.= "<h4>Insufficient Countries (" . $counted . ")</h4></div>";
    }
    $HTMLOUT.= "<h2>Top 10 Countries (total uploaded)</h2>";
    $result = sql_query("SELECT c.name, c.flagpic, sum(u.uploaded) AS ul FROM users AS u LEFT JOIN countries AS c ON u.country = c.id WHERE u.enabled = 'yes' GROUP BY c.name ORDER BY ul DESC LIMIT 10");
    $counted = mysqli_num_rows($result);
    if ($counted == "10") {
        $arr = mysql_fetch_rowsarr($result);
        $name1 = $arr[0]["name"];
        $num1 = $arr[0]["ul"];
        $name2 = $arr[1]["name"];
        $num2 = $arr[1]["ul"];
        $name3 = $arr[2]["name"];
        $num3 = $arr[2]["ul"];
        $name4 = $arr[3]["name"];
        $num4 = $arr[3]["ul"];
        $name5 = $arr[4]["name"];
        $num5 = $arr[4]["ul"];
        $name6 = $arr[5]["name"];
        $num6 = $arr[5]["ul"];
        $name7 = $arr[6]["name"];
        $num7 = $arr[6]["ul"];
        $name8 = $arr[7]["name"];
        $num8 = $arr[7]["ul"];
        $name9 = $arr[8]["name"];
        $num9 = $arr[8]["ul"];
        $name10 = $arr[9]["name"];
        $num10 = $arr[9]["ul"];
	$HTMLOUT.= "<ul class='bar-graph'>
		<li class='bar-graph-axis'>
			<div class='bar-graph-label'>100%</div>
			<div class='bar-graph-label'>80%</div>
			<div class='bar-graph-label'>60%</div>
			<div class='bar-graph-label'>40%</div>
			<div class='bar-graph-label'>20%</div>
			<div class='bar-graph-label'>0%</div>
		</li>
		<li class='bar primary' style='height: " .size($num1) . ";' title='" . size($num1) . "'>
			<div class='percent'>" . mksize($num1) . "</div>
			<div class='description'>$name1</div>
		</li>
		<li class='bar secondary' style='height: " .size($num2) . ";' title='" . size($num2) . "'>
			<div class='percent'>" . mksize($num2) . "</div>
			<div class='description'>$name2</div>
		</li>
		<li class='bar success' style='height: " .size($num3) . ";' title='" . size($num3) . "'>
			<div class='percent'>" . mksize($num3) . "</div>
			<div class='description'>$name3</div>
		</li>
		<li class='bar warning' style='height: " . size($num4) . ";' title='" . size($num4) . "'>
			<div class='percent'>" . mksize($num4) . "</div>
			<div class='description'>$name4</div>
		</li>
		<li class='bar alert' style='height: " . size($num5) . ";' title='" . size($num5) . "'>
			<div class='percent'>" . mksize($num5) . "</div>
			<div class='description'>$name5</div>
		</li>
		<li class='bar primary' style='height: " .size($num6) . ";' title='" . size($num6) . "'>
			<div class='percent'>" . mksize($num6) . "</div>
			<div class='description'>$name6</div>
		</li>
		<li class='bar secondary' style='height: " .size($num7) . ";' title='" . size($num7) . "'>
			<div class='percent'>" . mksize($num7) . "</div>
			<div class='description'>$name7</div>
		</li>
		<li class='bar success' style='height: " .size($num8) . ";' title='" . size($num8) . "'>
			<div class='percent'>" . mksize($num8) . "</div>
			<div class='description'>$name8</div>
		</li>
		<li class='bar warning' style='height: " . size($num9) . ";' title='" . size($num9) . "'>
			<div class='percent'>" . mksize($num9) . "</div>
			<div class='description'>$name9</div>
		</li>
		<li class='bar alert' style='height: " . size($num10) . ";' title='" . size($num10) . "'>
			<div class='percent'>" . mksize($num10) . "</div>
			<div class='description'>$name10</div>
		</li>
	</ul>"; 
    } else {
        $HTMLOUT.= "<h4>Insufficient Countries (" . $counted . ")</h4></div>";
    }
    echo stdhead($lang['head_title']) . $HTMLOUT . stdfoot();
    die();
}
// Default display / Top Users
$HTMLOUT.= "<h2>Top 10 Uploaders</h2>";
$result = sql_query("SELECT username, uploaded FROM users WHERE enabled = 'yes' ORDER BY uploaded DESC LIMIT 10");
$counted = mysqli_num_rows($result);
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['uploaded'];
    $upped2 = $arr[1]['uploaded'];
    $upped3 = $arr[2]['uploaded'];
    $upped4 = $arr[3]['uploaded'];
    $upped5 = $arr[4]['uploaded'];
    $upped6 = $arr[5]['uploaded'];
    $upped7 = $arr[6]['uploaded'];
    $upped8 = $arr[7]['uploaded'];
    $upped9 = $arr[8]['uploaded'];
    $upped10 = $arr[9]['uploaded'];
			$HTMLOUT.= "<ul class='bar-graph'>
			  <li class='bar-graph-axis'>
    <div class='bar-graph-label'>100%</div>
    <div class='bar-graph-label'>80%</div>
    <div class='bar-graph-label'>60%</div>
    <div class='bar-graph-label'>40%</div>
    <div class='bar-graph-label'>20%</div>
    <div class='bar-graph-label'>0%</div>
  </li>
		  <li class='bar primary' style='height: " .size($upped1) . ";' title='" . size($upped1) . "'>
			<div class='percent'>" . mksize($upped1) . "</div>
			<div class='description'>$user1</div>
		  </li>
		  <li class='bar secondary' style='height: " .size($upped2) . ";' title='" . size($upped2) . "'>
			<div class='percent'>" . mksize($upped2) . "</div>
			<div class='description'>$user2</div>
		  </li>
		  <li class='bar success' style='height: " .size($upped3) . ";' title='" . size($upped3) . "'>
			<div class='percent'>" . mksize($upped3) . "</div>
			<div class='description'>$user3</div>
		  </li>
		  <li class='bar warning' style='height: " . size($upped4) . ";' title='" . size($upped4) . "'>
			<div class='percent'>" . mksize($upped4) . "</div>
			<div class='description'>$user4</div>
		  </li>
		  <li class='bar alert' style='height: " . size($upped5) . ";' title='" . size($upped5) . "'>
			<div class='percent'>" . mksize($upped5) . "</div>
			<div class='description'>$user5</div>
		  </li>
		  <li class='bar primary' style='height: " .size($upped6) . ";' title='" . size($upped6) . "'>
			<div class='percent'>" . mksize($upped6) . "</div>
			<div class='description'>$user6</div>
		  </li>
		  <li class='bar secondary' style='height: " .size($upped7) . ";' title='" . size($upped7) . "'>
			<div class='percent'>" . mksize($upped7) . "</div>
			<div class='description'>$user7</div>
		  </li>
		  <li class='bar success' style='height: " .size($upped8) . ";' title='" . size($upped8) . "'>
			<div class='percent'>" . mksize($upped8) . "</div>
			<div class='description'>$user8</div>
		  </li>
		  <li class='bar warning' style='height: " . size($upped9) . ";' title='" . size($upped9) . "'>
			<div class='percent'>" . mksize($upped9) . "</div>
			<div class='description'>$user9</div>
		  </li>
		  <li class='bar alert' style='height: " . size($upped10) . ";' title='" . size($upped10) . "'>
			<div class='percent'>" . mksize($upped10) . "</div>
			<div class='description'>$user10</div>
		  </li>
		</ul>"; 
} else {
    $HTMLOUT.= "<h4>Insufficient Uploaders (" . $counted . ")</h4></div>";
}
$HTMLOUT.= "<h2>Top 10 Downloaders</h2>";
$result = sql_query("SELECT username, downloaded FROM users WHERE enabled = 'yes' ORDER BY downloaded DESC LIMIT 10");
$counted = mysqli_num_rows($result);
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['downloaded'];
    $upped2 = $arr[1]['downloaded'];
    $upped3 = $arr[2]['downloaded'];
    $upped4 = $arr[3]['downloaded'];
    $upped5 = $arr[4]['downloaded'];
    $upped6 = $arr[5]['downloaded'];
    $upped7 = $arr[6]['downloaded'];
    $upped8 = $arr[7]['downloaded'];
    $upped9 = $arr[8]['downloaded'];
    $upped10 = $arr[9]['downloaded'];
	$HTMLOUT.= "<ul class='bar-graph'>
		<li class='bar-graph-axis'>
			<div class='bar-graph-label'>100%</div>
			<div class='bar-graph-label'>80%</div>
			<div class='bar-graph-label'>60%</div>
			<div class='bar-graph-label'>40%</div>
			<div class='bar-graph-label'>20%</div>
			<div class='bar-graph-label'>0%</div>
		</li>
		<li class='bar primary' style='height: " .size($upped1) . ";' title='" . size($upped1) . "'>
			<div class='percent'>" . mksize($upped1) . "</div>
			<div class='description'>$user1</div>
		</li>
		<li class='bar secondary' style='height: " .size($upped2) . ";' title='" . size($upped2) . "'>
			<div class='percent'>" . mksize($upped2) . "</div>
			<div class='description'>$user2</div>
		</li>
		<li class='bar success' style='height: " .size($upped3) . ";' title='" . size($upped3) . "'>
			<div class='percent'>" . mksize($upped3) . "</div>
			<div class='description'>$user3</div>
		</li>
		<li class='bar warning' style='height: " . size($upped4) . ";' title='" . size($upped4) . "'>
			<div class='percent'>" . mksize($upped4) . "</div>
			<div class='description'>$user4</div>
		</li>
		<li class='bar alert' style='height: " . size($upped5) . ";' title='" . size($upped5) . "'>
			<div class='percent'>" . mksize($upped5) . "</div>
			<div class='description'>$user5</div>
		</li>
		<li class='bar primary' style='height: " .size($upped6) . ";' title='" . size($upped6) . "'>
			<div class='percent'>" . mksize($upped6) . "</div>
			<div class='description'>$user6</div>
		</li>
		<li class='bar secondary' style='height: " .size($upped7) . ";' title='" . size($upped7) . "'>
			<div class='percent'>" . mksize($upped7) . "</div>
			<div class='description'>$user7</div>
		</li>
		<li class='bar success' style='height: " .size($upped8) . ";' title='" . size($upped8) . "'>
			<div class='percent'>" . mksize($upped8) . "</div>
			<div class='description'>$user8</div>
		</li>
		<li class='bar warning' style='height: " . size($upped9) . ";' title='" . size($upped9) . "'>
			<div class='percent'>" . mksize($upped9) . "</div>
			<div class='description'>$user9</div>
		</li>
		<li class='bar alert' style='height: " . size($upped10) . ";' title='" . size($upped10) . "'>
			<div class='percent'>" . mksize($upped10) . "</div>
			<div class='description'>$user10</div>
		</li>
	</ul>"; 
} else {
    $HTMLOUT.= "<h4>Insufficient Downloaders (" . $counted . ")</h4>";
}
$HTMLOUT.= "<h2>Top 10 Fastest Uploaders</h2>";
$result = sql_query("SELECT  username, uploaded / (" . TIME_NOW . " - added) AS upspeed FROM users WHERE enabled = 'yes' ORDER BY upspeed DESC LIMIT 10");
$counted = mysqli_num_rows($result);
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['upspeed'];
    $upped2 = $arr[1]['upspeed'];
    $upped3 = $arr[2]['upspeed'];
    $upped4 = $arr[3]['upspeed'];
    $upped5 = $arr[4]['upspeed'];
    $upped6 = $arr[5]['upspeed'];
    $upped7 = $arr[6]['upspeed'];
    $upped8 = $arr[7]['upspeed'];
    $upped9 = $arr[8]['upspeed'];
    $upped10 = $arr[9]['upspeed'];
	$HTMLOUT.= "<ul class='bar-graph'>
		<li class='bar-graph-axis'>
			<div class='bar-graph-label'>100%</div>
			<div class='bar-graph-label'>80%</div>
			<div class='bar-graph-label'>60%</div>
			<div class='bar-graph-label'>40%</div>
			<div class='bar-graph-label'>20%</div>
			<div class='bar-graph-label'>0%</div>
		</li>
		<li class='bar primary' style='height: " .size($upped1) . ";' title='" . size($upped1) . "'>
			<div class='percent'>" . mksize($upped1) . "</div>
			<div class='description'>$user1</div>
		</li>
		<li class='bar secondary' style='height: " .size($upped2) . ";' title='" . size($upped2) . "'>
			<div class='percent'>" . mksize($upped2) . "</div>
			<div class='description'>$user2</div>
		</li>
		<li class='bar success' style='height: " .size($upped3) . ";' title='" . size($upped3) . "'>
			<div class='percent'>" . mksize($upped3) . "</div>
			<div class='description'>$user3</div>
		</li>
		<li class='bar warning' style='height: " . size($upped4) . ";' title='" . size($upped4) . "'>
			<div class='percent'>" . mksize($upped4) . "</div>
			<div class='description'>$user4</div>
		</li>
		<li class='bar alert' style='height: " . size($upped5) . ";' title='" . size($upped5) . "'>
			<div class='percent'>" . mksize($upped5) . "</div>
			<div class='description'>$user5</div>
		</li>
		<li class='bar primary' style='height: " .size($upped6) . ";' title='" . size($upped6) . "'>
			<div class='percent'>" . mksize($upped6) . "</div>
			<div class='description'>$user6</div>
		</li>
		<li class='bar secondary' style='height: " .size($upped7) . ";' title='" . size($upped7) . "'>
			<div class='percent'>" . mksize($upped7) . "</div>
			<div class='description'>$user7</div>
		</li>
		<li class='bar success' style='height: " .size($upped8) . ";' title='" . size($upped8) . "'>
			<div class='percent'>" . mksize($upped8) . "</div>
			<div class='description'>$user8</div>
		</li>
		<li class='bar warning' style='height: " . size($upped9) . ";' title='" . size($upped9) . "'>
			<div class='percent'>" . mksize($upped9) . "</div>
			<div class='description'>$user9</div>
		</li>
		<li class='bar alert' style='height: " . size($upped10) . ";' title='" . size($upped10) . "'>
			<div class='percent'>" . mksize($upped10) . "</div>
			<div class='description'>$user10</div>
		</li>
	</ul>";
} else {
    $HTMLOUT.= "<h4>Insufficient Uploaders (" . $counted . ")</h4>";
}
$HTMLOUT.= "<h2>Top 10 Fastest Downloaders</h2>";
$result = sql_query("SELECT username, downloaded / (" . TIME_NOW . " - added) AS downspeed FROM users WHERE enabled = 'yes' ORDER BY downspeed DESC LIMIT 10");
$counted = mysqli_num_rows($result);
if ($counted == "10") {
    $arr = mysql_fetch_rowsarr($result);
    $user1 = $arr[0]['username'];
    $user2 = $arr[1]['username'];
    $user3 = $arr[2]['username'];
    $user4 = $arr[3]['username'];
    $user5 = $arr[4]['username'];
    $user6 = $arr[5]['username'];
    $user7 = $arr[6]['username'];
    $user8 = $arr[7]['username'];
    $user9 = $arr[8]['username'];
    $user10 = $arr[9]['username'];
    $upped1 = $arr[0]['downspeed'];
    $upped2 = $arr[1]['downspeed'];
    $upped3 = $arr[2]['downspeed'];
    $upped4 = $arr[3]['downspeed'];
    $upped5 = $arr[4]['downspeed'];
    $upped6 = $arr[5]['downspeed'];
    $upped7 = $arr[6]['downspeed'];
    $upped8 = $arr[7]['downspeed'];
    $upped9 = $arr[8]['downspeed'];
    $upped10 = $arr[9]['downspeed'];
	$HTMLOUT.= "<ul class='bar-graph'>
		<li class='bar-graph-axis'>
			<div class='bar-graph-label'>100%</div>
			<div class='bar-graph-label'>80%</div>
			<div class='bar-graph-label'>60%</div>
			<div class='bar-graph-label'>40%</div>
			<div class='bar-graph-label'>20%</div>
			<div class='bar-graph-label'>0%</div>
		</li>
		<li class='bar primary' style='height: " .size($upped1) . ";' title='" . size($upped1) . "'>
			<div class='percent'>" . mksize($upped1) . "</div>
			<div class='description'>$user1</div>
		</li>
		<li class='bar secondary' style='height: " .size($upped2) . ";' title='" . size($upped2) . "'>
			<div class='percent'>" . mksize($upped2) . "</div>
			<div class='description'>$user2</div>
		</li>
		<li class='bar success' style='height: " .size($upped3) . ";' title='" . size($upped3) . "'>
			<div class='percent'>" . mksize($upped3) . "</div>
			<div class='description'>$user3</div>
		</li>
		<li class='bar warning' style='height: " . size($upped4) . ";' title='" . size($upped4) . "'>
			<div class='percent'>" . mksize($upped4) . "</div>
			<div class='description'>$user4</div>
		</li>
		<li class='bar alert' style='height: " . size($upped5) . ";' title='" . size($upped5) . "'>
			<div class='percent'>" . mksize($upped5) . "</div>
			<div class='description'>$user5</div>
		</li>
		<li class='bar primary' style='height: " .size($upped6) . ";' title='" . size($upped6) . "'>
			<div class='percent'>" . mksize($upped6) . "</div>
			<div class='description'>$user6</div>
		</li>
		<li class='bar secondary' style='height: " .size($upped7) . ";' title='" . size($upped7) . "'>
			<div class='percent'>" . mksize($upped7) . "</div>
			<div class='description'>$user7</div>
		</li>
		<li class='bar success' style='height: " .size($upped8) . ";' title='" . size($upped8) . "'>
			<div class='percent'>" . mksize($upped8) . "</div>
			<div class='description'>$user8</div>
		</li>
		<li class='bar warning' style='height: " . size($upped9) . ";' title='" . size($upped9) . "'>
			<div class='percent'>" . mksize($upped9) . "</div>
			<div class='description'>$user9</div>
		</li>
		<li class='bar alert' style='height: " . size($upped10) . ";' title='" . size($upped10) . "'>
			<div class='percent'>" . mksize($upped10) . "</div>
			<div class='description'>$user10</div>
		</li>
	</ul>";
} else {
    $HTMLOUT.= "<h4>Insufficient Downloaders (" . $counted . ")</h4>";
}
$HTMLOUT.= "</div>";
echo stdhead($lang['head_title']) . $HTMLOUT . stdfoot();
?>
