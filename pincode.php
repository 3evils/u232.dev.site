<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                			    |
 |--------------------------------------------------------------------------|
 |   Licence Info: GPL			                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2010 U-232 V5					    |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: Mindless, Autotron, whocares, Swizzles.		    |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
 */
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'password_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
require_once (CLASS_DIR . 'class_browser.php');
dbconn();
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
$lang = array_merge(load_language('global') , load_language('takelogin'));
function bark($text = 'Username or password incorrect')
{
    global $lang, $INSTALLER09, $mc1;
    $sha = sha1($_SERVER['REMOTE_ADDR']);
    $dict_key = 'dictbreaker:::' . $sha;
    $flood = $mc1->get_value($dict_key);
    if ($flood === false) $mc1->cache_value($dict_key, 'flood_check', 20);
    else die('Minimum 8 seconds between login attempts :)');
    stderr($lang['tlogin_failed'], $text);
}

if (isset($_POST['pin_code'])) {
	$username = $_POST['username'];
	$added = TIME_NOW;
	$res = sql_query("SELECT id, ip, passhash, perms, ssluse, secret, enabled, pin_code FROM users WHERE username = " . sqlesc($username) . " AND status = 'confirmed' AND passhash = " . sqlesc($_POST['passhash']));
	$row = mysqli_fetch_assoc($res);
	if((int)$_POST['pin_code'] != (int)$row['pin_code']) {
		$ip_escaped = sqlesc(getip());
		$fail = (@mysqli_fetch_row(sql_query("SELECT COUNT(id) from failedlogins where ip=$ip_escaped"))) or sqlerr(__FILE__, __LINE__);
	    if ($fail[0] == 0) sql_query("INSERT INTO failedlogins (ip, added, attempts) VALUES ($ip_escaped, $added, 1)") or sqlerr(__FILE__, __LINE__);
	    else sql_query("UPDATE failedlogins SET attempts = attempts + 1 where ip=$ip_escaped") or sqlerr(__FILE__, __LINE__);
	    $to = ((int)$row["id"]);
	    $ip = getip();
	    $subject = "Failed login";
	    $msg = "[color=red]Security alert[/color]\n Account: ID=" . (int)$row['id'] . " Somebody (probably you, " . htmlsafechars($username) . " !) tried to login but failed!" . "\nTheir [b]Ip Address [/b] was : " . htmlsafechars($ip) . "\n If this wasn't you please report this event to a {$INSTALLER09['site_name']} staff member\n - Thank you.\n";
	    $sql = "INSERT INTO messages (sender, receiver, msg, subject, added) VALUES('System', " . sqlesc($to) . ", " . sqlesc($msg) . ", " . sqlesc($subject) . ", $added);";
	    $res = sql_query($sql) or sqlerr(__FILE__, __LINE__);
	    $mc1->delete_value('inbox_new_' . $row['id']);
	    $mc1->delete_value('inbox_new_sb_' . $row['id']);
	    bark("<b>Error</b>: Pin Code entry incorrect!");
	} else {
		$passh = md5($row["passhash"] . $_SERVER["REMOTE_ADDR"]);
    	logincookie($row["id"], $passh);
    	header("Location: {$INSTALLER09['baseurl']}/index.php");
	}
} else {
	echo "
	<!DOCTYPE HTML>

<html>
    <head>
        <title>Pincode Input</title>

    <link href=\"css/bootstrap.min.css\" rel=\"stylesheet\">
    <link href=\"css/bootstrap-pincode-input.css\" rel=\"stylesheet\">
    <style>
        body{

        }
    </style>
    <script src=\"scripts/jquery-2.1.4.min.js\" type=\"text/javascript\"></script>
    <script type=\"text/javascript\" src=\"scripts/bootstrap-pincode-input.js\"></script>
    <script>
        $(document).ready(function() {
            $('#pin_code').pincodeInput({hidedigits:true,inputs:4});
            
            
        });
    </script>
    </head>
    <body>

    <div class=\"container\">
        <h1>Enter your pin code</h1>
        
        <div class=\"container-fluid\">
            <div class=\"row\">
                <div class=\"col-md-12\">
                <form method='post' title='login' action='pincode.php'>
                    <input type=\"text\" id=\"pin_code\" name='pin_code' >
                    <input type='hidden' name='username' value='".$_GET['username']."' />
                    <input type='hidden' name='passhash' value='".$_GET['passhash']."' />
                    <a href=\"#\" onclick=\"javascript:$('#pin_code').pincodeInput().data('plugin_pincodeInput').clear()\">clear</a><br/>
                    <button class=\"btn-primary\">Submit</button></form>
                </div>
            </div>
        </div>




        </div>

    </body>
</html>

";
}
