<?php
// Version: 0.9.1; ProfilePictures
// This file is a part of Ultimate Profile mod
// Author: Jovan Turanjanin
// Thanks Yagiz for adjusting this template to SMF 2.0

function template_pictures_edit()
{
	global $context, $scripturl, $settings, $txt;
	
	// The main containing header.
	echo '
	<form action="', $context['profile_action'] ,'" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator" enctype="multipart/form-data">
		<h3 class="catbg">
			<span class="left"></span>
			<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
			', $txt['profile_picture_caption'] ,'
		</h3>
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
				<div class="content">
					<dl>
						<dt>
							<strong>', $txt['profile_picture_title'] ,'</strong>
						</dt>
						<dd>
							<input type="text" name="title" value="', @$context['picture_title'] ,'" class="input_text" size="50" />
						</dd>
					</dl>';

	echo '
					<dl>
						<dt>
							<strong>', $txt['profile_picture_description'] ,':</strong>
						</dt>
						<dd>
							<textarea class="editor" name="description" rows="5" cols="50">', @$context['picture_description'] ,'</textarea>
						</dd>
					</dl>';
	echo '						
					<dl>
						<dt>
							<strong>', $txt['profile_album_parent'] ,'</strong>
						</dt>
						<dd>
						<select name="album_id" id="album_id">
								<option value="0">' . $txt['profile_album_basic'] . '</option>';
						foreach ($context['album_tree'] as $album)
						{	
								echo '
								<option value="', $album['id'] ,'"', (@$context['album_parent'] == $album['id']) ? ' selected="selected"' : '', '> ' . str_repeat('=', $album['level']) . '=> ' . $album['title'] . '</option>';
						}
						echo '
							</select>
						</dd>
					</dl>';

	if ($context['picture_upload'])
	echo '
					<dl>
						<dt>
							<strong>', $txt['profile_picture_path'] ,':</strong>
						</dt>
						<dd>
							<input type="file" name="picture" size="48" class="input_file"  />
						</dd>
					</dl>';
						
	echo '
					<div class="righttext">
						<input type="submit" value="', $txt['save'], '" class="button_submit" />
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
						<input type="hidden" name="u" value="', $context['id_member'], '" />
						<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
					</div>
				</div>
			<span class="botslice"><span></span></span>
		</div>
		<br />
	</form>';
}


