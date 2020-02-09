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
$count = 0;
	while ((list($code, $url) = each($smilies))) {
		if ($count % 20 == 0) $HTMLOUT.= "<p>";
		$HTMLOUT.= "     <a href=\"javascript: SmileIT('" . str_replace("'", "\'", $code) . "','shbox','shbox_text')\" aria-label='Dismiss alert' data-close><img src='./pic/smilies/" . $url . "' alt='' /></a>     ";
		$count++;
		if ($count % 20 == 0) $HTMLOUT.= "</p>";
	}
?>
