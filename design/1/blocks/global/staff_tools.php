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
//==Staff tools quick link - Stoner

 if ($CURUSER['class'] >= UC_STAFF)
 {
	 
	 $htmlout .= '<ul class="menu vertical">';
 if (($mysql_data = $mc1->get_value('is_staff_' . $CURUSER['class'])) === false) {
 $res = sql_query('SELECT * FROM staffpanel WHERE av_class <= ' . sqlesc($CURUSER['class']) . ' ORDER BY page_name ASC') or sqlerr(__FILE__, __LINE__);
  while ($arr = mysqli_fetch_assoc($res)) $mysql_data[] = $arr;
 $mc1->cache_value('is_staff_' . $CURUSER['class'], $mysql_data, $INSTALLER09['expires']['staff_check']);
  }
  if ($mysql_data) { 
   $htmlout .= '
   <a href="#" class="button small">User</a>';
     
  foreach ($mysql_data as $key => $value){
  if ($value['av_class'] <= $CURUSER['class'] && $value['type'] == 'user') {
  $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
  }
  }
  $htmlout .= '
   <a href="#" class="button small">Settings</a>';
           
  foreach ($mysql_data as $key => $value){
  if ($value['av_class'] <= $CURUSER['class'] && $value['type'] == 'settings') {
  $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
  }
  }
  $htmlout .= '
<a href="#" class="button small">Stats</a>';
           
  foreach ($mysql_data as $key => $value){
  if ((int)$value['av_class'] <= $CURUSER['class'] && htmlsafechars($value['type']) == 'stats') {
  $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
  }
  }
  $htmlout .= '
<a href="#" class="button small">Other</a>';
           
  foreach ($mysql_data as $key => $value){
  if ((int)$value['av_class'] <= $CURUSER['class'] && htmlsafechars($value['type']) == 'other') {
  $htmlout .= '<li><a href="'.htmlsafechars($value["file_name"]).'">'.htmlsafechars($value["page_name"]).'</a></li>';
  }
  }
  $htmlout .= '';
  }
  $htmlout .='</ul>';
  }
//== End
// End Class
// End File
