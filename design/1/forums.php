<?php
/**
 |--------------------------------------------------------------------------|
 |   https://github.com/Bigjoos/                                            |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2018 U-232 CodeName Trinity                              |
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
          $HTMLOUT .= "<div class='navigation'><a href='".$INSTALLER09['baseurl']."/index.php'>" . $INSTALLER09["site_name"] . "</a>
          <br /><span class='active'></span></div><br />";
	  $ovf_res = sql_query("SELECT id, name, min_class_view FROM over_forums ORDER BY sort ASC") or sqlerr(__FILE__, __LINE__);
	  while ($ovf_arr = mysqli_fetch_assoc($ovf_res)) {
	  if ($CURUSER['class'] < $ovf_arr["min_class_view"])
	  continue;
          $ovfid = (int)$ovf_arr["id"];
          $ovfname = htmlsafechars($ovf_arr["name"]);
          $HTMLOUT .= "<div class='callout'><div class='card-divider'>
          <span class='nav navbar-nav navbar-right'><label for='checkbox_4' class='text-left'>
          </label></span>";
          $HTMLOUT .="&nbsp;&nbsp;<strong><a href='{$INSTALLER09['baseurl']}/forums.php?action=forumview&amp;forid=".$ovfid."'>".$ovfname."</a></strong></div>
          <div class='table striped hover'>";
          $HTMLOUT .= show_forums($ovfid, false, $forums, true, true);
          }
$HTMLOUT .= "";
          if ($Multi_forum['configs']['use_forum_stats_mod'])
          $HTMLOUT .= forum_stats();
$HTMLOUT .= "<div class='col-sm-12 col-sm-offset-0'>
          <p align='center'>
	  <a href='{$INSTALLER09['baseurl']}/forums.php?action=search'><b class='btn btn-default btn-sm'>&nbsp;&nbsp;{$lang['forum_pg_srch']}&nbsp;&nbsp;</b></a>&nbsp;&nbsp; 
	  <a href='{$INSTALLER09['baseurl']}/forums.php?action=viewunread'><b class='btn btn-default btn-sm'>&nbsp;&nbsp;{$lang['forum_pg_new']}&nbsp;&nbsp;</b></a>&nbsp;&nbsp; 
	  <a href='{$INSTALLER09['baseurl']}/forums.php?action=getdaily'><b class='btn btn-default btn-sm'>&nbsp;&nbsp;{$lang['forum_pg_24h']}&nbsp;&nbsp;</b></a>&nbsp;&nbsp; 
	  <a href='{$INSTALLER09['baseurl']}/forums.php?catchup'><b class='btn btn-default btn-sm'>&nbsp;&nbsp;{$lang['forum_pg_mark']}&nbsp;&nbsp;</b></a></p>
          </div><br /><br />";
?>