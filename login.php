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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
dbconn();
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
ini_set('session.use_trans_sid', '0');
$stdfoot = '';
if ($INSTALLER09['captcha_on'] === true)
$stdfoot = array(
    /** include js **/
    'js' => array(
           'captcha', 'jquery.simpleCaptcha-0.2'
    )
);

$lang = array_merge(load_language('global') , load_language('login'));
$newpage = new page_verify();
$newpage->create('takelogin');
$left = $total = '';
//== 09 failed logins
function left()
{
    global $INSTALLER09;
    $total = 0;
    $ip = getip();
    $fail = sql_query("SELECT SUM(attempts) FROM failedlogins WHERE ip=" . sqlesc($ip)) or sqlerr(__FILE__, __LINE__);
    list($total) = mysqli_fetch_row($fail);
    $left = $INSTALLER09['failedlogins'] - $total;
    if ($left <= 2) $left = "<span class='label alert disabled'>{$left}</span>";
    else $left = "<span class='label success disabled'>{$left}</span>";
    return $left;
}
//== End Failed logins
$HTMLOUT ="";
$got_ssl = isset($_SERVER['HTTPS']) && (bool)$_SERVER['HTTPS'] == true ? true : false;
//== click X by Retro
$value = array(
    '...',
    '...',
    '...',
    '...',
    '...',
    '...'
);
$value[rand(1, count($value) - 1) ] = 'X';
$HTMLOUT.= "".($INSTALLER09['captcha_on'] ? "<script>
	  /*<![CDATA[*/
	  $(document).ready(function () {
	  $('#captchalogin').simpleCaptcha();
    });
    /*]]>*/
    </script>" : "")."
	<div class='row-login'><div class='large-3 columns'>&nbsp;&nbsp;</div>";
		if (!empty($_GET["returnto"])) {
    $returnto = htmlsafechars($_GET["returnto"]);
        $HTMLOUT.= "<div class='large-6 columns'>
			<div class='card'>
				<div class='card-divider'><h1 class='text-center'>{$lang['login_not_logged_in']}</h1></div>";
        $HTMLOUT.= "<div class='alert-box warning'>{$lang['login_error']}</div>";
}
$HTMLOUT.="<div class='card-section'>{$lang['login_cookies']}<br />{$lang['login_cookies1']}<br />
			<span class='label warning disabled'>{$INSTALLER09['failedlogins']}</span>&nbsp;{$lang['login_failed']}<br />{$lang['login_failed_1']}&nbsp;
			".left()." {$lang['login_failed_2']} ".(left()>1?"":"s")."&nbsp;{$lang['login_remain']}</div>";
	unset($returnto);
	
	$HTMLOUT.= "<form role='form' method='post' title='login' action='takelogin.php'>
      <div class='input-group'>
        <span class='input-group-label'><i class='fa fa-user' aria-hidden='true'></i></span>
        <input type='text' class='input-group-field' name='username' placeholder='Username'>
      </div>
      <div class='input-group'>
        <span class='input-group-label'><i class='fa fa-lock' aria-hidden='true'></i></span>
		<input type='password' class='input-group-field' name='password' placeholder='Password'></label>
      </div>
			".($INSTALLER09['captcha_on'] ? "<div class='input-group float-center'><div id='captchalogin'></div></div>" : "") . "<p class='text-center'>{$lang['login_click']}<strong>{$lang['login_x']}</strong></p>
				<div class='row'><div class='columns large-6 large-centered medium-6 medium-centered'><div class='button-group'>";
for ($i = 0; $i < count($value); $i++) {
    $HTMLOUT.= "
	
	<input name=\"submitme\" type=\"submit\" value=\"{$value[$i]}\" class=\"button\">";
}
$HTMLOUT.= "</div></div></div>";
			    $HTMLOUT.= "<fieldset class='fieldset'>
					<legend>{$lang['login_use_ssl']}</legend>
					<ul class='menu vertical'>
						<li><input type='checkbox' name='use_ssl' " . ($got_ssl ? "checked='checked'" : "disabled='disabled' title='SSL connection not available'") . " value='1' id='ssl'><label for='ssl'>{$lang['login_ssl1']}</label></li>
						<li><input type='checkbox' name='perm_ssl' " . ($got_ssl ? "" : "disabled='disabled' title='SSL connection not available'") . " value='1' id='ssl2'><label for='ssl2'>{$lang['login_ssl2']}</label></li>
					</ul>
				</fieldset>
<div class='row'><div class='columns large-6 large-centered'>
<div class='small button-group'>
<a class='button' href='signup.php'>{$lang['login_signup']}</a>
<a class='button' href='resetpw.php'>{$lang['login_forgot']}</a>
<a class='button' href='recover.php'>{$lang['login_forgot_1']}</a>
</div></div></div>
";
if (isset($returnto)) $HTMLOUT.= "<input type='hidden' name='returnto' value='" . htmlsafechars($returnto) . "'>\n";
$HTMLOUT.= "";
$HTMLOUT.="</form></div></div><div class='large-3 columns'></div>";
echo stdhead("{$lang['login_login_btn']}", true) . $HTMLOUT . stdfoot($stdfoot);
?>
