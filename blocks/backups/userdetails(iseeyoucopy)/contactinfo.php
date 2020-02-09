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
//=== member contact stuff
$HTMLOUT.= "<div class='row'>";
$HTMLOUT.= (($CURUSER['class'] >= UC_STAFF || $CURUSER['id'] || $user['show_email'] === 'yes') ? '
		<div class="card small-6 medium-4 large-2 columns">
			<h6 class="subheader">' . $lang['userdetails_email'] . '</h6>
			<p><a class="altlink" href="mailto:' . /*decrypt_email(*/htmlsafechars($user['email'])/*)*/ . '"  title="' . $lang['userdetails_email_click'] . '" target="_blank"><i class="fas fa-at"></i>' . $lang['userdetails_send_email'] . '</a></p>
		</div>' : '') . ($user['google_talk'] !== '' ? '
		<div class="card small-6 medium-4 large-2 columns">
			<h6 class="subheader">' . $lang['userdetails_gtalk'] . '</h6>
			<p><a class="altlink" href="http://talkgadget.google.com/talkgadget/popout?member=' . htmlsafechars($user['google_talk']) . '" title="' . $lang['userdetails_gtalk_click'] . '"  target="_blank"><i class="fab fa-google" aria-hidden="true"></i></a></p>
		</div>' : '') . ($user['msn'] !== '' ? '
		<div class="card small-6 medium-4 large-2 columns">
			<h6 class="subheader">' . $lang['userdetails_msn'] . '</h6>
			<p><a class="altlink" href="http://members.msn.com/' . htmlsafechars($user['msn']) . '" target="_blank" title="' . $lang['userdetails_msn_click'] . '"><i class="fab fa-windows" aria-hidden="true"></i></a></p>
		</div>' : '') . ($user['yahoo'] !== '' ? '
		<div class="card small-6 medium-4 large-2 columns">
			<h6 class="subheader">' . $lang['userdetails_yahoo'] . '</h6>
			<p><a class="altlink" href="http://webmessenger.yahoo.com/?im=' . htmlsafechars($user['yahoo']) . '" target="_blank" title="' . $lang['userdetails_yahoo_click'] . '"><i class="fab fa-yahoo" aria-hidden="true"></i></a></p>
		</div>' : '') . ($user['icq'] !== '' ? '
		<div class="card small-6 medium-4 large-2 columns">
			<h6 class="subheader">' . $lang['userdetails_icq'] . '</h6>
			<p><a class="altlink" href="http://people.icq.com/people/&amp;uin=' . htmlsafechars($user['icq']) . '" title="' . $lang['userdetails_icq_click'] . '" target="_blank"><img src="pic/forums/icq.gif" alt="icq" /></a></p>
		</div>' : '') . ($user['website'] !== '' ? '
		<div class="card small-6 medium-4 large-2 columns">
			<h6 class="subheader">' . $lang['userdetails_website'] . '</h6>
			<p><a class="altlink" href="' . htmlsafechars($user['website']) . '" target="_blank" title="' . $lang['userdetails_website_click'] . '"><i class="fas fa-link" aria-hidden="true"></i>' . htmlsafechars($user['website']) . '</a></p>
		</div>' : '');
	$HTMLOUT.= "</div>";
//==end
// End Class
// End File
