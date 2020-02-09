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
/*
+------------------------------------------------
|   $Date$ 210413
|   $Revision$ 4.0
|   $Author$ Bigjoos, Roguesurfer
|   $URL$
|   $usercp
|   
+------------------------------------------------
*/
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (CLASS_DIR . 'class_user_options.php');
require_once (CLASS_DIR . 'class_user_options_2.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'html_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
require_once (CACHE_DIR . 'timezones.php');
dbconn(false);
loggedinorreturn();
$stdfoot = array(
    /** include js **/
    'js' => array(
        'keyboard',
    )
);
$stdhead = array(
    /** include js **/
    'js' => array(
        'custom-form-elements'
    ),
    /** include css **/
    'css' => array(
         'usercp'
    )
);
//echo user_options::CLEAR_NEW_TAG_MANUALLY;
//die();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'html_functions' . DIRECTORY_SEPARATOR . 'global_html_functions.php'); 
$design = array_merge(load_design());
$lang = array_merge(load_language('global') , load_language('usercp'));
$newpage = new page_verify();
$newpage->create('tkepe');
$HTMLOUT = $stylesheets = $wherecatina = '';
$templates = sql_query("SELECT id, name, design_id FROM stylesheets ORDER BY id");
while ($templ = mysqli_fetch_assoc($templates)) {
    if (file_exists("templates/".intval($templ['id'])."/template.php")) $stylesheets.= "<option value='" . (int)$templ['id'] . "'" . ($templ['id'] == $CURUSER['stylesheet'] ? " selected='selected'" : "") . ">" . htmlsafechars($templ['name']) . "</option>";
}
$countries = "<option value='0'>---- {$lang['usercp_none']} ----</option>\n";
$ct_r = sql_query("SELECT id,name FROM countries ORDER BY name") or sqlerr(__FILE__, __LINE__);
while ($ct_a = mysqli_fetch_assoc($ct_r)) {
    $countries.= "<option value='" . (int)$ct_a['id'] . "'" . ($CURUSER["country"] == $ct_a['id'] ? " selected='selected'" : "") . ">" . htmlsafechars($ct_a['name']) . "</option>\n";
}
$offset = ($CURUSER['time_offset'] != "") ? (string)$CURUSER['time_offset'] : (string)$INSTALLER09['time_offset'];
$time_select = "<select name='user_timezone'>";
foreach ($TZ as $off => $words) {
    if (preg_match("/^time_(-?[\d\.]+)$/", $off, $match)) {
        $time_select.= $match[1] == $offset ? "<option value='{$match[1]}' selected='selected'>$words</option>\n" : "<option value='{$match[1]}'>$words</option>\n";
    }
}
$time_select.= "</select>";
if ($CURUSER['dst_in_use']) {
    $dst_check = 'checked="checked"';
} else {
    $dst_check = '';
}
if ($CURUSER['auto_correct_dst']) {
    $dst_correction = 'checked="checked"';
} else {
    $dst_correction = '';
}
$HTMLOUT.= "<script type='text/javascript'>
    /*<![CDATA[*/
    function daylight_show()
    {
    if ( document.getElementById( 'tz-checkdst' ).checked )
    {
    document.getElementById( 'tz-checkmanual' ).style.display = 'none';
    }
    else
    {
    document.getElementById( 'tz-checkmanual' ).style.display = 'block';
    }
    }
    /*]]>*/
    </script>";
$HTMLOUT.= '
    <script type="text/javascript">
    /*<![CDATA[*/
    $(document).ready(function()	{
    //=== show hide paranoia info
    $("#paranoia_open").click(function() {
    $("#paranoia_info").slideToggle("slow", function() {
    });
    });
    });
    /*]]>*/
    </script>';
$possible_actions = array(
    'avatar',
    'signature',
    'social',
    'location',
    'security',
    'links',
    'torrents',
    'personal',
    'default'
);
$action = isset($_GET["action"]) ? htmlsafechars(trim($_GET["action"])) : '';
if (!in_array($action, $possible_actions)) stderr('<br /><div class="alert alert-error span11">'.$lang['usercp_err1'].'</div>');
if (isset($_GET["edited"])) {
    $HTMLOUT.= "<br /><div class='alert alert-success span11'>{$lang['usercp_updated']}!</div><br />";
    if (isset($_GET["mailsent"])) $HTMLOUT.= "<h2>{$lang['usercp_mail_sent']}!</h2>\n";
} elseif (isset($_GET["emailch"])) {
    $HTMLOUT.= "<h1>{$lang['usercp_emailch']}!</h1>\n";
}
$HTMLOUT.= "<h4 class='subheader'>{$lang['usercp_welcome']}<a href='userdetails.php?id=" . (int)$CURUSER['id'] . "'>" . htmlsafechars($CURUSER['username']) . "</a> !</h4>
		<ul class='tabs' data-responsive-accordion-tabs='tabs medium-accordion large-tabs' id='usercp-tabs'>
			<li class='tabs-title is-active'><a href='usercp.php?action=avatar'>{$lang['usercp_menu_av']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=signature'>{$lang['usercp_menu_sig']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=default'>{$lang['usercp_menu_pms']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=security'>{$lang['usercp_menu_sec']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=torrents'>{$lang['usercp_menu_tts']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=personal'>{$lang['usercp_menu_pers']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=social'>{$lang['usercp_menu_soc']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=location'>{$lang['usercp_menu_loc']}</a></li>
			<li class='tabs-title'><a href='usercp.php?action=links'>{$lang['usercp_menu_lnk']}</a></li>
		</ul>
	<div class='row callout'>
	<form method='post' action='takeeditcp.php'>";
	require_once (BLOCK_DIR . 'usercp/avatar_img_and_links.php');
//== Avatar
if ($action == "avatar") {
	require_once (BLOCK_DIR . 'usercp/avatar.php');
}
//== Signature
elseif ($action == "signature") {
	require_once (BLOCK_DIR . 'usercp/signature.php');
}
//== Social
elseif ($action == "social") {
	require_once (BLOCK_DIR . 'usercp/social.php');
}
//== Location
elseif ($action == "location") {
 	require_once (BLOCK_DIR . 'usercp/location.php');
}
//== links
elseif ($action == "links") {
	require_once (BLOCK_DIR . 'usercp/links.php');
}
//== Security
elseif ($action == "security") {
 	require_once (BLOCK_DIR . 'usercp/security.php');
}
//== Torrents
elseif ($action == "torrents") {
	require_once (BLOCK_DIR . 'usercp/torrents.php');
}
//== Personal
elseif ($action == "personal") {
	require_once (BLOCK_DIR . 'usercp/personal.php');
} 
//== Default
elseif ($action == "default") {
	require_once (BLOCK_DIR . 'usercp/default.php');
}
$HTMLOUT.= "</form></div>";
echo stdhead(htmlsafechars($CURUSER["username"], ENT_QUOTES) . "{$lang['usercp_stdhead']} ", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
