<?php
/**********************************************************************************
* Profile-Pictures.php                                                                      *
***********************************************************************************
* Version: 0.9.1
* This file is a part of Ultimate Profile mod
* Author: Jovan Turanjanin                                                      *
**********************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');


function pictures ($memID)
{
	global $context, $modSettings, $smcFunc, $txt, $sourcedir;
	
	loadTemplate('ProfilePictures');
	
	require_once $sourcedir . '/Profile-Modify.php';
	loadThemeOptions($memID);
	if ((@$context['member']['options']['pictures_budd_only'] == 1 || @$context['member']['options']['customized_private'] == 1) && @$modSettings['enable_buddylist'] == 1) {
		if (is_buddy($memID, $context['user']['id']) || allowedTo('edit_ultimate_profile_any') || $context['user']['is_owner'])
			$context['can_view_pics'] = true;
		else
			$context['can_view_pics'] = false;
	} else
		$context['can_view_pics'] = true;
	
	
	if ($modSettings['profile_enable_pictures'] != 1 || !$context['can_view_pics'])
		fatal_error($txt['profile_picture_not_allowed'], false);
	
	// Permisions
	$allowed_edit = false;
	$allowed_add = false;
	$allowed_album_modify = false;
		
	if (allowedTo('edit_ultimate_profile_any')) {
		$allowed_edit = true;
		$allowed_add = true;
		$allowed_album_modify = true;

	} elseif ($context['user']['is_owner'] && allowedTo('edit_ultimate_profile_own')) {
		$allowed_add = true;
		$allowed_album_modify = true;
		
		// Are you editing picture that is on your own profile?
		$temp_id = isset($_GET['view']) ? $_GET['view'] : (isset($_GET['edit']) ? $_GET['edit'] : (isset($_GET['edit2']) ? $_GET['edit2'] : (isset($_GET['delete']) ? $_GET['delete'] : '')));
		$request = $smcFunc['db_query']('', '
			SELECT id_member 
			FROM {db_prefix}profile_pictures 
			WHERE id_picture = {int:id_picture}',
			array(
				'id_picture' => (int)$temp_id,
			)
		);
		list($user_id) = $smcFunc['db_fetch_row']($request);
		
		if ($context['user']['id'] == $user_id)
			$allowed_edit = true;
		
		// What about albums?
		$temp_id2 = isset($_GET['album']) ? $_GET['album'] : (isset($_GET['editalb']) ? $_GET['editalb'] : (isset($_GET['editalb2']) ? $_GET['editalb2'] : (isset($_GET['deletealb']) ? $_GET['deletealb'] : '')));
		if ($temp_id2 !== '') {
			$request = $smcFunc['db_query']('', '
				SELECT id_member 
				FROM {db_prefix}profile_albums 
				WHERE id_album = {int:id_album}',
				array(
					'id_album' => (int)$temp_id2,
				)
			);
			list($user_id) = $smcFunc['db_fetch_row']($request);
		
			if ($context['user']['id'] == $user_id)
				$allowed_album_modify = true;
		}
	}
	
	if (isset($_GET['add']) || isset($_GET['add2'])) {
		if ($modSettings['profile_pictures_number'] > 0) { // 0 means unlimited :D.
			$request = $smcFunc['db_query']('', '
				SELECT COUNT(id_picture) 
				FROM {db_prefix}profile_pictures 
				WHERE id_member = {int:id_member}',
				array(
					'id_member' => $memID,
				)
			);
			list($pic_count) = $smcFunc['db_fetch_row']($request);
			
			if ($pic_count >= $modSettings['profile_pictures_number'])
				fatal_error($txt['profile_pictures_over'], false);
		}
	}
	
	$context['can_add'] = $allowed_add;	
	
	// I can't use switch($_GET) here so I'm stuck with elseifs... It will work :)
	if (isset($_GET['add'])) {
		AddPicture($allowed_add);
	} elseif (isset($_GET['add2'])) {
		AddPicture2($allowed_add);
	} elseif (isset($_GET['edit'])) {
		EditPicture($allowed_edit);
	} elseif (isset($_GET['edit2'])) {
		EditPicture2($allowed_edit);
	} elseif (isset($_GET['delete'])) {
		DeletePicture($allowed_edit);
	} elseif (isset($_GET['addalb'])) {
		AddAlbum($allowed_album_modify);
	} elseif (isset($_GET['addalb2'])) {
		AddAlbum2($allowed_album_modify);
	} elseif (isset($_GET['editalb'])) {
		EditAlbum($allowed_album_modify);
	} elseif (isset($_GET['editalb2'])) {
		EditAlbum2($allowed_album_modify);
	} elseif (isset($_GET['deletealb'])) {
		DeleteAlbum($allowed_album_modify);
	} elseif (isset($_GET['comment'])) {
		AddPictureComment();
	} elseif (isset($_GET['delcomment'])) {
		DeletePictureComment();
	} elseif (isset($_GET['view'])) {
		ViewPicture($allowed_edit);
	} else {
		ShowAlbum($allowed_album_modify);
	}
}

function AddPicture ($allowed_add)
{
	global $context, $txt, $scripturl;
	
	$memID = $context['member']['id'];
	
	if (!$allowed_add)
		fatal_error($txt['profile_pictures_add_not'], false);
	
	if (isset($_GET['add']))
		$context['album_parent'] = (int)$_GET['add'];
	$context['sub_template'] = 'pictures_edit';
	$context['album_tree'] = build_tree();
	$context['profile_action'] = $scripturl . '?action=profile;area=pictures;u=' . $memID . ';add2';
	$context['picture_upload'] = true;
}

function AddPicture2 ($allowed_add)
{
	global $txt, $smcFunc, $sourcedir, $modSettings, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('post');
		
	if (!$allowed_add)
		fatal_error($txt['profile_pictures_add_not'], false);
		
	if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_FILES['picture']) || !isset($_POST['album_id']))
		fatal_error($txt['profile_pictures_fields'], false);
	
	if ($_POST['album_id'] > 0) {
		$request = $smcFunc['db_query']('', '
			SELECT pictures 
			FROM {db_prefix}profile_albums 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => (int)$_POST['album_id'],
			)
		);
		if ($smcFunc['db_num_rows']($request) < 1)
			fatal_error($txt['profile_albums_parent_not'], false);
	}
	
	// Are there any errors during upload?
	if (!($_FILES['picture']['error'] == '0') || !file_exists($_FILES['picture']['tmp_name']) || !is_uploaded_file($_FILES['picture']['tmp_name']))
		fatal_error($txt['profile_pictures_upload_fail'], false);
	
	// Is this file a picture or something else?
	$picture = getimagesize($_FILES['picture']['tmp_name']);
	if ($picture == NULL)
		fatal_error($txt['profile_pictures_pic_not'], false);
	
	$time = time();
	$filename = $memID . '_' . $time . '.' . get_extension($_FILES['picture']['name']);
	$thumb_filename = $memID . '_' . $time . '_thumb.' . get_extension($_FILES['picture']['name']);
	
	move_uploaded_file($_FILES['picture']['tmp_name'], $modSettings['profile_pictures_path'] . '/tmp_' . $memID);
	
	// Let's make thumbnails :).
	unset($modSettings['avatar_download_png']); // Delete this line if you want PNG thumbnails (better quality (lossless), much bigger files).
	require_once $sourcedir . '/Subs-Graphics.php';
	createThumbnail($modSettings['profile_pictures_path'] . '/tmp_' . $memID, 120, 89);
	rename($modSettings['profile_pictures_path'] . '/tmp_' . $memID . '_thumb', $modSettings['profile_pictures_path'] . '/' . $thumb_filename);
	
	createThumbnail($modSettings['profile_pictures_path'] . '/tmp_' . $memID, $modSettings['profile_pictures_width'], '');
	rename($modSettings['profile_pictures_path'] . '/tmp_' . $memID . '_thumb', $modSettings['profile_pictures_path'] . '/' . $filename);
	
	@unlink($modSettings['profile_pictures_path'] . '/tmp_' . $memID);
	
	$smcFunc['db_insert']('normal', '{db_prefix}profile_pictures',
			array(
				'id_member' => 'int',
				'time' => 'int',
				'title' => 'text',
				'description' => 'text',
				'filename' => 'text',
				'id_album' => 'int',
			),
			array(
				'id_member' => $memID,
				'time' => $time,
				'title' => htmlspecialchars($_POST['title']),
				'description' => htmlspecialchars($_POST['description']),
				'filename' => htmlspecialchars($_FILES['picture']['name']),
				'id_album' => (int)$_POST['album_id'],
			),
			array('id_picture')
	);
	
	if ($_POST['album_id'] > 0)
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}profile_albums SET 
			pictures = pictures + 1 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => (int)$_POST['album_id'],
			)
		);
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';album=' . $_POST['album_id']);
}

function EditPicture ($allowed_edit)
{
	global $context, $txt, $smcFunc, $scripturl;
	
	$memID = $context['member']['id'];
	
	if (!$allowed_edit)
		fatal_error($txt['profile_pictures_edit_not'], false);
		
	$request = $smcFunc['db_query']('', 'SELECT title, description, id_album 
		FROM {db_prefix}profile_pictures 
		WHERE id_picture = "' . (int)$_GET['edit'] . '"',array());
	list($context['picture_title'], $context['picture_description'], $context['album_parent']) = $smcFunc['db_fetch_row']($request);
	
	$context['sub_template'] = 'pictures_edit';
	$context['album_tree'] = build_tree();
	$context['profile_action'] = $scripturl . '?action=profile;area=pictures;u=' . $memID . ';edit2=' . $_GET['edit'];
	$context['picture_upload'] = false;
}

function EditPicture2 ($allowed_edit)
{
	global $txt, $smcFunc, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('post');
		
	if (!$allowed_edit)
		fatal_error($txt['profile_pictures_edit_not'], false);
	
	if (!isset($_POST['title']) || !isset($_POST['description']) || !isset($_POST['album_id']))
		fatal_error($txt['profile_pictures_fields'], false);
	
	if ($_POST['album_id'] > 0) {
		$request = $smcFunc['db_query']('', '
			SELECT pictures 
			FROM {db_prefix}profile_albums 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => (int)$_POST['album_id'],
			)
		);
		if ($smcFunc['db_num_rows']($request) < 1)
			fatal_error($txt['profile_albums_parent_not'], false);
	}
	
	// Stupid picture count field :P
	$request = $smcFunc['db_query']('', '
		SELECT id_album 
		FROM {db_prefix}profile_pictures 
		WHERE id_picture = {int:id_picture}',
		array(
			'id_picture' => (int)$_GET['edit2'],
		)
	);
	list($old_album_id) = $smcFunc['db_fetch_row']($request);
	
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}profile_pictures SET
		title = {text:title},
		description = {text:description},
		id_album = {int:id_album}
		WHERE id_picture = {int:id_picture}',
		array(
			'title' => htmlspecialchars($_POST['title']),
			'description' => htmlspecialchars($_POST['description']),
			'id_album' => (int)$_POST['album_id'],
			'id_picture' => $_GET['edit2'],
		)
	);
	
	if ($old_album_id !== $_POST['album_id']) {
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}profile_albums SET 
			pictures = pictures - 1 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => $old_album_id,
			)
		);
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}profile_albums SET 
			pictures = pictures + 1 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => (int)$_POST['album_id'],
			)
		);
	}
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';album=' . $_POST['album_id']);
}

function DeletePicture ($allowed_edit)
{
	global $txt, $smcFunc, $modSettings, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('get');
		
	if (!$allowed_edit)
		fatal_error($txt['profile_pictures_delete_not'], false);
	
	$album_id = delete_picture($_GET['delete']);
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';album=' . $album_id);
}

function AddAlbum ($allowed_add)
{
	global $context, $txt, $scripturl;
	
	$memID = $context['member']['id'];
	
	if (!$allowed_add)
		fatal_error($txt['profile_albums_add_not'], false);
	
	if (isset($_GET['addalb']))
		$context['album_parent'] = (int)$_GET['addalb'];
	$context['sub_template'] = 'albums_edit';
	$context['album_tree'] = build_tree();
	$context['profile_action'] = $scripturl . '?action=profile;area=pictures;u=' . $memID . ';addalb2';
}

function AddAlbum2 ($allowed_add) {
	global $txt, $smcFunc, $sourcedir, $modSettings, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('post');
		
	if (!$allowed_add)
		fatal_error($txt['profile_albums_add_not'], false);
		
	if (!isset($_POST['title']) || !isset($_POST['parent_id']))
		fatal_error($txt['profile_albums_fields'], false);
	
	if ($_POST['parent_id'] > 0) {
		$request = $smcFunc['db_query']('', '
			SELECT pictures 
			FROM {db_prefix}profile_albums 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => (int)$_POST['parent_id'],
			)
		);
		if ($smcFunc['db_num_rows']($request) < 1)
			fatal_error($txt['profile_albums_parent_not'], false);
	}
	
	$smcFunc['db_insert']('normal', '{db_prefix}profile_albums',
			array(
				'id_member' => 'int',
				'title' => 'text',
				'pictures' => 'int',
				'parent_id' => 'int',
			),
			array(
				'id_member' => $memID,
				'title' => htmlspecialchars($_POST['title']),
				'pictures' => '0',
				'parent_id' => (int)$_POST['parent_id'],
			),
			array('id_album')
	);
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';album=' . $_POST['parent_id']);
}

function EditAlbum ($allowed_edit)
{
	global $context, $txt, $smcFunc, $scripturl;
	
	$memID = $context['member']['id'];
	
	if (!$allowed_edit)
		fatal_error($txt['profile_albums_edit_not'], false);
		
	$request = $smcFunc['db_query']('', '
		SELECT title, parent_id 
		FROM {db_prefix}profile_albums 
		WHERE id_album = {int:id_album}',
		array(
			'id_album' => (int)$_GET['editalb'],
		)
	);
	list($context['album_title'], $context['album_parent']) = $smcFunc['db_fetch_row']($request);
	
	$context['sub_template'] = 'albums_edit';
	$context['album_tree'] = build_tree($_GET['editalb']);
	$context['profile_action'] = $scripturl . '?action=profile;area=pictures;u=' . $memID . ';editalb2=' . $_GET['editalb'];
}

function EditAlbum2 ($allowed_edit)
{
	global $txt, $smcFunc, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('post');
		
	if (!$allowed_edit)
		fatal_error($txt['profile_albums_edit_not'], false);
	
	if (!isset($_POST['title']) || !isset($_POST['parent_id']))
		fatal_error($txt['profile_albums_fields'], false);
	
	if ($_POST['parent_id'] > 0) {
		$request = $smcFunc['db_query']('', '
			SELECT pictures 
			FROM {db_prefix}profile_albums 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => (int)$_POST['parent_id'],
			)
		);
		if ($smcFunc['db_num_rows']($request) < 1)
			fatal_error($txt['profile_albums_parent_not'], false);
	}
	
	$smcFunc['db_query']('', '
		UPDATE {db_prefix}profile_albums SET
			title = {string:title},
			parent_id = {int:parent_id}
		WHERE id_album = {int:id_album}',
		array(
			'title' => htmlspecialchars($_POST['title']),
			'parent_id' => (int)$_POST['parent_id'],
			'id_album' => (int)$_GET['editalb2'],
		)
	);
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';album=' . $_GET['editalb2']);
}

function DeleteAlbum ($allowed_edit)
{
	global $txt, $smcFunc, $modSettings, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('get');
		
	if (!$allowed_edit)
		fatal_error($txt['profile_albums_delete_not'], false);
	
	if ($_GET['deletealb'] == 0)
		redirectexit('action=profile;u=' . $memID . ';area=pictures');
	
	$request = $smcFunc['db_query']('', '
		SELECT parent_id 
		FROM {db_prefix}profile_albums 
		WHERE id_album = {int:id_album}',
		array(
			'id_album' => (int)$_GET['deletealb'],
		)
	);
	list($parent_id) = $smcFunc['db_fetch_row']($request);
	
	delete_album($_GET['deletealb']);
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';album=' . $parent_id);
}

function AddPictureComment()
{
	global $txt, $context, $smcFunc, $sourcedir, $scripturl, $modSettings, $language;
	
	$memID = $context['member']['id'];
	
	// Guests are not allowed to comment.
	is_not_guest();

	if (empty($_POST['comment']))
		fatal_error($txt['profile_comment_field'], false);
	
	// Integration with AEVA mod (Thanks Nao 尚 ;))
	if (isset($modSettings['aeva_enable']) && file_exists($sourcedir . '/Subs-Aeva.php')) {
		@include_once($sourcedir . '/Subs-Aeva.php');
		if (function_exists('aeva_onposting'))
			$_POST['comment'] = aeva_onposting($_POST['comment']);
	}
		
	checkSession('post');
	
	// Only buddies can post comments?
	if (isset($context['member']['options']['comments_budd_only']) && $context['member']['options']['comments_budd_only'] == 1) {
		if (!is_buddy($memID, $context['user']['id']) && !allowedTo('edit_ultimate_profile_any'))
			fatal_error($txt['profile_comments_buddies_only'], false);
	}

	$request = $smcFunc['db_insert']('normal', '{db_prefix}picture_comments',
			array(
				'id_member' => 'int',
				'comment' => 'text',
				'time' => 'int',
				'comment_picture_id' => 'int',
			),
			array(
				'id_member' => $context['user']['id'],
				'title' => htmlspecialchars($_POST['comment']),
				'time' => time(),
				'comment_picture_id' => (int)$_GET['comment'],
			),
			array('id_comment')
	);
	
	// Should we notify the user?
	if (@$context['member']['options']['comments_notif_disable'] != 1 && $context['user']['id'] != $memID) {
		$request = $smcFunc['db_query']('', '
			SELECT lngfile 
			FROM {db_prefix}members 
			WHERE id_member = {int:id_member}',
			array(
				'id_member' => $memID,
			)
		);
		list($user_language) = $smcFunc['db_fetch_row']($request);
		
		loadLanguage('UltimateProfile', empty($user_language) || empty($modSettings['userLanguage']) ? $language : $user_language, false);
		
		require_once $sourcedir . '/Subs-Post.php';
		sendpm(array('to' => array($memID), 'bcc' => array()), sprintf($txt['profile_notif_piccom_subject'], $context['user']['name']), sprintf($txt['profile_notif_piccom_body'], $context['user']['name'], $scripturl . '?action=profile;area=pictures;view=' . (int)$_GET['comment']), false, array('id' => 0, 'name' => $txt['profile_notif_com_user'], 'username' => $txt['profile_notif_com_user']));
	}
		
	redirectexit('action=profile;area=pictures;u=' . $memID . ';view=' . (int)$_GET['comment']);
}

function DeletePictureComment()
{
	global $context, $smcFunc, $context;
	
	$memID = $context['member']['id'];
	
	checkSession('get');
		
	$allowed = false;
	
	if (allowedTo('edit_ultimate_profile_any'))
		$allowed = true;
	elseif ($context['user']['is_owner'] && allowedTo('edit_ultimate_profile_own')) {
		// Are you deleting comment that is on your own profile?
		$request = $smcFunc['db_query']('', '
			SELECT pic.id_member
			FROM ({db_prefix}picture_comments AS com, {db_prefix}profile_pictures AS pic)
			WHERE com.comment_picture_id = pic.id_picture 
				AND com.id_comment = {int:id_comment}',
				array(
					'id_comment' => (int)$_GET['delcomment'],
				)
			);
		list($user_id) = $smcFunc['db_fetch_row']($request);
		
		if ($context['user']['id'] == $user_id)
			$allowed = true;
	}
	
	$request = $smcFunc['db_query']('', '
		SELECT comment_picture_id 
		FROM {db_prefix}picture_comments 
		WHERE id_comment = {int:id_comment}',
		array(
			'id_comment' => (int)$_GET['delcomment'],
		)
	);
	list($picture_id) = $smcFunc['db_fetch_row']($request);
	
	if ($allowed)
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}picture_comments 
			WHERE id_comment = {int:id_comment}',
			array(
				'id_comment' => (int)$_GET['delcomment'],
			)
		);
	
	redirectexit('action=profile;area=pictures;u=' . $memID . ';view=' . $picture_id);
}

function ViewPicture ($allowed_edit)
{
	global $smcFunc, $txt, $context, $scripturl, $modSettings, $settings;
	
	$memID = $context['member']['id'];
	
	$request = $smcFunc['db_query']('', '
		SELECT time, title, description, filename, id_album
		FROM {db_prefix}profile_pictures
		WHERE id_member = {int:id_member} 
			AND id_picture = {int:id_picture}',
		array(
			'id_member' => $memID,
			'id_picture' => (int)$_GET['view'],
		)
	);
	if ($smcFunc['db_num_rows']($request) < 1)
		fatal_error($txt['profile_pictures_not_found'], false);

	$context['picture'] = $smcFunc['db_fetch_assoc']($request);
	$context['picture']['image'] = $modSettings['profile_pictures_url'] . '/' . $memID . '_' . $context['picture']['time'] . '.' . get_extension($context['picture']['filename']);
	$context['picture']['time'] = timeformat($context['picture']['time']);
	
	$request = $smcFunc['db_query']('', '
		SELECT title	
		FROM {db_prefix}profile_albums 
		WHERE id_album = {int:id_album}',
		array(
			'id_album' => $context['picture']['id_album'],
		)
	);
	list($context['album']['title']) = $smcFunc['db_fetch_row']($request);
	
	if ($allowed_edit) {
		$context['picture']['edit'] = $scripturl . '?action=profile;area=pictures;u=' . $memID . ';edit=' . $_GET['view'];
		$context['picture']['delete'] = $scripturl . '?action=profile;area=pictures;u=' . $memID . ';delete=' . $_GET['view'] . ';sesc=' . $context['session_id'];
	}
	
	// Prepare and load comments.
	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}picture_comments 
		WHERE comment_picture_id = {int:picture_id}',
		array(
			'picture_id' => (int)$_GET['view'],
		)
	);
	list($commentCount) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	$maxComments = 10; // Hard-coded; should this be configurable?
	
	// For avatars: if we're always html resizing, assume it's too large.
	if ($modSettings['avatar_action_too_large'] == 'option_html_resize' || $modSettings['avatar_action_too_large'] == 'option_js_resize') {
		$avatar_width = !empty($modSettings['avatar_max_width_external']) ? ' width="' . $modSettings['avatar_max_width_external'] . '"' : '';
		$avatar_height = !empty($modSettings['avatar_max_height_external']) ? ' height="' . $modSettings['avatar_max_height_external'] . '"' : '';
	} else {
		$avatar_width = '';
		$avatar_height = '';
	}

	// Make sure the starting place makes sense and construct our friend the page index.
	$context['start'] = (int)$_REQUEST['start'];
	$context['page_index'] = constructPageIndex($scripturl . '?action=profile;u=' . $memID . ';area=pictures;view=' . $_GET['view'], $context['start'], $commentCount, $maxComments, false);
	$context['current_page'] = $context['start'] / $maxComments;
	$context['current_member'] = $memID;
	$context['can_delete'] = allowedTo('edit_ultimate_profile_any') || ($context['user']['is_owner'] && allowedTo('edit_ultimate_profile_own'));
		
	$request = $smcFunc['db_query']('', '
			SELECT com.id_comment, com.id_member, com.comment, com.time, mem.real_name, mem.show_online,
				IFNULL(lo.log_time, 0) AS is_online, IFNULL(a.id_attach, 0) AS id_attach, a.filename, a.attachment_type, mem.avatar
			FROM {db_prefix}picture_comments as com
				LEFT JOIN {db_prefix}members AS mem ON (com.id_member = mem.id_member)
				LEFT JOIN {db_prefix}log_online AS lo ON (lo.id_member = com.id_member)
				LEFT JOIN {db_prefix}attachments AS a ON (a.id_member = com.id_member)
			WHERE com.comment_picture_id = {int:id_picture}
			ORDER BY id_comment DESC 
			LIMIT {int:start}, {int:maxcomments}',
			array(
				'id_picture' => (int)$_GET['view'],
				'start' => $context['start'],
				'maxcomments' => $maxComments,
			)
	);
	
	while ($row = $smcFunc['db_fetch_assoc']($request)) {
		
		censorText($row['comment']);
		
		$row['is_online'] = (!empty($row['show_online']) || allowedTo('moderate_forum')) && $row['is_online'] > 0;
		
		$context['comments'][] = array (
			'body' => parse_bbc($row['comment']),
			'author' => array (
				'real_name' => $row['real_name'],
				'id_member' => $row['id_member'],
				'avatar' => $row['avatar'] == '' ? ($row['id_attach'] > 0 ? '<img src="' . (empty($row['attachment_type']) ? $scripturl . '?action=dlattach;attach=' . $row['id_attach'] . ';type=avatar' : $modSettings['custom_avatar_url'] . '/' . $row['filename']) . '" alt="" class="avatar" border="0" />' : '') : (stristr($row['avatar'], 'http://') ? '<img src="' . $row['avatar'] . '"' . $avatar_width . $avatar_height . ' alt="" class="avatar" border="0" />' : '<img src="' . $modSettings['avatar_url'] . '/' . htmlspecialchars($row['avatar']) . '" alt="" class="avatar" border="0" />'),
				'online' => array (
					'is_online' => $row['is_online'],
					'text' => &$txt[$row['is_online'] ? 'online' : 'offline'],
					'href' => $scripturl . '?action=pm;sa=send;u=' . $row['id_member'],
					'link' => '<a href="' . $scripturl . '?action=pm;sa=send;u=' . $row['id_member'] . '">' . $txt[$row['is_online'] ? 'online' : 'offline'] . '</a>',
					'image_href' => $settings['images_url'] . '/' . ($row['is_online'] ? 'useron' : 'useroff') . '.gif',
					'label' => &$txt[$row['is_online'] ? 'pm_online' : 'pm_offline']
				),
			),
			'time' => timeformat($row['time']),
			'delete' => $scripturl . '?action=profile;area=pictures;u=' . $memID . ';delcomment=' . $row['id_comment'] . ';sesc=' . $context['session_id'],
		);
		
	}
	$smcFunc['db_free_result']($request);
	
	
	// Previous/next picture.
	$request = $smcFunc['db_query']('', '
		SELECT id_picture 
		FROM {db_prefix}profile_pictures 
		WHERE id_picture < {int:id_picture}
			AND id_member = {int:id_member}
			AND id_album = {int:id_album}
		ORDER BY id_picture DESC 
		LIMIT 1',
		array(
			'id_picture' => (int)$_GET['view'],
			'id_member' => $memID,
			'id_album' => $context['picture']['id_album'],
		)
	);
	list($context['picture']['previous_id']) = $smcFunc['db_fetch_row']($request);
	
	$request = $smcFunc['db_query']('', '
		SELECT id_picture 
		FROM {db_prefix}profile_pictures 
		WHERE id_picture > {int:id_picture}
			AND id_member = {int:id_member}
			AND id_album = {int:id_album}
		ORDER BY id_picture ASC 
		LIMIT 1',
		array(
			'id_picture' => (int)$_GET['view'],
			'id_member' => $memID,
			'id_album' => $context['picture']['id_album'],
		)
	);
	list($context['picture']['next_id']) = $smcFunc['db_fetch_row']($request);
	
	$context['sub_template'] = 'pictures_view';
}

function ShowAlbum ($allowed_album_modify)
{
	global $smcFunc, $context, $modSettings, $scripturl, $txt;
	
	$memID = $context['member']['id'];
	$albumID = isset($_GET['album']) ? (int)$_GET['album'] : 0;
	
	// Album info
	if ($albumID == 0) {
		$context['album']['id'] = 0;
		
		$request = $smcFunc['db_query']('', '
			SELECT COUNT(id_picture) 
			FROM {db_prefix}profile_pictures 
			WHERE id_member = {int:id_member}
				AND id_album = 0',
			array(
				'id_member' => $memID,
			)
		);
		list($context['album']['pictures_count']) = $smcFunc['db_fetch_row']($request);
		
	} else {
		$request = $smcFunc['db_query']('', '
			SELECT id_album, title, pictures, parent_id 
			FROM {db_prefix}profile_albums
			WHERE id_member = {int:id_member}
				AND id_album = {int:id_album}',
				array(
					'id_member' => $memID,
					'id_album' => $albumID,
				)
		);
		list($context['album']['id'], $context['album']['title'], $context['album']['pictures_count'], $context['album']['parent_id']) = $smcFunc['db_fetch_row']($request);
		
		if ($smcFunc['db_num_rows'] ($request) < 1)
			fatal_error($txt['profile_album_not_found'], false);
	
		$context['can_edit'] = $allowed_album_modify;
	}
		
	// Make sure the starting place makes sense and construct our friend the page index.
	$maxPictures = 10; // hard-coded; should this be configurable?
	$context['start'] = (int)$_REQUEST['start'];
	$context['page_index'] = constructPageIndex($scripturl . '?action=profile;u=' . $memID . ';area=pictures;album=' . $albumID, $context['start'], $context['album']['pictures_count'], $maxPictures, false);
	$context['current_page'] = $context['start'] / $maxPictures;
	
	// Show all albums.
	if ($context['start'] == 0) { // Child albums will be shown only on the first page.
		$request = $smcFunc['db_query']('', '
			SELECT id_album, title, pictures
			FROM {db_prefix}profile_albums
			WHERE id_member = {int:id_member}
				AND parent_id = {int:parent_id}',
				array(
					'id_member' => $memID,
					'parent_id' => $albumID,
				)
		);
		
		$context['albums'] = array();
		while ($row = $smcFunc['db_fetch_assoc']($request)) {
			$context['albums'][] = array(
				'id' => $row['id_album'],
				'title' => $row['title'],
				'pictures_count' => $row['pictures'],
				'url' => $scripturl . '?action=profile;area=pictures;u=' . $memID . ';album=' . $row['id_album']
			);
		}
	}
	
	
	// Show all pictures.
	$request = $smcFunc['db_query']('', '
			SELECT id_picture, time, title, description, filename 
			FROM {db_prefix}profile_pictures 
			WHERE id_member = {int:id_member}
				AND id_album = {int:id_album}
			LIMIT {int:start}, {int:maxpictures}',
			array(
				'id_member' => $memID,
				'id_album' => $albumID,
				'start' => $context['start'],
				'maxpictures' => $maxPictures,
			)
	);
	while ($picture = $smcFunc['db_fetch_assoc']($request)) {
		$context['pictures'][] = array(
			'id_picture' => $picture['id_picture'],
			'title' => $picture['title'],
			'description' => $picture['description'],
			'url' => $scripturl . '?action=profile;area=pictures;u=' . $memID . ';view=' . $picture['id_picture'],
			'thumb' => $modSettings['profile_pictures_url'] . '/' . $memID . '_' . $picture['time'] . '_thumb.' . get_extension($picture['filename']),
			'time' => timeformat($picture['time']),
		);
	}
	
	$context['sub_template'] = 'album_show';
}


function delete_picture ($picture_id)
{
	global $smcFunc, $modSettings, $context;
	
	$picture_id = (int)$picture_id;
		
	$request = $smcFunc['db_query']('', '
		SELECT id_member, time, filename, id_album
		FROM {db_prefix}profile_pictures 
		WHERE id_picture = {int:id_picture}',
		array(
			'id_picture' => (int)$picture_id,
		)
	);
	list($memID, $time, $filename, $album_id) = $smcFunc['db_fetch_row']($request);
	
	$normal_filename = $memID . '_' . $time . '.' . get_extension($filename);
	$thumb_filename = $memID . '_' . $time . '_thumb.' . get_extension($filename);
	
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}profile_pictures 
		WHERE id_picture = {int:id_picture}',
		array(
			'id_picture' => $picture_id,
		)
	);
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}picture_comments 
		WHERE comment_picture_id = {int:id_picture}',
		array(
			'id_picture' => $picture_id,
		)
	);
	if ($album_id > 0)
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}profile_albums SET 
			pictures = pictures - 1 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => $album_id,
			)
		);
	
	@unlink($modSettings['profile_pictures_path'] . '/' . $normal_filename);
	@unlink($modSettings['profile_pictures_path'] . '/' . $thumb_filename);
	
	return $album_id;
}

function delete_album ($album_id)
{
	global $smcFunc;
	
	$album_id = (int)$album_id;
	
	$request = $smcFunc['db_query']('', '
		SELECT id_album 
		FROM {db_prefix}profile_albums 
		WHERE parent_id = {int:id_parent}',
		array(
			'id_parent' => $album_id,
		)
	);
	
	while ($album = $smcFunc['db_fetch_assoc']($request)) {
		$request2 = $smcFunc['db_query']('', '
			SELECT id_picture 
			FROM {db_prefix}profile_pictures 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => $album['id_album'],
			)
		);
		while ($picture = $smcFunc['db_fetch_assoc']($request2)) {
			delete_picture($picture['id_picture']);
		}
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}profile_albums 
			WHERE id_album = {int:id_album}',
			array(
				'id_album' => $album['id_album'],
			)
		);
		
		delete_album($album['id_album']);
	}
	
	$request = $smcFunc['db_query']('', '
		SELECT id_picture 
		FROM {db_prefix}profile_pictures 
		WHERE id_album = {int:id_album}',
		array(
			'id_album' => $album_id,
		)
	);
	while ($picture = $smcFunc['db_fetch_assoc']($request)) {
		delete_picture($picture['id_picture']);
	}
	$smcFunc['db_query']('', '
		DELETE FROM {db_prefix}profile_albums 
		WHERE id_album = {int:id_album}',
		array(
			'id_album' => $album_id,
		)
	);
}


function build_tree ($parent_album = '') {
	global $smcFunc, $context, $albums;
	
	// Load all the albums.
	$request = $smcFunc['db_query']('', '
		SELECT id_album, title, parent_id 
		FROM {db_prefix}profile_albums 
		WHERE id_member = {int:id_member}',
		array(
			'id_member' => $context['member']['id'],
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request)) {
		if ($parent_album !== $row['parent_id'] && $parent_album !== $row['id_album']) {
			$albums_temp[$row['parent_id']][$row['id_album']] = array(
				'id' => $row['id_album'],
				'title' => $row['title'],
				'child_albums' => array()
			);
		}
	}
	
	$parents = @$albums_temp[0]; // Top-level albums
	$albums = array(); // Sorted list
	
	if (count($parents) > 0) {
		foreach ($parents as $key => $value) {
			$child_albums = isset($albums_temp[$key]) ? $albums_temp[$key] : array();
			
			if (count($child_albums) > 0) {
				foreach ($child_albums as $keySub => $valueSub) {
					if (array_key_exists($keySub, $albums_temp))
						$child_albums[$keySub]['child_albums'] = $albums_temp[$keySub];
				}
				$parents[$key]['child_albums'] = $child_albums;
			}
		}
		
		foreach ($parents as $key => $value) {
			$albums[] = array(
				'id' => $parents[$key]['id'],
				'title' => $parents[$key]['title'],
				'level' => 0
			);
			
			if (count($parents[$key]['child_albums']) > 0)
				children($parents[$key]['child_albums'], 1);
		}
	}
	
	return $albums;
}

function children ($children, $level) {
	global $albums;
	
	$level++;

	foreach ($children as $key => $value) {
		$albums[] = array (
			'id' => $children[$key]['id'],
			'title' => $children[$key]['title'],
			'level' => $level
		);
		
		if (count($children[$key]['child_albums']) > 0)
			children($children[$key]['child_albums'], $level);
	}
}

?>