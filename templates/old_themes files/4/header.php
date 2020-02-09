<?php
     /*-----------------------------------------------------------------------\
	|   https://github.com/Bigjoos/ -------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Licence Info: WTFPL  --------------------------------------------------|
	|--------------------------------------------------------------------------|
	|   Copyright (C) 2010 U-232 V5	-------------------------------------------|
	|--------------------------------------------------------------------------|
	|   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon. --|
	|--------------------------------------------------------------------------|
	|   Project Leaders: Mindless, Autotron, whocares, Swizzles.---------------|
	\------------------------------------------------------------------------*/
 //==Template system by Terranova
 //==Template system modified by son
function stdhead($title = "", $msgalert = true, $stdhead = false)
{
    global $CURUSER, $INSTALLER09, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $mc1, $BLOCKS, $CURBLOCK, $mood, $blocks;
    if (!$INSTALLER09['site_online']) die("Site is down for maintenance, please check back again later... thanks<br />");
    if ($title == "") $title = $INSTALLER09['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '');
    else $title = $INSTALLER09['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '') . " :: " . htmlsafechars($title);
    if ($CURUSER) {
        $INSTALLER09['stylesheet'] = isset($CURUSER['stylesheet']) ? "{$CURUSER['stylesheet']}.css" : $INSTALLER09['stylesheet'];
        $INSTALLER09['categorie_icon'] = isset($CURUSER['categorie_icon']) ? "{$CURUSER['categorie_icon']}" : $INSTALLER09['categorie_icon'];
        $INSTALLER09['language'] = isset($CURUSER['language']) ? "{$CURUSER['language']}" : $INSTALLER09['language'];
    }
    $salty = md5("Th15T3xtis5add3dto66uddy6he@water..." . $CURUSER['username'] . "");
    /** ZZZZZZZZZZZZZZZZZZZZZZZZZZip it! */
//if (!isset($_NO_COMPRESS)) if (!ob_start('ob_gzhandler')) ob_start();
    $htmlout = '';
    //== Include js files needed only for the page being used by pdq
    $js_incl = '';
    $js_incl.= '<!-- javascript goes here or in footer -->';
    if (!empty($stdhead['js'])) {
        foreach ($stdhead['js'] as $JS) $js_incl.= "<script type='text/javascript' src='{$INSTALLER09['baseurl']}/scripts/" . $JS . ".js'></script>";
    }
    //== Include css files needed only for the page being used by pdq
    $stylez = ($CURUSER ? "{$CURUSER['stylesheet']}" : "{$INSTALLER09['stylesheet']}");
    $css_incl = '';
    $css_incl.= '<!-- css goes in header -->';
    if (!empty($stdhead['css'])) {
        foreach ($stdhead['css'] as $CSS) $css_incl.= "<link type='text/css' rel='stylesheet' href='{$INSTALLER09['baseurl']}/templates/{$stylez}/css/" . $CSS . ".css' />";
    }
$htmlout .='
<!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <!-- ####################################################### -->
        <!-- #   This website is powered by U-232 V5	           # -->
        <!-- #   Download and support at: https://u-232.com        # -->
        <!-- #   Template Modded by U-232 Dev Team                 # -->
        <!-- ####################################################### -->
  <head>
    <!--<meta charset="'.charset().'" />-->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
    <title>'.$title.'</title>
		<!-- favicon  -->
    	<link rel="shortcut icon" href="/favicon.ico" />
	<!-- Global CSS-->
  	<link rel="stylesheet" href="css/global_media.css" type="text/css">
	<link rel="stylesheet" href="css/ie.css" type="text/css">
        <!--<link rel="stylesheet" href="css/bbcode.css" />-->
 	<!-- Template CSS-->
     	<link rel="stylesheet" href="templates/4/css/font-awesome.css" />
     	<link rel="stylesheet" href="templates/4/css/mega-menu.css" />
	<!--<link rel="stylesheet" href="templates/4/css/icarosel.css" type="text/css">-->
	<link rel="stylesheet" href="templates/4/css/style.css" type="text/css">
	<link rel="stylesheet" href="templates/4/css/style2.css" type="text/css">
	<link rel="stylesheet" href="templates/4/css/bootstrap.css" type="text/css">
	<link rel="stylesheet" href="templates/4/css/custom.min.css" type="text/css">
    	<link rel="stylesheet" href="templates/4/4.css" />
	<!--Global Javascript-->
    	<script src="scripts/jquery-1.11.1.js"></script>
      	<script src="scripts/bootstrap.js"></script>	
        <script type="text/javascript" src="scripts/jquery-1.5.js"></script>
        <script type="text/javascript" src="scripts/jquery.status.js"></script>
        <script type="text/javascript" src="scripts/jquery.cookie.js"></script>
        <script type="text/javascript" src="scripts/help.js"></script>
	<!--Template Javascript-->
    	<script src="templates/4/js/mega-menu-hover.js"></script>
    	<script src="templates/4/js/bsa-hover.js"></script>
    	<script src="templates/4/js/custom.js"></script>
	<!-- Forum CSS-->
	<!--<link rel="stylesheet" href="templates/1/css/forum.css" />-->
	<!-- Global javascript-->
		<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
		<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
		<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
		<!--[if gt IE 8]> <![endif]-->
		<!--[if lt IE 9]><meta http-equiv="X-UA-Compatible" content="IE=9"><![endif]-->
		<!-- <script src="scripts/html5shiv.js"  async></script>  -->
		<script src="scripts/respond.min.js" async></script> <!-- used for IE8 and below-->
		<!-- <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>  -->    
		<script type="application/rss+xml" title="Latest Torrents" src="/rss.php?torrent_pass='.$CURUSER["torrent_pass"].'"></script>';
	$htmlout .= "
    <style type='text/css'>#mlike{cursor:pointer;}</style>
    <script type='text/javascript'>
        /*<![CDATA[*/
		// Like Dislike function
		//================================================== -->
		$(function() {							// the like js
		$('span[id*=mlike]').like232({
		times : 5,            	// times checked 
		disabled : 5,         	// disabled from liking for how many seconds
		time  : 5,             	// period within check is performed
		url : '/ajax.like.php'
		});
		});
	<!--template changer function  -->
       function themes() {
          window.open('take_theme.php','My themes','height=150,width=200,resizable=no,scrollbars=no,toolbar=no,menubar=no');
        }
	<!--language changer function  -->
        function language_select() {
          window.open('take_lang.php','My language','height=150,width=200,resizable=no,scrollbars=no,toolbar=no,menubar=no');
        }
	<!--radio function -->
        function radio() {
          window.open('radio_popup.php','My Radio','height=700,width=800,resizable=no,scrollbars=no,toolbar=no,menubar=no');
        }
         /*]]>*/
        </script>";
 $htmlout .="{$js_incl}{$css_incl}";
 $htmlout .='</head>';
 $htmlout .='<body>';
 if ($CURUSER) {
 $htmlout .="".($INSTALLER09['mods']['snow'] && $CURUSER['snow'] == 'yes' ? "<div id='snow'>" : "")."<div class='container'>";
 $htmlout .="<div class='banners'></div>";
 require_once (TEMPLATE_DIR.'' . DIRECTORY_SEPARATOR . ''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'mega_menu.php');
 $htmlout .='<div class="text-center alert collapse well" id="collapseStatusBar">'.StatusBar().'</div>';
 require_once (TEMPLATE_DIR.'' . DIRECTORY_SEPARATOR . ''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'global_messages.php');
 require_once (TEMPLATE_DIR.'' . DIRECTORY_SEPARATOR . ''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'staff_tools.php');
 require_once (TEMPLATE_DIR.'' . DIRECTORY_SEPARATOR . ''.$CURUSER['stylesheet'].'' . DIRECTORY_SEPARATOR . 'message_modal.php');
}
 return $htmlout;
 }
?>