function template_album_show()
{
	global $context, $settings, $txt, $scripturl, $sc;
	
	echo '
	<h3 class="catbg">
		<span class="left"></span>
		<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
		<a href="', $scripturl ,'?action=profile;u=', $context['member']['id'] ,'">', $context['member']['name'] ,'</a> - <a href="', $scripturl ,'?action=profile;area=pictures;u=', $context['member']['id'] ,'">', $txt['profile_picture_caption'] , '</a> ', (isset($context['album']['title'])) ? ' - ' . $context['album']['title'] : '', '
	</h3>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<div class="content">
			<table border="0" width="100%" cellspacing="1" cellpadding="4" align="center" class="bordercolor">';
			
			if (!empty($context['albums'])) {
				echo '
				<tr>
					<td class="windowbg" style="padding-bottom: 2ex;">
						<table border="0" cellpadding="4" cellspacing="1" width="100%" id="albums">';
				foreach ($context['albums'] as $album) {
					echo '
						<tr>
							<td width="5%"><img src="', $settings['images_url'] ,'/board.gif" alt="" /></td>
							<td><a href="', $album['url'] ,'">', $album['title'] ,'</a><br /><div class="smalltext">', $txt['profile_albums_pic_count'] ,': ', $album['pictures_count'] ,'</div></td>
						</tr>';
				}
				
				echo '	</table>
					</td>
				</tr>';		
			}
	
			if (!empty($context['pictures'])) {
				echo '
				<tr>
					<td class="windowbg2" style="padding-bottom: 2ex;">
						<table border="0" cellpadding="4" cellspacing="1" width="100%" id="pictures">';
									
					
						$i = 1;
						foreach ($context['pictures'] as $picture) {
							if ($i == 1)
								echo '
								<tr>';
								
								echo '
									<td align="center" valign="top" class="windowbg2" style="clear: both;">
										<a href="', $picture['url'] ,'"><img src="', $picture['thumb'] ,'" alt="" title="', $picture['description'], '" border="0" /></a>
										<br />
										<b>', $picture['title'], '</b>
									</td>';
							
							if ($i == 3)
								echo '
								</tr>';
							
							$i++;
							if ($i == 4) $i = 1;
						}				
					echo '
						</table>
					</td>
				</tr>';
			} else 
				if (empty($context['albums']))
					echo '
					<tr>
						<td class="windowbg2" style="padding-bottom: 2ex;">', $txt['profile_pictures_no'] ,'</td>
					</tr>';
	
	echo '
		</table>
		
		</div>
		<span class="botslice"><span></span></span>
	</div>';
	
	// Show more page numbers.
	echo '
	<div class="pagesection">
		<div class="pagelinks align_left">
			', $txt['pages'], ': ', $context['page_index'], '
		</div>
	</div>';
	
	if ($context['can_add'])
		echo '<br />
		<h3 class="catbg">
			<span class="left"></span>
			<span style="float: left;">
				<a href="', $scripturl ,'?action=profile;area=pictures;u=', $context['member']['id'] , ';add=' . $context['album']['id'] . '">', $txt['profile_picture_add'] ,'</a>
			</span>
			<span style="float: right;">
				', (isset($context['can_add'])) ? 
					' <a href="' . $scripturl . '?action=profile;area=pictures;u=' . $context['member']['id'] . ';addalb=' . $context['album']['id'] . '"><img style="vertical-align: middle" src="' . $settings['images_url'] . '/buttons/im_reply.gif" alt="' . $txt['profile_album_add'] . '" title="' . $txt['profile_album_add'] . '" /></a> '
				: '', 
				(isset($context['can_edit'])) ? 
					  '<a href="' . $scripturl . '?action=profile;area=pictures;u=' . $context['member']['id'] . ';editalb=' . $context['album']['id'] . '"><img style="vertical-align: middle" src="' . $settings['images_url'] . '/buttons/modify.gif" alt="' . $txt['profile_album_edit'] . '" title="' . $txt['profile_album_edit'] . '" /></a>  
					  <a onclick="javascript:return confirm(\'' . $txt['profile_album_delete_confirm'] . '\')" href="' . $scripturl . '?action=profile;area=pictures;u=' . $context['member']['id'] . ';deletealb=' . $context['album']['id'] . ';sesc=' . $sc . '"><img style="vertical-align: middle" src="' . $settings['images_url'] . '/buttons/delete.gif" alt="' . $txt['profile_album_delete'] . '" title="' . $txt['profile_album_delete'] . '" /></a>'  
				: '', '
			</span>
		</h3>';
}

