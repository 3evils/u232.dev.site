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
    //=== watched user stuff
    $the_flip_box = '<button class="tiny button float-right" data-toggle="watchedModal">' . ($user['watched_user'] > 0 ? ''.$lang['userdetails_flip2'].' ' : ''.$lang['userdetails_flip3'].' ') . '</button>';
    $HTMLOUT.= '<div class="card secondary medium-6 large-4 columns">
	<h6>'.$lang['userdetails_watched']. $the_flip_box .'</h6>
		<div class="reveal" id="watchedModal" data-reveal data-close-on-click="true">
		 ' . ($user['watched_user'] > 0 ? '<div class="callout alert">'.$lang['userdetails_watched_since'].'  ' . get_date($user['watched_user'], '</div>') . ' ' : '<div class="callout success"> '.$lang['userdetails_not_watched'].'</div>').'
  <form method="post" action="member_input.php" name="notes_for_staff">
  <fieldset class="fieldset">
		<input name="id" type="hidden" value="' . $id . '">
		<input type="hidden" value="watched_user" name="action"><legend>'.$lang['userdetails_add_watch'].'</legend>
		<input type="radio" value="yes" name="add_to_watched_users"' . ($user['watched_user'] > 0 ? ' checked="checked"' : '') . '> '.$lang['userdetails_yes1'].'
		<input type="radio" value="no" name="add_to_watched_users"' . ($user['watched_user'] == 0 ? ' checked="checked"' : '') . '> '.$lang['userdetails_no1'].'
		<p class="help-text" style="color:red;font-size: xx-small;">* '.$lang['userdetails_watch_change1'].'<br> '.$lang['userdetails_watch_change2'].'</p>
		<textarea id="watched_reason" rows="6" name="watched_reason">' . htmlsafechars($user['watched_user_reason']) . '</textarea><br>
		<input id="watched_user_button" type="submit" value="'.$lang['userdetails_submit'].'" class="tiny buttotn" name="watched_user_button">
	</fieldset>
	</form>
	<button class="close-button" data-close aria-label="Close reveal" type="button">
		<span aria-hidden="true">&times;</span>
	</button>
	</div>
	</div>';
//==End
// End Class
// End File