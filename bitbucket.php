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
// by system
// pic management by pdq
// no rights reserved - public domain FTW!
require_once (__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once (INCL_DIR.'user_functions.php');
require_once (INCL_DIR.'bbcode_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('bitbucket'));
$HTMLOUT = "";
/* Image folder located outside of webroot */
// BITBUCKET_DIR Now defined in config.php
//define('BITBUCKET_DIR', DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'bucket');
/* Avatar folder located inside BITBUCKET_DIR */
define('AVATAR_DIR', BITBUCKET_DIR.DIRECTORY_SEPARATOR.'avatar');
if (!is_dir(AVATAR_DIR)) {
    mkdir(AVATAR_DIR);
}
$SaLt = 'mE0wI924dsfsfs!@B'; // change this!
$SaLty = '8368364562'; // NEW!
$skey = 'eTe5$Ybnsccgbsfdsfsw4h6W'; // change this!
$maxsize = $INSTALLER09['bucket_maxsize'];
/* seperate images into */
$folders = date('Y/m');
// valid file formats
$formats = array(
    '.gif',
    '.jpg',
    '.jpeg',
    '.png',
);
// path to bucket/avatar directories
$bucketdir = (isset($_POST["avy"]) ? AVATAR_DIR.'/' : BITBUCKET_DIR.'/'.$folders.'/');
$bucketlink = ((isset($_POST["avy"]) || (isset($_GET['images']) && $_GET['images'] == 2)) ? 'avatar/' : $folders.'/');
$address = $INSTALLER09['baseurl'].'/';
$PICSALT = md5($SaLt . $CURUSER['username']);
//rename files and obscufate uploader
$USERSALT = substr(md5($SaLty.$CURUSER['id']) , 0, 6);
/* this is a hack, you should create folders named 2012, 2013, 2014, etc,
* inside these folders you should have folders for the months named 01 to 12
* then comment out the following 2 lines
*/
make_year(BITBUCKET_DIR);
make_month(BITBUCKET_DIR);
if ($CURUSER['design'] == $CURUSER['design']) {
	require_once DESIGN_DIR . "{$CURUSER['design']}/bitbucket.php";
}

echo stdhead($lang['bitbucket_bitbucket']).$HTMLOUT.stdfoot();
function bucketrand()
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $out = '';
    for ($i = 0; $i < 6; $i++) $out.= $chars[mt_rand(0, 61) ];
    return $out;
}
function encrypt($text)
{
    global $PICSALT;
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $PICSALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB) , MCRYPT_RAND))));
}
function decrypt($text)
{
    global $PICSALT;
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $PICSALT, base64_decode($text) , MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB) , MCRYPT_RAND)));
}
/* Sanity checking by pdq */
function valid_path($root, $input)
{
    $fullpath = $root.$input;
    $fullpath = realpath($fullpath);
    $root = realpath($root);
    $rl = strlen($root);
    return ($root != substr($fullpath, 0, $rl)) ? NULL : $fullpath;
}
function make_year($path)
{
    $dir = $path.'/'.date('Y');
    if (!is_dir($dir)) mkdir($dir);
}
function make_month($path)
{
    $dir = $path.'/'.date('Y/m');
    if (!is_dir($dir)) mkdir($dir);
}
// EndFile

?>
