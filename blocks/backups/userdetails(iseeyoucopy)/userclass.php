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
$HTMLOUT.= "<div class='card large-4 columns'>
  <h6>{$lang['userdetails_class']}</h6>
  <div class='card-section'>
    <div class='thumbnail'>
      " . get_user_class_name($user["class"]) . "&nbsp;&nbsp;
		<img src='" . get_user_class_image($user["class"]) . "' alt='" . get_user_class_name($user["class"]) . "' title='" . get_user_class_name($user["class"]) . "'>
    </div>
  </div>
</div>";
//==End
// End Class
// End File
