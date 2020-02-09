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
 //=== staff Notes
    $the_flip_box_4 = '<button class="tiny button float-right" data-toggle="staffnotesModal">view</button>';
    $HTMLOUT.= '<div class="card secondary medium-6 large-4 columns">
	<h6>'.$lang['userdetails_staffnotes'].'<button class="tiny button float-right" data-toggle="staffnotesModal">' . ($user['staff_notes'] !== '' ? ''.$lang['userdetails_vae'].' ' : ''.$lang['userdetails_add'].' ') . '</a></h6>

	<div class="reveal" id="staffnotesModal" data-reveal data-close-on-click="true">
			<form method="post" action="member_input.php" name="notes_for_staff">
				<input name="id" type="hidden" value="' . (int)$user['id'] . '">
				<input type="hidden" value="staff_notes" name="action" id="action">
				<textarea id="new_staff_note" rows="6" name="new_staff_note">' . htmlsafechars($user['staff_notes']) . '</textarea>
				<input id="staff_notes_button" type="submit" value="'.$lang['userdetails_submit'].'" class="btn" name="staff_notes_button"/>
			</form>
		</div>
	</div>';
//==End
// End Class
// End File