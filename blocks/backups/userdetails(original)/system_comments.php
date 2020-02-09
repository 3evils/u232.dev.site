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
//=== system comments
    $the_flip_box_7 = '<a name="system_comments"></a>
	<a class="tiny button float-right" href="#system_comments" data-toggle="systemcommModal">view</a>';
	$HTMLOUT.= "<div class='card secondary medium-6 large-4 columns'>";
    if (!empty($user_stats['modcomment'])) 
		$HTMLOUT.= "
			<h4 class='subheader'>{$lang['userdetails_system']}". ($user_stats['modcomment'] != '' ? $the_flip_box_7 .'</h4>
			<div class="reveal" id="systemcommModal" data-reveal data-close-on-click="true">
				<div class="card-section">' . format_comment($user_stats['modcomment']) . '</div><button class="close-button" data-close aria-label="Close reveal" type="button">
		<span aria-hidden="true">&times;</span>
	</button></div>' : '') . "
		</div>";

//==End
// End Class
// End File