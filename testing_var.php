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
// Achievements mod by MelvinMeow
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'));
function load_designs($file = '')
{
    global $INSTALLER09, $CURUSER;
    if (!isset($GLOBALS['CURUSER']) OR empty($GLOBALS['CURUSER']['design'])) {
        if (!file_exists(DESIGN_DIR . "bootstrap/html_content.php")) {
            stderr('System Error', 'Can\'t find html arrays files');
        }
        require_once (DESIGN_DIR . "bootstrap/html_content.php");
		return $design;
    }
    if (!file_exists(DESIGN_DIR . "bootstrap/html_content.php")) {
        stderr('System Error', 'Can\'t find html arrays files');
    } else {
        require_once DESIGN_DIR . "bootstrap/html_content.php";
    }
	return $design;
}
$design = extract(load_design());
 
$HTMLOUT = '';
$HTMLOUT.= "

<div class='{$row}'>
<div class='{$callout}'>
<h1>Test Tsjbvxjsbl</h1>
<button class='{$button} {$alert}'>Testing button</button>
<button class='{$button} {$warning}'>Testing button</button>
<button class='{$button} {$secondary}'>Testing button</button>
<button class='{$button} {$success}'>Testing button</button>
<button class='{$button}'>Testing button</button>
</div>
</div>

";
echo stdhead("testing vars") . $HTMLOUT . stdfoot();
die;
?>