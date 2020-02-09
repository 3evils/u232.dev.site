<?php
function template_generate()
{
	global $context, $txt;
	
	// Display the new key.
	echo '
	<span class="upperframe"><span></span></span>
		<div class="roundframe">
			<div style="text-align: center;"><strong>', $txt['invite_new_key'], '</strong>' . '<span style="color: green;">' .  $context['invitation_key'] . '</span></div>
		</div>
	<span class="lowerframe"><span></span></span>';
}

function template_group_settings()
{
	global $context, $txt, $scripturl;
	
	// Display all settings.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_settings_title'], '
		</h3>
	</div>
	<form action="', $scripturl, '?action=admin;area=invite;sa=modifygroup" method="post" accept-charset="UTF-8">
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="settings">
					<dt>
						', $txt['invite_groups'], '
					</dt>
					<dd>';
	
	foreach($context['invite_groups'] as $group)
		echo '
						<div style="padding-bottom: 4px;">
							<input type="text" name="group_' . $group['id_group'] . '" value="' . $group['max_invites'] . '" size="6" class="input_text" /> ' . $group['group_name'] . '
						</div>';
	
	echo '
					</dd>
				</dl>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<br /><br />
		<hr />
		<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		<div style="text-align: right;" ><input class="button_submit" name="save" type="submit" value="', $txt['save'],'" /></div>
	</form>
	';
}

function template_invite_generate()
{
	global $context, $txt;
	
	// Display the new key.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_new_key_head'], '
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe"><div class="innerframe">
		<div style="text-align: center;"><strong>', $txt['invite_new_key'], '</strong>' . '<span style="color: green;">' .  $context['invitation_key'] . '</span></div>
	</div></div>
	<span class="lowerframe"><span></span></span>';
}

function template_no_keys()
{
	global $txt;
	
	// Display a message.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_error'],'
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe"><div class="innerframe">
		<div style="text-align: center;">', $txt['invite_no_key'], '</div>
	</div></div>
	<span class="lowerframe"><span></span></span>';
}

function template_email()
{
	global $txt, $scripturl;
	
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_email_title'], '
		</h3>
	</div>
	<form action="', $scripturl, '?action=invite;area=email" method="post" accept-charset="UTF-8">
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="settings">
					<dt>
						<strong>', $txt['invite_email_recipient_name'], '</strong>
					</dt>
					<dd>
						<input type="text" name="r_name" value="" class="input_text" />
					</dd>
					<dt>
						<strong>', $txt['invite_email_recipient'], '</strong>
					</dt>
					<dd>
						<input type="text" name="r_email" value="" class="input_text" />
					</dd>
					<dt>
						<strong>', $txt['invite_email_recipient_message'], '</strong>
					</dt>
					<dd>
						<textarea name="r_message" rows="3" cols="35" style="width: 100%"></textarea>
					</dd>
				</dl>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<div style="text-align: right;" ><input class="button_submit" name="submit" type="submit" value="', $txt['send'],'" /></div>
	</form>';
}

function template_email_empty()
{
	global $txt;
	
	// Display a message.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_error'],'
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe"><div class="innerframe">
		<div style="text-align: center;">', $txt['invite_email_empty'], '</div>
	</div></div>
	<span class="lowerframe"><span></span></span>';
}

function template_email_sent()
{
	global $txt;
	
	// Display a message.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_error'],'
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe"><div class="innerframe">
		<div style="text-align: center;">', $txt['invite_email_sent'], '</div>
	</div></div>
	<span class="lowerframe"><span></span></span>';
}

function template_email_not_sent()
{
	global $txt;
	
	// Display a message.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_error'],'
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe"><div class="innerframe">
		<div style="text-align: center;">', $txt['invite_email_not_sent'], '</div>
	</div></div>
	<span class="lowerframe"><span></span></span>';
}

function template_email_invalid()
{
	global $txt;
	
	// Display a message.
	echo '
	<div class="cat_bar">
		<h3 class="catbg">
			', $txt['invite_error'],'
		</h3>
	</div>
	<span class="clear upperframe"><span></span></span>
	<div class="roundframe"><div class="innerframe">
		<div style="text-align: center;">', $txt['invite_email_invalid'], '</div>
	</div></div>
	<span class="lowerframe"><span></span></span>';
}
?>