function template_pictures_view()
{
	global $context, $settings, $txt, $scripturl;
	
	echo '
	<h3 class="catbg">
		<span class="left"></span>
		<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
		<a href="', $scripturl , '?action=profile;u=', $context['member']['id'], ';area=pictures">', sprintf ($txt['profile_picture_members'], $context['member']['name']) ,'</a> - ', (isset($context['album']['title'])) ? '<a href="' . $scripturl . '?action=profile;area=pictures;u=' . $context['member']['id'] . ';album=' . $context['picture']['id_album'] . '">' . $context['album']['title'] . '</a> - ' : '', $context['picture']['title'] ,'
	</h3>';
	
	if (!empty($context['picture']['description']))
		echo '
		<p class="windowbg description">', $context['picture']['description'] ,'</p>';
	
	echo '
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<div class="content" style="text-align: center;">
			<img src="', $context['picture']['image'] ,'" alt="" />
			
			<br /><br />';
		if (isset($context['picture']['edit'])) {
			echo '
				<div class="righttext smalltext">
					<a href="', $context['picture']['edit'] ,'">', $txt['profile_picture_edit'] ,'</a> &nbsp;
					<a href="', $context['picture']['delete'] ,'">', $txt['profile_picture_delete'] ,'</a>
				</div>';
		}
		echo '
				<div class="lefttext smalltext">';
		if ($context['picture']['previous_id'])
			echo '
					&nbsp;<a href="', $scripturl ,'?action=profile;area=pictures;u=', $context['member']['id'] ,';view=', $context['picture']['previous_id'],'">', $txt['profile_picture_previous'] ,'</a>&nbsp;';
		if ($context['picture']['next_id'])
			echo '
					&nbsp;<a href="', $scripturl ,'?action=profile;area=pictures;u=', $context['member']['id'] ,';view=', $context['picture']['next_id'],'">', $txt['profile_picture_next'] ,'</a>&nbsp;';
	
	echo '		</div>
		</div>
		<span class="botslice"><span></span></span>
	</div>
	<br />';
	
	if (@$context['member']['options']['comments_disable'] != 1) {
		echo '
	
	<script type="text/javascript">
	function comment() {
		document.getElementById("comment").style.display = "block";
	}
	</script>
	
	<h3 class="catbg" style="width: 65%; margin: auto; padding: auto;">
		<span class="left"></span>
		<a href="javascript:void(0);" onclick="comment()">', $txt['profile_comment_add'] ,'</a>
	</h3>
	
	<div class="windowbg2" id="comment" style="width: 65%; margin: auto; padding: auto; display: none;">
		<span class="topslice"><span></span></span>
		<div class="content">
			<form action="', $scripturl ,'?action=profile;area=pictures;u=', $context['member']['id'] ,';comment=', (int)$_GET['view'] ,'" method="post">
				', $txt['profile_comment'] ,'<br />
				<textarea  cols="50" rows="4" name="comment"></textarea><br />
				<br />
				<input type="submit" value="', $txt['save'] ,'" class="input_button" />
				<input type="hidden" name="sc" value="', $context['session_id'], '" />
			</form>
		</div>
		<span class="botslice"><span></span></span>
	</div>
	
	<br />';
		
	// Only show comments if they have made some!
	if (!empty($context['comments'])) {
		echo '
	<div id="forumposts">
		<h3 class="catbg"><span class="left"></span>', $txt['profile_comments'] ,'</h3>';
			
		
		foreach ($context['comments'] as $comment) {
			echo '
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="poster">
				<h4><a href="', $scripturl ,'?action=profile;u=', $comment['author']['id_member'] ,'">', $comment['author']['real_name'] ,'</a></h4>
				<ul class="reset smalltext">
					<li class="avatar" style="overflow: auto;">', $comment['author']['avatar'] ,'</li>
					<li>', $settings['use_image_buttons'] ? '<img src="' . $comment['author']['online']['image_href'] . '" alt="' . $comment['author']['online']['text'] . '" align="middle" />' : $comment['author']['online']['text'], $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $comment['author']['online']['text'] . '</span>' : '', '</li>
				</ul>
			</div>
			<div class="postarea">
				<div class="flow_hidden">
					<div class="keyinfo">
						<h5>', $comment['time'] ,'</h5>
					</div>
				</div>
				<div class="post">
					<div class="inner">', $comment['body'];
					
					if ($context['can_delete'])
						echo '
						<span onclick="javascript:return confirm(\'' . $txt['profile_comment_delete_confirm'] . '\')" class="smalltext" style="float: right; padding-top: 2em;"><a href="', $comment['delete'], '">', $txt['profile_comment_delete'] ,'</a></span>';
					
					echo '
					</div>
				</div>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<hr class="post_separator" />';
		}
		
	echo '
	</div>';
	
	} else {
		echo '
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
				<div class="content" style="text-align: center;">', $txt['profile_comment_no'] ,'</div>
			<span class="botslice"><span></span></span>
		</div>';
	}

	// Show more page numbers.
	echo '
	<div class="pagesection">
		<div class="pagelinks align_left">
			', $txt['pages'], ': ', $context['page_index'], '
		</div>
	</div>';
	}
}

function template_albums_edit()
{
	global $context, $scripturl, $settings, $txt;
	
	// The main containing header.
	echo '
	<form action="', $context['profile_action'] ,'" method="post" accept-charset="', $context['character_set'], '" name="album" id="creator">
		<h3 class="catbg">
			<span class="left"></span>
			<img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
			', $txt['profile_picture_caption'] ,'
		</h3>
		<div class="windowbg2">
			<span class="topslice"><span></span></span>
				<div class="content">
					<dl>
						<dt>
							<strong>', $txt['profile_album_title'] ,'</strong>
						</dt>
						<dd>
							<input type="text" name="title" value="', @$context['album_title'] ,'" class="input_text" size="50" />
						</dd>
					</dl>
					<dl>
						<dt>
							<strong>', $txt['profile_album_parent'] ,'</strong>
						</dt>
						<dd>
							<select name="parent_id" id="parent_id">
								<option value="0">' . $txt['profile_album_basic'] . '</option>';
						foreach ($context['album_tree'] as $album)
						{	
								echo '
								<option value="', $album['id'] ,'"', ($context['album_parent'] == $album['id']) ? ' selected="selected"' : '', '> ' . str_repeat('=', $album['level']) . '=> ' . $album['title'] . '</option>';
						}
						echo '
							</select>
						</dd>
					</dl>
					<div class="righttext">
						<input type="submit" value="', $txt['save'], '" class="button_submit" />
						<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
						<input type="hidden" name="u" value="', $context['id_member'], '" />
						<input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
					</div>
				</div>
			<span class="botslice"><span></span></span>
		</div>
		<br />
	</form>';
}

?>