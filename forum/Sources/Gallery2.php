<?php
/*
SMF Gallery Lite Edition
Version 3.0
by:vbgamer45
http://www.smfhacks.com
Copyright 2008-2010 SMFHacks.com

############################################
License Information:
SMF Gallery is NOT free software.
This software may not be redistributed.

Links to http://www.smfhacks.com must remain unless
branding free option is purchased.
#############################################
*/

if (!defined('SMF'))
	die('Hacking attempt...');

function GalleryMain()
{
	global $id_member, $user_info, $modSettings, $boarddir, $boardurl, $currentVersion;

	$id_member = $user_info['id'];

	$currentVersion = '3.0.3';

	// Load the main template file
	loadtemplate('Gallery2');

	// Load the language files
	if (loadlanguage('Gallery') == false)
		loadLanguage('Gallery','english');

	// Setup Gallery Path and Url
	if (empty($modSettings['gallery_url']))
		$modSettings['gallery_url'] = $boardurl . '/gallery/';

    if (empty($modSettings['gallery_path']))
		$modSettings['gallery_path'] = $boarddir . '/gallery/';
		
	if (empty($modSettings['gallery_set_images_per_page']))
		$modSettings['gallery_set_images_per_page'] = 20;
		
	if (empty($modSettings['gallery_thumb_height']))
		$modSettings['gallery_thumb_height'] = 78;
		
	if (empty($modSettings['gallery_thumb_width']))
		$modSettings['gallery_thumb_width'] = 120;
			

	// Gallery Actions
	$subActions = array(
		'main' => 'main',
		'view' => 'ViewPicture',
		'admincat' => 'AdminCats',
		'adminset'=> 'AdminSettings',
		'adminset2'=> 'AdminSettings2',
		'delete' => 'DeletePicture',
		'delete2' => 'DeletePicture2',
		'edit' => 'EditPicture',
		'edit2' => 'EditPicture2',
		'report' => 'ReportPicture',
		'report2' => 'ReportPicture2',
		'deletereport' => 'DeleteReport',
		'reportlist' => 'ReportList',
		'comment' => 'AddComment',
		'comment2' => 'AddComment2',
		'delcomment' => 'DeleteComment',
		'rate' => 'RatePicture',
		'catup' => 'CatUp',
		'catdown' => 'CatDown',
		'addcat' => 'AddCategory',
		'addcat2' => 'AddCategory2',
		'editcat' => 'EditCategory',
		'editcat2' => 'EditCategory2',
		'deletecat' => 'DeleteCategory',
		'deletecat2' => 'DeleteCategory2',
		'viewc' => 'ViewC',
		'myimages' => 'MyImages',
		'approvelist' => 'ApproveList',
		'approve' => 'ApprovePicture',
		'unapprove' => 'UnApprovePicture',
		'add' => 'AddPicture',
		'add2' => 'AddPicture2',
		'search' => 'Search',
		'search2' => 'Search2',
		'regen' => 'ReGenerateThumbnails',
		'regen2' => 'ReGenerateThumbnails2',
	);

	// Follow the sa or just go to main function
	@$sa = $_GET['sa'];
	if (!empty($subActions[$sa]))
		$subActions[$sa]();
	else
		mainview();

}

function mainview()
{
	global $context, $scripturl, $mbname, $txt, $smcFunc, $modSettings, $user_info;
	// View the main gallery

	// Is the user allowed to view the gallery?
	isAllowedTo('smfgallery_view');

	// Load the main gallery template
	$context['sub_template']  = 'mainview';

	$context['gallery_cat_name'] = ' ';


	GalleryUserTabs();


	@$cat = (int) $_REQUEST['cat'];
	if ($cat)
	{
		// Get category name
		$dbresult1 = $smcFunc['db_query']('', "
		SELECT
			id_cat, title, roworder, description, image
		FROM {db_prefix}gallery_cat
		WHERE id_cat = $cat LIMIT 1");

		$row1 = $smcFunc['db_fetch_assoc']($dbresult1);
		$context['gallery_cat_name'] = $row1['title'];
		$smcFunc['db_free_result']($dbresult1);

		// Link Tree
		$context['linktree'][] = array(
					'url' =>  $scripturl . '?action=gallery;cat=' . $cat,
					'name' => $context['gallery_cat_name']
				);


		$context['page_title'] = $mbname . ' - ' . $context['gallery_cat_name'];
		$context['sub_template']  = 'image_listing';

		if (!empty($modSettings['gallery_who_viewing']))
		{
			$context['can_moderate_forum'] = allowedTo('moderate_forum');

				//SMF 1.1
				//Taken from Display.php
				// Start out with no one at all viewing it.
				$context['view_members'] = array();
				$context['view_members_list'] = array();
				$context['view_num_hidden'] = 0;
				
				$whoID = (string) $cat;

				// Search for members who have this picture id set in their GET data.
				$request = $smcFunc['db_query']('', "
					SELECT
						lo.id_member, lo.log_time, mem.real_name, mem.member_name, mem.show_online,
						mg.online_color, mg.id_group, mg.group_name
					FROM {db_prefix}log_online AS lo
						LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = lo.id_member)
						LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group))
					WHERE INSTR(lo.url, 's:7:\"gallery\";s:3:\"cat\";s:" . strlen($whoID ) .":\"$cat\";') OR lo.session = '" . ($user_info['is_guest'] ? 'ip' . $user_info['ip'] : session_id()) . "'");
				while ($row = $smcFunc['db_fetch_assoc']($request))
				{
					if (empty($row['id_member']))
						continue;

					if (!empty($row['online_color']))
						$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
					else
						$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';

					$is_buddy = in_array($row['id_member'], $user_info['buddies']);
					if ($is_buddy)
						$link = '<b>' . $link . '</b>';

					// Add them both to the list and to the more detailed list.
					if (!empty($row['show_online']) || allowedTo('moderate_forum'))
						$context['view_members_list'][$row['log_time'] . $row['member_name']] = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;
					$context['view_members'][$row['log_time'] . $row['member_name']] = array(
						'id' => $row['id_member'],
						'username' => $row['member_name'],
						'name' => $row['real_name'],
						'group' => $row['id_group'],
						'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
						'link' => $link,
						'is_buddy' => $is_buddy,
						'hidden' => empty($row['show_online']),
					);

					if (empty($row['show_online']))
						$context['view_num_hidden']++;
				}

				// The number of guests is equal to the rows minus the ones we actually used ;).
				$context['view_num_guests'] = $smcFunc['db_num_rows']($request) - count($context['view_members']);
				$smcFunc['db_free_result']($request);

				// Sort the list.
				krsort($context['view_members']);
				krsort($context['view_members_list']);


		}
		
		
		$context['start'] = (int) $_REQUEST['start'];
		
		$dbresult = $smcFunc['db_query']('', "
		SELECT p.id_picture, p.commenttotal, p.filesize, p.views, p.thumbfilename, p.filename, p.height, p.width, 
		 p.title, p.id_member, m.member_name, m.real_name, p.date, p.description 
		 FROM {db_prefix}gallery_pic as p
		LEFT JOIN {db_prefix}members AS m on ( p.id_member = m.id_member) 
		WHERE p.id_cat = $cat AND p.approved = 1 ORDER BY id_picture DESC LIMIT $context[start]," . $modSettings['gallery_set_images_per_page']);
		$context['gallery_image_list'] = array();
		while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_image_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);

		
		
		$context['gallery_image_count'] = $smcFunc['db_affected_rows']();


	}
	else
	{
		$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'];
		
		// Category list
		$dbresult = $smcFunc['db_query']('', "
		SELECT 
			id_cat, title, roworder, description, image 
		FROM {db_prefix}gallery_cat ORDER BY roworder ASC");
		$context['gallery_cat_list'] = array();
		while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_cat_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
		
		// Get unapproved pictures
		$dbresult3 = $smcFunc['db_query']('', "
			SELECT 
				COUNT(*) AS total 
			FROM {db_prefix}gallery_pic 
			WHERE approved = 0");
			$totalrow = $smcFunc['db_fetch_assoc']($dbresult3);
			$totalpics = $totalrow['total'];
			$smcFunc['db_free_result']($dbresult3);
		$context['gallery_unapproved_pics']	= $totalpics;
		
		// Get reported pictures
		$dbresult4 = $smcFunc['db_query']('', "
			SELECT 
				COUNT(*) AS total 
			FROM {db_prefix}gallery_report");
			$totalrow = $smcFunc['db_fetch_assoc']($dbresult4);
			$totalreport = $totalrow['total'];
			$smcFunc['db_free_result']($dbresult4);
		$context['gallery_reported_pics'] = $totalreport;
		
	}

}

function AddCategory()
{
	global $context, $mbname, $txt, $modSettings, $sourcedir;

	isAllowedTo('smfgallery_manage');

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_addcategory'];

	$context['sub_template']  = 'add_category';

	// Check if spellchecking is both enabled and actually working.
	$context['show_spellchecking'] = !empty($modSettings['enableSpellChecking']) && function_exists('pspell_new');

	// Needed for the WYSIWYG editor.
	require_once($sourcedir . '/Subs-Editor.php');
	
	// Now create the editor.
	$editorOptions = array(
			'id' => 'descript',
			'value' => '',
			'width' => '90%',
			'form' => 'catform',
			'labels' => array(
				'post_button' => ''
			),
		);
		
		
	create_control_richedit($editorOptions);
	$context['post_box_name'] = $editorOptions['id'];
	
}

function AddCategory2()
{
	global $txt, $smcFunc, $sourcedir;

	isAllowedTo('smfgallery_manage');
	
	// If we came from WYSIWYG then turn it back into BBC regardless.
	if (!empty($_REQUEST['descript_mode']) && isset($_REQUEST['descript']))
	{
		require_once($sourcedir . '/Subs-Editor.php');

		$_REQUEST['descript'] = html_to_bbc($_REQUEST['descript']);

		// We need to unhtml it now as it gets done shortly.
		$_REQUEST['descript'] = un_htmlspecialchars($_REQUEST['descript']);


	}

	$title = htmlspecialchars($_REQUEST['title'], ENT_QUOTES);
	$description = htmlspecialchars($_REQUEST['descript'], ENT_QUOTES);
	$image =  htmlspecialchars($_REQUEST['image'], ENT_QUOTES);

	if (trim($title) == '')
		fatal_error($txt['gallery_error_cat_title'],false);

	// Do the order
	$dbresult = $smcFunc['db_query']('', "
	SELECT
		roworder
	FROM {db_prefix}gallery_cat ORDER BY roworder DESC");
	$row = $smcFunc['db_fetch_assoc']($dbresult);

	$order = $row['roworder'];
	$order++;

	// Insert the category
	$smcFunc['db_query']('', "INSERT INTO {db_prefix}gallery_cat
			(title, description,roworder,image)
		VALUES ('$title', '$description',$order,'$image')");
	$smcFunc['db_free_result']($dbresult);


	 redirectexit('action=admin;area=gallery;sa=admincat');
}

function ViewC()
{
	die(base64_decode('UG93ZXJlZCBieSBHYWxsZXJ5IEZvciBTTUYgIG1hZGUgYnkgdmJnYW1lcjQ1IGh0dHA6Ly93d3cuc21maGFja3MuY29t'));
}

function EditCategory()
{
	global $context, $mbname, $txt, $modSettings,  $smcFunc, $sourcedir;

	@$cat = (int) $_REQUEST['cat'];

	if (empty($cat))
		fatal_error($txt['gallery_error_no_cat']);

	isAllowedTo('smfgallery_manage');

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_editcategory'];

	$context['sub_template']  = 'edit_category';

	// Check if spellchecking is both enabled and actually working.
	$context['show_spellchecking'] = !empty($modSettings['enableSpellChecking']) && function_exists('pspell_new');


	$dbresult = $smcFunc['db_query']('', "
	SELECT
		id_cat, title, image, description
	FROM {db_prefix}gallery_cat
	WHERE ID_CAT = $cat LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$context['gallery_cat_edit'] = $row;
	$smcFunc['db_free_result']($dbresult);
	
	// Needed for the WYSIWYG editor.
	require_once($sourcedir . '/Subs-Editor.php');
	
	// Now create the editor.
	$editorOptions = array(
			'id' => 'descript',
			'value' => $context['gallery_cat_edit']['description'],
			'width' => '90%',
			'form' => 'catform',
			'labels' => array(
				'post_button' => ''
			),
		);
		
		
		create_control_richedit($editorOptions);
		$context['post_box_name'] = $editorOptions['id'];

}

function EditCategory2()
{
	global $txt, $smcFunc, $sourcedir;

	isAllowedTo('smfgallery_manage');
	
	// If we came from WYSIWYG then turn it back into BBC regardless.
	if (!empty($_REQUEST['descript_mode']) && isset($_REQUEST['descript']))
	{
		require_once($sourcedir . '/Subs-Editor.php');

		$_REQUEST['descript'] = html_to_bbc($_REQUEST['descript']);

		// We need to unhtml it now as it gets done shortly.
		$_REQUEST['descript'] = un_htmlspecialchars($_REQUEST['descript']);


	}
	

	// Clean the input
	$title = htmlspecialchars($_REQUEST['title'], ENT_QUOTES);
	$description = htmlspecialchars($_REQUEST['descript'], ENT_QUOTES);
	$catid = (int) $_REQUEST['catid'];
	$image = htmlspecialchars($_REQUEST['image'], ENT_QUOTES);

	if (trim($title) == '')
		fatal_error($txt['gallery_error_cat_title'],false);

	// Update the category
	$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_cat
		SET title = '$title', image = '$image', description = '$description' WHERE id_cat = $catid LIMIT 1");


	redirectexit('action=admin;area=gallery;sa=admincat');

}

function DeleteCategory()
{
	global $context, $mbname, $txt;

	@$catid = (int) $_REQUEST['cat'];

	if (empty($catid))
		fatal_error($txt['gallery_error_no_cat']);

	$context['gallery_catid'] = $catid;

	isAllowedTo('smfgallery_manage');

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_delcategory'];

	$context['sub_template']  = 'delete_category';
}

function DeleteCategory2()
{
	global $modSettings, $smcFunc;

	isAllowedTo('smfgallery_manage');

	$catid = (int) $_REQUEST['catid'];

	$dbresult = $smcFunc['db_query']('', "
	SELECT
		id_picture, thumbfilename, filename
	FROM {db_prefix}gallery_pic
	WHERE id_cat = $catid");

	while($row = $smcFunc['db_fetch_assoc']($dbresult))
	{
		// Delete Files

		// Delete Large image
		@unlink($modSettings['gallery_path'] . $row['filename']);
		// Delete Thumbnail
		@unlink($modSettings['gallery_path'] . $row['thumbfilename']);

		$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_comment WHERE id_picture = " . $row['id_picture']);

		$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_report WHERE id_picture = " . $row['id_picture']);

	}
	// Delete All Pictures
	$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_pic WHERE id_cat = $catid");

	// Finally delete the category
	$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_cat WHERE id_cat = $catid LIMIT 1");


	redirectexit('action=admin;area=gallery;sa=admincat');
}

function ViewPicture()
{
	global $context, $mbname, $smcFunc, $modSettings, $user_info, $scripturl, $txt, $id_member;

	isAllowedTo('smfgallery_view');

	// Get the picture ID
	if (isset($_REQUEST['pic']))
		$id = (int) $_REQUEST['pic'];

	if (isset($_REQUEST['id']))
		$id = (int) $_REQUEST['id'];
	
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected'], false);


	GalleryUserTabs();

	// Get the picture information
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.id_picture, p.width, p.height, p.allowcomments, p.id_cat, p.keywords, p.commenttotal, p.filesize, p.filename, p.approved,
    	p.views, p.title, p.id_member, m.member_name, m.real_name, p.date, p.description, c.title CATNAME
    FROM {db_prefix}gallery_pic as p
    LEFT JOIN {db_prefix}gallery_cat AS c ON (c.id_cat= p.id_cat)
    LEFT JOIN {db_prefix}members AS m ON (p.id_member = m.id_member)
    WHERE p.id_picture= $id   LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);

	// Checked if they are allowed to view an unapproved picture.
	if ($row['approved'] == 0 && $id_member != $row['id_member'])
	{
		if (!AllowedTo('smfgallery_manage'))
			fatal_error($txt['gallery_error_pic_notapproved'],false);
	}

	$context['linktree'][] = array(
					'url' => $scripturl . '?action=gallery;cat=' . $row['id_cat'],
					'name' => $row['CATNAME'],
				);

	// Gallery picture information
	$context['gallery_pic'] = array(
		'id_picture' => $row['id_picture'],
		'id_member' => $row['id_member'],
		'commenttotal' => $row['commenttotal'],
		'views' => $row['views'],
		'title' => $row['title'],
		'description' => $row['description'],
		'filesize' => $row['filesize'],
		'filename' => $row['filename'],
		'width' => $row['width'],
		'height' => $row['height'],
		'allowcomments' => $row['allowcomments'],
		'id_cat' => $row['id_cat'],
		'date' => timeformat($row['date']),
		'keywords' => $row['keywords'],
		'member_name' => $row['member_name'],
		'real_name' => $row['real_name'],
	);
	$smcFunc['db_free_result']($dbresult);


	// Update the number of views.
	 $dbresult = $smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic
		SET views = views + 1 WHERE id_picture= $id LIMIT 1");


	$context['sub_template']  = 'view_picture';

	$context['page_title'] = $mbname . ' - ' . $context['gallery_pic']['title'];
	
$dbresult = $smcFunc['db_query']('', "
		SELECT 
			c.id_picture,  c.id_comment, c.date, c.comment, c.id_member, m.posts, m.member_name,m.real_name 
			FROM {db_prefix}gallery_comment as c, {db_prefix}members AS m 
		WHERE   c.id_picture = " . $context['gallery_pic']['id_picture'] . " AND c.id_member = m.id_member ORDER BY c.id_comment DESC");
		$context['gallery_comment_count'] = $smcFunc['db_affected_rows']();
		$context['gallery_comment_list'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_comment_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
	

	if (!empty($modSettings['gallery_who_viewing']))
	{
		$context['can_moderate_forum'] = allowedTo('moderate_forum');

				//SMF 1.1
				//Taken from Display.php
				// Start out with no one at all viewing it.
				$context['view_members'] = array();
				$context['view_members_list'] = array();
				$context['view_num_hidden'] = 0;
				$whoID = (string) $id;

				// Search for members who have this picture id set in their GET data.
				$request = $smcFunc['db_query']('', "
					SELECT
						lo.id_member, lo.log_time, mem.real_name, mem.member_name, mem.show_online,
						mg.online_color, mg.id_group, mg.group_name
					FROM {db_prefix}log_online AS lo
						LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = lo.id_member)
						LEFT JOIN {db_prefix}membergroups AS mg ON (mg.id_group = IF(mem.id_group = 0, mem.id_post_group, mem.id_group))
					WHERE INSTR(lo.url, 's:7:\"gallery\";s:2:\"sa\";s:4:\"view\";s:2:\"id\";s:" . strlen($whoID ) .":\"$id\";') OR lo.session = '" . ($user_info['is_guest'] ? 'ip' . $user_info['ip'] : session_id()) . "'");
				while ($row = $smcFunc['db_fetch_assoc']($request))
				{
					if (empty($row['id_member']))
						continue;

					if (!empty($row['online_color']))
						$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '" style="color: ' . $row['online_color'] . ';">' . $row['real_name'] . '</a>';
					else
						$link = '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>';

					$is_buddy = in_array($row['id_member'], $user_info['buddies']);
					if ($is_buddy)
						$link = '<b>' . $link . '</b>';

					// Add them both to the list and to the more detailed list.
					if (!empty($row['show_online']) || allowedTo('moderate_forum'))
						$context['view_members_list'][$row['log_time'] . $row['member_name']] = empty($row['show_online']) ? '<i>' . $link . '</i>' : $link;
					$context['view_members'][$row['log_time'] . $row['member_name']] = array(
						'id' => $row['id_member'],
						'username' => $row['member_name'],
						'name' => $row['real_name'],
						'group' => $row['id_group'],
						'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
						'link' => $link,
						'is_buddy' => $is_buddy,
						'hidden' => empty($row['show_online']),
					);

					if (empty($row['show_online']))
						$context['view_num_hidden']++;
				}

				// The number of guests is equal to the rows minus the ones we actually used ;).
				$context['view_num_guests'] = $smcFunc['db_num_rows']($request) - count($context['view_members']);
				$smcFunc['db_free_result']($request);

				// Sort the list.
				krsort($context['view_members']);
				krsort($context['view_members_list']);

	}
}

function AddPicture()
{
	global $context, $mbname, $txt, $modSettings, $sourcedir, $smcFunc;
	
	GalleryUserTabs();

	isAllowedTo('smfgallery_add');

	$context['sub_template']  = 'add_picture';

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_addpicture'];

	// Check if spellchecking is both enabled and actually working.
	$context['show_spellchecking'] = !empty($modSettings['enableSpellChecking']) && function_exists('pspell_new');

	
	// Needed for the WYSIWYG editor.
	require_once($sourcedir . '/Subs-Editor.php');
	
		// Now create the editor.
		$editorOptions = array(
			'id' => 'descript',
			'value' => '',
			'width' => '90%',
			'form' => 'picform',
			'labels' => array(
				'post_button' => ''
			),
		);
		
		
		create_control_richedit($editorOptions);
		$context['post_box_name'] = $editorOptions['id'];
		
	$dbresult = $smcFunc['db_query']('', "
 	SELECT 
 		id_cat, title 
 	FROM {db_prefix}gallery_cat ORDER BY roworder ASC");
	$context['gallery_cat_list'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_cat_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
		
}

function AddPicture2()
{
	global $id_member, $txt,  $modSettings,  $sourcedir, $gd2 , $smcFunc;

	isAllowedTo('smfgallery_add');

	// Check if gallery path is writable
	if (!is_writable($modSettings['gallery_path']))
		fatal_error($txt['gallery_write_error'] . $modSettings['gallery_path']);
		
		
	// If we came from WYSIWYG then turn it back into BBC regardless.
	if (!empty($_REQUEST['descript_mode']) && isset($_REQUEST['descript']))
	{
		require_once($sourcedir . '/Subs-Editor.php');

		$_REQUEST['descript'] = html_to_bbc($_REQUEST['descript']);

		// We need to unhtml it now as it gets done shortly.
		$_REQUEST['descript'] = un_htmlspecialchars($_REQUEST['descript']);


	}


	$title = htmlspecialchars($_REQUEST['title'],ENT_QUOTES);
	$description = htmlspecialchars($_REQUEST['descript'],ENT_QUOTES);
	$keywords = htmlspecialchars($_REQUEST['keywords'],ENT_QUOTES);
	$cat = (int) $_REQUEST['cat'];
	
	@$allowcomments = $_REQUEST['allowcomments'];

	// Check if pictures are auto approved
	$approved = (allowedTo('smfgallery_autoapprove') ? 1 : 0);

	// Allow comments on picture if no setting set.
	if(empty($modSettings['gallery_commentchoice']) || $modSettings['gallery_commentchoice'] == 0)
		$allowcomments = 1;
	else
	{
		if(empty($allowcomments))
			$allowcomments = 0;
		else
			$allowcomments = 1;
	}

	if (trim($title) == '')
		fatal_error($txt['gallery_error_no_title'],false);
	if (empty($cat))
		fatal_error($txt['gallery_error_no_cat'],false);
	
	CheckGalleryCategoryExists($cat);

	$testGD = get_extension_funcs('gd');
	$gd2 = in_array('imagecreatetruecolor', $testGD) && function_exists('imagecreatetruecolor');
	unset($testGD);

	//Process Uploaded file
	if (isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '')
	{


		$sizes = getimagesize($_FILES['picture']['tmp_name']);
		$failed = false;
		if ($sizes === false)
		{
			@unlink($modSettings['gallery_path'] . '/img.tmp');
			move_uploaded_file($_FILES['picture']['tmp_name'], $modSettings['gallery_path'] . '/img.tmp');

			$_FILES['picture']['tmp_name'] = $modSettings['gallery_path'] . '/img.tmp';
			$sizes = getimagesize($_FILES['picture']['tmp_name']);
			$failed =true;
		}

			// No size, then it's probably not a valid pic.
			if ($sizes === false)
				fatal_error($txt['gallery_error_invalid_picture'],false);
			elseif ((!empty($modSettings['gallery_max_width']) && $sizes[0] > $modSettings['gallery_max_width']) || (!empty($modSettings['gallery_max_height']) && $sizes[1] > $modSettings['gallery_max_height']))
			{
				//Delete the temp file
				@unlink($_FILES['picture']['tmp_name']);
				fatal_error($txt['gallery_error_img_size_height'] . $sizes[1] . $txt['gallery_error_img_size_width'] . $sizes[0],false);
			}
			else
			{
				//Get the filesize
				$filesize = $_FILES['picture']['size'];

				if(!empty($modSettings['gallery_max_filesize']) && $filesize > $modSettings['gallery_max_filesize'])
				{
					//Delete the temp file
					@unlink($_FILES['picture']['tmp_name']);
					fatal_error($txt['gallery_error_img_filesize'] . gallery_format_size($modSettings['gallery_max_filesize'], 2) ,false);
				}

				// Filename Member Id + Day + Month + Year + 24 hour, Minute Seconds
				//$extension = substr(strrchr($_FILES['picture']['name'], '.'), 1);
				$extensions = array(
					1 => 'gif',
					2 => 'jpeg',
					3 => 'png',
					5 => 'psd',
					6 => 'bmp',
					7 => 'tiff',
					8 => 'tiff',
					9 => 'jpeg',
					14 => 'iff',
					);
				$extension = isset($extensions[$sizes[2]]) ? $extensions[$sizes[2]] : '.bmp';
			
				
				$filename = $id_member . '_' . date('d_m_y_g_i_s') . '.' . $extension;

				if ($failed == false)
					move_uploaded_file($_FILES['picture']['tmp_name'], $modSettings['gallery_path'] . $filename);
				else
					rename($_FILES['picture']['tmp_name'], $modSettings['gallery_path'] . $filename);

				@chmod($modSettings['gallery_path'] . $filename, 0644);
				// Create thumbnail
				require_once($sourcedir . '/Subs-Graphics.php');

				createThumbnail($modSettings['gallery_path'] . $filename, $modSettings['gallery_thumb_width'],$modSettings['gallery_thumb_height']);
				rename($modSettings['gallery_path'] . $filename . '_thumb',  $modSettings['gallery_path'] . 'thumb_' . $filename);
				$thumbname = 'thumb_' . $filename;



				@chmod($modSettings['gallery_path'] . $thumbname, 0644);
				// Create the Database entry
				$t = time();
				$smcFunc['db_query']('', "INSERT INTO {db_prefix}gallery_pic
							(id_cat, filesize,thumbfilename,filename, height, width, keywords, title, description,id_member,date,approved,allowcomments)
						VALUES ($cat, $filesize,'$thumbname', '$filename', $sizes[1], $sizes[0], '$keywords','$title', '$description',$id_member,$t,$approved, $allowcomments)");

			// Update the SMF Shop Points
			if (isset($modSettings['shopVersion']))
 				$smcFunc['db_query']('', "UPDATE {db_prefix}members
				 	SET money = money + " . $modSettings['gallery_shop_picadd'] . "
				 	WHERE id_member = {$id_member}
				 	LIMIT 1");

				// Redirect to the users image page.
				if ($id_member != 0)
					redirectexit('action=gallery;sa=myimages;u=' . $id_member);
				else
					redirectexit('action=gallery;cat=' . $cat);
			}

	}
	else
		fatal_error($txt['gallery_error_no_picture']);

}

function EditPicture()
{
	global $context, $mbname, $txt, $id_member, $modSettings, $smcFunc, $sourcedir;

	GalleryUserTabs();
	
	is_not_guest();

	$id = (int) $_REQUEST['pic'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	//Check if the user owns the picture or is admin
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.id_picture, p.thumbfilename, p.width, p.height, p.allowcomments, p.id_cat, p.keywords,
    p.commenttotal, p.filesize, p.filename, p.approved, p.views, p.title, p.id_member, m.member_name, m.real_name, p.date, p.description
    FROM {db_prefix}gallery_pic as p
    LEFT JOIN {db_prefix}members AS m ON (m.id_member = p.id_member)
    WHERE id_picture= $id LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);

	// Gallery picture information
	$context['gallery_pic'] = array(
		'id_picture' => $row['id_picture'],
		'id_member' => $row['id_member'],
		'commenttotal' => $row['commenttotal'],
		'views' => $row['views'],
		'title' => $row['title'],
		'description' => $row['description'],
		'filesize' => $row['filesize'],
		'filename' => $row['filename'],
		'thumbfilename' => $row['thumbfilename'],
		'width' => $row['width'],
		'height' => $row['height'],
		'allowcomments' => $row['allowcomments'],
		'id_cat' => $row['id_cat'],
		'date' => timeformat($row['date']),
		'keywords' => $row['keywords'],
		'member_name' => $row['member_name'],
		'real_name' => $row['real_name'],
	);
	$smcFunc['db_free_result']($dbresult);
	
	
		// Needed for the WYSIWYG editor.
		require_once($sourcedir . '/Subs-Editor.php');
	
		// Now create the editor.
		$editorOptions = array(
			'id' => 'descript',
			'value' => $row['description'],
			'width' => '90%',
			'form' => 'picform',
			'labels' => array(
				'post_button' => ''
			),
		);
		
		
		create_control_richedit($editorOptions);
		$context['post_box_name'] = $editorOptions['id'];
		
$dbresult = $smcFunc['db_query']('', "
 	SELECT 
 		id_cat, title 
 	FROM {db_prefix}gallery_cat ORDER BY roworder ASC");
	$context['gallery_cat_list'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_cat_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);

	if (AllowedTo('smfgallery_manage') || (AllowedTo('smfgallery_edit') && $id_member == $context['gallery_pic']['id_member']))
	{
		$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_editpicture'];
		$context['sub_template']  = 'edit_picture';

		// Check if spellchecking is both enabled and actually working.
		$context['show_spellchecking'] = !empty($modSettings['enableSpellChecking']) && function_exists('pspell_new');
	}
	else
		fatal_error($txt['gallery_error_noedit_permission']);


}

function EditPicture2()
{
	global $id_member, $txt, $modSettings, $sourcedir, $gd2, $smcFunc;

	is_not_guest();

	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);
		
		
	// If we came from WYSIWYG then turn it back into BBC regardless.
	if (!empty($_REQUEST['descript_mode']) && isset($_REQUEST['descript']))
	{
		require_once($sourcedir . '/Subs-Editor.php');

		$_REQUEST['descript'] = html_to_bbc($_REQUEST['descript']);

		// We need to unhtml it now as it gets done shortly.
		$_REQUEST['descript'] = un_htmlspecialchars($_REQUEST['descript']);


	}

	// Check the user permissions
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	id_member,thumbfilename,filename
    FROM {db_prefix}gallery_pic
    WHERE id_picture= $id LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$memID = $row['id_member'];
	$oldfilename = $row['filename'];
	$oldthumbfilename  = $row['thumbfilename'];

	$smcFunc['db_free_result']($dbresult);
	if (AllowedTo('smfgallery_manage') || (AllowedTo('smfgallery_edit') && $id_member == $memID))
	{

		if(!is_writable($modSettings['gallery_path']))
			fatal_error($txt['gallery_write_error'] . $modSettings['gallery_path']);


		$title = htmlspecialchars($_REQUEST['title'],ENT_QUOTES);
		$description = htmlspecialchars($_REQUEST['descript'],ENT_QUOTES);
		$keywords = htmlspecialchars($_REQUEST['keywords'],ENT_QUOTES);
		$cat = (int) $_REQUEST['cat'];
		
		@$allowcomments = $_REQUEST['allowcomments'];

		//Check if pictures are auto approved
		$approved = (allowedTo('smfgallery_autoapprove') ? 1 : 0);

		//Allow comments on picture if no setting set.
		if (empty($modSettings['gallery_commentchoice']) || $modSettings['gallery_commentchoice'] == 0)
			$allowcomments = 1;
		else
		{
			if(empty($allowcomments))
				$allowcomments = 0;
			else
				$allowcomments = 1;
		}



		if (trim($title) == '')
			fatal_error($txt['gallery_error_no_title'],false);
		if (empty($cat))
			fatal_error($txt['gallery_error_no_cat'],false);
			
		CheckGalleryCategoryExists($cat);


	$testGD = get_extension_funcs('gd');
	$gd2 = in_array('imagecreatetruecolor', $testGD) && function_exists('imagecreatetruecolor');
	unset($testGD);

		//Process Uploaded file
		if (isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '')
		{

				$sizes = getimagesize($_FILES['picture']['tmp_name']);
				$failed = false;
				if ($sizes === false)
				{
					@unlink($modSettings['gallery_path'] . '/img.tmp');
					move_uploaded_file($_FILES['picture']['tmp_name'], $modSettings['gallery_path'] . '/img.tmp');

					$_FILES['picture']['tmp_name'] = $modSettings['gallery_path'] . '/img.tmp';
					$sizes = getimagesize($_FILES['picture']['tmp_name']);
					$failed =true;
				}

				// No size, then it's probably not a valid pic.
				if ($sizes === false)
					fatal_error($txt['gallery_error_invalid_picture'],false);
				elseif ((!empty($modSettings['gallery_max_width']) && $sizes[0] > $modSettings['gallery_max_width']) || (!empty($modSettings['gallery_max_height']) && $sizes[1] > $modSettings['gallery_max_height']))
				{
					fatal_error($txt['gallery_error_img_size_height'] . $sizes[1] . $txt['gallery_error_img_size_width']. $sizes[0],false);
				}
				else
				{

					//Get the filesize
					$filesize = $_FILES['picture']['size'];
					if(!empty($modSettings['gallery_max_filesize']) && $filesize > $modSettings['gallery_max_filesize'])
					{
						//Delete the temp file
						@unlink($_FILES['picture']['tmp_name']);
						fatal_error($txt['gallery_error_img_filesize'] . gallery_format_size($modSettings['gallery_max_filesize'], 2),false);
					}
					//Delete the old files
					@unlink($modSettings['gallery_path'] . $oldfilename );
					@unlink($modSettings['gallery_path'] . $oldthumbfilename);

					//Filename Member Id + Day + Month + Year + 24 hour, Minute Seconds
					//$extension = substr(strrchr($_FILES['picture']['name'], '.'), 1);
					$extensions = array(
						1 => 'gif',
						2 => 'jpeg',
						3 => 'png',
						5 => 'psd',
						6 => 'bmp',
						7 => 'tiff',
						8 => 'tiff',
						9 => 'jpeg',
						14 => 'iff',
						);
					$extension = isset($extensions[$sizes[2]]) ? $extensions[$sizes[2]] : '.bmp';
			
					
					$filename = $id_member . '_' . date('d_m_y_g_i_s') . '.' . $extension;

					if ($failed == false)
						move_uploaded_file($_FILES['picture']['tmp_name'], $modSettings['gallery_path'] . $filename);
					else
						rename($_FILES['picture']['tmp_name'], $modSettings['gallery_path'] . $filename);

					@chmod($modSettings['gallery_path'] . $filename, 0644);
					//Create thumbnail
					require_once($sourcedir . '/Subs-Graphics.php');

					createThumbnail($modSettings['gallery_path'] . $filename, $modSettings['gallery_thumb_width'], $modSettings['gallery_thumb_height']);
					rename($modSettings['gallery_path'] . $filename . '_thumb',  $modSettings['gallery_path'] . 'thumb_' . $filename);
					$thumbname = 'thumb_' . $filename;


					@chmod($modSettings['gallery_path'] . $thumbname, 0644);

					//Update the Database entry
					$t = time();

					$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic
					SET id_cat = $cat, filesize = $filesize, filename = '$filename',  thumbfilename = '$thumbname', height = $sizes[1], width = $sizes[0], approved = $approved, date =  $t, title = '$title', description = '$description', keywords = '$keywords', allowcomments = $allowcomments WHERE id_picture= $id LIMIT 1");


					//Redirect to the users image page.
					redirectexit('action=gallery;sa=myimages;u=' . $id_member);
				}

		}
		else
		{
			//Update the image properties if no upload has been set
			$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic
				SET id_cat = $cat, title = '$title', description = '$description', keywords = '$keywords', allowcomments = $allowcomments WHERE id_picture= $id LIMIT 1");

			//Redirect to the users image page.
			redirectexit('action=gallery;sa=myimages;u=' . $id_member);

		}

	}
	else
		fatal_error($txt['gallery_error_noedit_permission']);


}

function DeletePicture()
{
	global $context, $mbname, $txt, $id_member, $smcFunc;

	GalleryUserTabs();
	
	is_not_guest();

	$id = (int) $_REQUEST['pic'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	//Check if the user owns the picture or is admin
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.id_picture, p.thumbfilename, p.width, p.height, p.allowcomments, p.id_cat, p.keywords, p.commenttotal, p.filesize, p.filename, p.approved, p.views, p.title, p.id_member, m.member_name, m.real_name, p.date, p.description
    FROM {db_prefix}gallery_pic as p
    LEFT JOIN {db_prefix}members AS m ON (m.id_member = p.id_member)
    WHERE id_picture= $id  LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);

	//Gallery picture information
	$context['gallery_pic'] = array(
		'id_picture' => $row['id_picture'],
		'id_member' => $row['id_member'],
		'commenttotal' => $row['commenttotal'],
		'views' => $row['views'],
		'title' => $row['title'],
		'description' => $row['description'],
		'filesize' => $row['filesize'],
		'filename' => $row['filename'],
		'thumbfilename' => $row['thumbfilename'],
		'width' => $row['width'],
		'height' => $row['height'],
		'allowcomments' => $row['allowcomments'],
		'id_cat' => $row['id_cat'],
		'date' => timeformat($row['date']),
		'keywords' => $row['keywords'],
		'member_name' => $row['member_name'],
		'real_name' => $row['real_name'],
	);
	$smcFunc['db_free_result']($dbresult);

	if (AllowedTo('smfgallery_manage') || (AllowedTo('smfgallery_delete') && $id_member == $context['gallery_pic']['id_member']))
	{
		$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_delpicture'];
		$context['sub_template']  = 'delete_picture';

	}
	else
		fatal_error($txt['gallery_error_nodelete_permission']);



}

function DeletePicture2()
{
	global$txt, $id_member,  $modSettings, $smcFunc;

	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	//Check if the user owns the picture or is admin
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.id_picture, p.filename, p.thumbfilename,  p.id_member
    FROM {db_prefix}gallery_pic as p
    WHERE id_picture= $id LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$memID = $row['id_member'];
	$smcFunc['db_free_result']($dbresult);

	if(AllowedTo('smfgallery_manage') || (AllowedTo('smfgallery_delete') && $id_member == $memID))
	{

		//Delete Large image
		@unlink($modSettings['gallery_path'] . $row['filename']);
		//Delete Thumbnail
		@unlink($modSettings['gallery_path'] . $row['thumbfilename']);

		//Delete all the picture related db entries

		$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_comment WHERE id_picture = $id LIMIT 1");

		$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_report WHERE id_picture = $id LIMIT 1");

		//Delete the picture
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_pic WHERE id_picture= $id LIMIT 1");

		// Update the SMF Shop Points
			if (isset($modSettings['shopVersion']))
 				$smcFunc['db_query']('', "UPDATE {db_prefix}members
				 	SET money = money - " . $modSettings['gallery_shop_picadd'] . "
				 	WHERE id_member = {$memID}
				 	LIMIT 1");

		// Redirect to the users image page.
		redirectexit('action=gallery;sa=myimages;u=' . $id_member);


	}
	else
		fatal_error($txt['gallery_error_nodelete_permission']);

}
function ReportPicture()
{
	global $context, $mbname, $txt;

	isAllowedTo('smfgallery_report');

	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	$context['gallery_pic_id'] = $id;

	$context['sub_template']  = 'report_picture';

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_reportpicture'];

}

function ReportPicture2()
{
	global $id_member, $txt, $smcFunc;

	isAllowedTo('smfgallery_report');

	$comment = htmlspecialchars($_REQUEST['comment'],ENT_QUOTES);
	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	if (trim($comment) == '')
		fatal_error($txt['gallery_error_no_comment'],false);

	$commentdate = time();

	$smcFunc['db_query']('', "INSERT INTO {db_prefix}gallery_report
			(id_member, comment, date, id_picture)
		VALUES ($id_member,'$comment', $commentdate,$id)");

	redirectexit('action=gallery;sa=view;pic=' . $id);

}

function AddComment()
{
	global $context, $mbname, $txt, $modSettings, $smcFunc, $sourcedir;
	
	GalleryUserTabs();

	is_not_guest();

	isAllowedTo('smfgallery_comment');
	loadlanguage('Post');

	$id = (int) $_REQUEST['id'];
	if (empty($id) )
		fatal_error($txt['gallery_error_no_pic_selected']);

	$context['gallery_pic_id'] = $id;

	// Comments allowed check
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.allowcomments
    FROM {db_prefix}gallery_pic as p
    WHERE id_picture= $id LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$smcFunc['db_free_result']($dbresult);
	//Checked if comments are allowed
	if ($row['allowcomments'] == 0)
			fatal_error($txt['gallery_error_not_allowcomment']);


	$context['sub_template']  = 'add_comment';

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_addcomment'];

	// Check if spellchecking is both enabled and actually working.
	$context['show_spellchecking'] = !empty($modSettings['enableSpellChecking']) && function_exists('pspell_new');

	$modSettings['disable_wysiwyg'] = !empty($modSettings['disable_wysiwyg']) || empty($modSettings['enableBBC']);


	// Needed for the WYSIWYG editor.
	require_once($sourcedir . '/Subs-Editor.php');

	// Now create the editor.
	$editorOptions = array(
		'id' => 'message',
		'value' => '',
		'width' => '90%',
		'form' => 'cprofile',
		'labels' => array(
			'post_button' => $txt['gallery_text_addcomment']
		),
	);
	create_control_richedit($editorOptions);
	$context['post_box_name'] = $editorOptions['id'];

}

function AddComment2()
{
	global $id_member, $txt, $modSettings, $smcFunc, $sourcedir;

	isAllowedTo('smfgallery_comment');
	
	// If we came from WYSIWYG then turn it back into BBC regardless.
	if (!empty($_REQUEST['message_mode']) && isset($_REQUEST['message']))
	{
		require_once($sourcedir . '/Subs-Editor.php');

		$_REQUEST['message'] = html_to_bbc($_REQUEST['message']);

		// We need to unhtml it now as it gets done shortly.
		$_REQUEST['message'] = un_htmlspecialchars($_REQUEST['message']);


	}
	

	$comment = htmlspecialchars($_REQUEST['message'],ENT_QUOTES);
	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	//Check if that picture allows comments.
    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.allowcomments
    FROM {db_prefix}gallery_pic as p
    WHERE id_picture= $id LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$smcFunc['db_free_result']($dbresult);
	//Checked if comments are allowed
	if ($row['allowcomments'] == 0)
		fatal_error($txt['gallery_error_not_allowcomment']);

	if (trim($comment) == '')
		fatal_error($txt['gallery_error_no_comment'],false);

	$commentdate = time();

	$smcFunc['db_query']('', "INSERT INTO {db_prefix}gallery_comment
			(id_member, comment, date, id_picture)
		VALUES ($id_member,'$comment', $commentdate,$id)");


	// Update the SMF Shop Points
	if (isset($modSettings['shopVersion']))
 				$smcFunc['db_query']('', "UPDATE {db_prefix}members
				 	SET money = money + " . $modSettings['gallery_shop_commentadd'] . "
				 	WHERE id_member = {$id_member}
				 	LIMIT 1");

	//Update Comment total
	 $smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic
		SET commenttotal = commenttotal + 1 WHERE id_picture= $id LIMIT 1");


	redirectexit('action=gallery;sa=view;pic=' . $id);

}

function DeleteComment()
{
	global $txt, $modSettings, $smcFunc;

	is_not_guest();

	isAllowedTo('smfgallery_manage');

	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_com_selected']);


	//Get the picture ID for redirect
	$dbresult = $smcFunc['db_query']('', "
	SELECT
		id_picture,ID_COMMENT, id_member
	FROM {db_prefix}gallery_comment
	WHERE ID_COMMENT = $id LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$picid = $row['id_picture'];
	$memID = $row['id_member'];
	$smcFunc['db_free_result']($dbresult);
	// Now delete the comment.
	$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_comment WHERE ID_COMMENT = $id LIMIT 1");


	//Update Comment total
	  $dbresult = $smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic
		SET commenttotal = commenttotal - 1 WHERE id_picture= $picid LIMIT 1");

			// Update the SMF Shop Points
			if (isset($modSettings['shopVersion']))
 				$smcFunc['db_query']('', "UPDATE {db_prefix}members
				 	SET money = money - " . $modSettings['gallery_shop_commentadd'] . "
				 	WHERE id_member = {$memID}
				 	LIMIT 1");

	// Redirect to the picture
	redirectexit('action=gallery;sa=view;pic=' . $picid);
}

function AdminSettings()
{
	global $context, $mbname, $txt;
	isAllowedTo('smfgallery_manage');

	DoGalleryAdminTabs();

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_settings'];

	$context['sub_template']  = 'settings';

}

function AdminSettings2()
{

	isAllowedTo('smfgallery_manage');

	// Get the settings
	$gallery_max_height = (int) $_REQUEST['gallery_max_height'];
	$gallery_max_width =  (int) $_REQUEST['gallery_max_width'];
	$gallery_max_filesize =  (int) $_REQUEST['gallery_max_filesize'];
	$gallery_commentchoice =  isset($_REQUEST['gallery_commentchoice']) ? 1 : 0;
	$gallery_set_images_per_page = (int) $_REQUEST['gallery_set_images_per_page'];
	$gallery_set_images_per_row = (int) $_REQUEST['gallery_set_images_per_row'];
	$gallery_thumb_width = (int) $_REQUEST['gallery_thumb_width'];
	$gallery_thumb_height = (int) $_REQUEST['gallery_thumb_height'];

	// Shop settings
	$gallery_shop_picadd = (int) $_REQUEST['gallery_shop_picadd'];
	$gallery_shop_commentadd = (int) $_REQUEST['gallery_shop_commentadd'];

	$gallery_path = $_REQUEST['gallery_path'];
	$gallery_url = $_REQUEST['gallery_url'];
	$gallery_who_viewing = isset($_REQUEST['gallery_who_viewing']) ? 1 : 0;

	// Image Linking codes
	$gallery_set_showcode_bbc_image = isset($_REQUEST['gallery_set_showcode_bbc_image']) ? 1 : 0;
	$gallery_set_showcode_directlink = isset($_REQUEST['gallery_set_showcode_directlink']) ? 1 : 0;
	$gallery_set_showcode_htmllink = isset($_REQUEST['gallery_set_showcode_htmllink']) ? 1 : 0;


	updateSettings(
	array(
	'gallery_max_height' => $gallery_max_height,
	'gallery_max_width' => $gallery_max_width,
	'gallery_max_filesize' => $gallery_max_filesize,
	'gallery_path' => $gallery_path,
	'gallery_url' => $gallery_url,
	'gallery_commentchoice' => $gallery_commentchoice,
	'gallery_who_viewing' => $gallery_who_viewing,
	'gallery_set_images_per_page' => $gallery_set_images_per_page,
	'gallery_set_images_per_row' => $gallery_set_images_per_row,
	'gallery_thumb_width' => $gallery_thumb_width,
	'gallery_thumb_height' => $gallery_thumb_height,

	'gallery_shop_commentadd' => $gallery_shop_commentadd,
	'gallery_shop_picadd' => $gallery_shop_picadd,

	'gallery_set_showcode_bbc_image' => $gallery_set_showcode_bbc_image,
	'gallery_set_showcode_directlink' => $gallery_set_showcode_directlink,
	'gallery_set_showcode_htmllink' => $gallery_set_showcode_htmllink,

	));

	redirectexit('action=admin;area=gallery;sa=adminset');

}

function AdminCats()
{
	global $context, $mbname, $txt, $smcFunc;
	isAllowedTo('smfgallery_manage');

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_managecats'];


	DoGalleryAdminTabs();

	$context['sub_template']  = 'manage_cats';
	
	$dbresult = $smcFunc['db_query']('', "
		SELECT 
			id_cat, title, roworder, description, image 
		FROM {db_prefix}gallery_cat ORDER BY roworder ASC");
	$context['gallery_cat_list'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_cat_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
}

function CatUp()
{
	global $txt, $smcFunc;
	// Check if they are allowed to manage cats
	isAllowedTo('smfgallery_manage');

	// Get the cat id
	@$cat = (int) $_REQUEST['cat'];
	ReOrderCats($cat);

	//Check if there is a category above it
	//First get our row order
	$dbresult1 = $smcFunc['db_query']('', "
	SELECT
		roworder
	FROM {db_prefix}gallery_cat
	WHERE id_cat = $cat");
	$row = $smcFunc['db_fetch_assoc']($dbresult1);
	$oldrow = $row['roworder'];
	$o = $row['roworder'];
	$o--;

	$smcFunc['db_free_result']($dbresult1);
	$dbresult = $smcFunc['db_query']('', "
	SELECT
		id_cat, roworder
	FROM {db_prefix}gallery_cat
	WHERE roworder = $o");
	if ($smcFunc['db_affected_rows']()== 0)
		fatal_error($txt['gallery_nocatabove'],false);
	$row2 = $smcFunc['db_fetch_assoc']($dbresult);


	// Swap the order Id's
	$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_cat
		SET roworder = $oldrow WHERE id_cat = " .$row2['id_cat']);

	$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_cat
		SET roworder = $o WHERE id_cat = $cat");


	$smcFunc['db_free_result']($dbresult);

	// Redirect to index to view cats
	redirectexit('action=gallery');
}

function CatDown()
{
	global $txt, $smcFunc;

	// Check if they are allowed to manage cats
	isAllowedTo('smfgallery_manage');

	// Get the cat id
	@$cat = (int) $_REQUEST['cat'];
	ReOrderCats($cat);
	// Check if there is a category below it
	// First get our row order
	$dbresult1 = $smcFunc['db_query']('', "
	SELECT
		roworder
	FROM {db_prefix}gallery_cat
	WHERE id_cat = $cat LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult1);
	$oldrow = $row['roworder'];
	$o = $row['roworder'];
	$o++;

	$smcFunc['db_free_result']($dbresult1);
	$dbresult = $smcFunc['db_query']('', "
	SELECT
		id_cat, roworder
	FROM {db_prefix}gallery_cat
	WHERE roworder = $o");
	if ($smcFunc['db_affected_rows']()== 0)
		fatal_error($txt['gallery_nocatbelow'],false);
	$row2 = $smcFunc['db_fetch_assoc']($dbresult);


	//Swap the order Id's
	$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_cat
		SET roworder = $oldrow WHERE id_cat = " .$row2['id_cat']);

	$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_cat
		SET roworder = $o WHERE id_cat = $cat");


	$smcFunc['db_free_result']($dbresult);


	// Redirect to index to view cats
	redirectexit('action=gallery');
}

function MyImages()
{
	global $context, $mbname, $txt, $id_member, $scripturl, $smcFunc, $modSettings;

	isAllowedTo('smfgallery_view');

	GalleryUserTabs();


	$u = (int) $_REQUEST['u'];
	if (empty($u))
		fatal_error($txt['gallery_error_no_user_selected']);

	// Store the gallery userid
	$context['gallery_userid'] = $u;

    $dbresult = $smcFunc['db_query']('', "
    SELECT
    	m.member_name, m.real_name
    FROM {db_prefix}members AS m
    WHERE m.id_member = $u  LIMIT 1");
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$context['gallery_usergallery_name'] = $row['real_name'];
	$smcFunc['db_free_result']($dbresult);

	$context['start'] = (int) $_REQUEST['start'];
	
	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $context['gallery_usergallery_name'];

	$context['sub_template']  = 'myimages';
	
	$userid = $context['gallery_userid'];
		$dbresult = $smcFunc['db_query']('', "
		SELECT COUNT(*) AS total 
		 FROM {db_prefix}gallery_pic as p, {db_prefix}members AS m 
		WHERE p.id_member = $userid AND p.id_member = m.id_member " . ($id_member == $userid ? '' : ' AND p.approved = 1'));
	$row = $smcFunc['db_fetch_assoc']($dbresult);
	$context['gallery_totalpic'] = $row['total'];
	$smcFunc['db_free_result']($dbresult);
	
    $dbresult = $smcFunc['db_query']('', "
    SELECT 
    	p.id_picture, p.commenttotal, p.filesize, p.thumbfilename, p.approved, p.views, p.id_member, m.real_name, p.date, p.filename, p.height, p.width  
    FROM {db_prefix}gallery_pic as p, {db_prefix}members AS m 
    WHERE p.id_member = $userid AND p.id_member = m.id_member " . ($id_member == $userid ? '' : ' AND p.approved = 1 ')  . " LIMIT $context[start]," . $modSettings['gallery_set_images_per_page']);
	$context['gallery_my_images'] = array();
 	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_my_images'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);   

}

function ApproveList()
{
	global $context, $mbname, $txt, $smcFunc;

	isAllowedTo('smfgallery_manage');

	DoGalleryAdminTabs();

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_approveimages'];


	$context['sub_template']  = 'approvelist';
	
	$dbresult = $smcFunc['db_query']('', "
		  	SELECT 
		  		p.id_picture, p.thumbfilename, p.title, p.id_member, m.member_name, m.real_name, p.date, p.description, 
		  		p.filename, p.height, p.width 
		  	FROM {db_prefix}gallery_pic as p
		  	LEFT JOIN {db_prefix}members AS m  on (p.id_member = m.id_member) 
		  	WHERE p.approved = 0 ORDER BY p.id_picture DESC");
	$context['gallery_approve_list'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_approve_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
	
}

function ApprovePicture()
{
	global $txt, $smcFunc;

	isAllowedTo('smfgallery_manage');

	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	// Update the approval
	$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic SET approved = 1 WHERE id_picture= $id LIMIT 1");

	// Redirect to approval list
	redirectexit('action=admin;area=gallery;sa=approvelist');

}
function UnApprovePicture()
{
	global $txt, $smcFunc;

	isAllowedTo('smfgallery_manage');

	$id = (int) $_REQUEST['pic'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_pic_selected']);

	// Update the approval
	 $smcFunc['db_query']('', "UPDATE {db_prefix}gallery_pic SET approved = 0 WHERE id_picture= $id LIMIT 1");

	// Redirect to approval list
	redirectexit('action=admin;area=gallery;sa=approvelist');
}

function ReportList()
{
	global $context, $mbname, $txt, $smcFunc;

	isAllowedTo('smfgallery_manage');

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_form_reportimages'];


	DoGalleryAdminTabs();

	$context['sub_template']  = 'reportlist';
	
	$dbresult = $smcFunc['db_query']('', "
		  	SELECT 
		  		r.ID, r.id_picture, r.id_member, m.member_name, m.real_name, r.date,r.comment 
		  	FROM {db_prefix}gallery_report as r
		  	LEFT JOIN {db_prefix}members AS m on (r.id_member = m.id_member) ORDER BY r.id_picture DESC");
	$context['gallery_report_list'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_report_list'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
			
}

function DeleteReport()
{
	global $txt, $smcFunc;

	// Check the permission
	isAllowedTo('smfgallery_manage');

	$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['gallery_error_no_report_selected']);

	$smcFunc['db_query']('', "DELETE FROM {db_prefix}gallery_report WHERE ID = $id LIMIT 1");

	// Redirect to redirect list
	redirectexit('action=admin;area=gallery;sa=reportlist');
}

function Search()
{
	global $context, $mbname, $txt;

	//  the user allowed to view the gallery?
	isAllowedTo('smfgallery_view');

	GalleryUserTabs();

	$context['sub_template']  = 'search';

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_search'];
}

function Search2()
{
	global $context, $mbname, $txt, $smcFunc;

	// Is the user allowed to view the gallery?
	isAllowedTo('smfgallery_view');

	GalleryUserTabs();

	//Check if keyword search was selected
	@$keyword =  htmlspecialchars($_REQUEST['key'],ENT_QUOTES);
	if($keyword == '')
	{
		//Probably a normal Search
		$searchfor =  htmlspecialchars($_REQUEST['searchfor'],ENT_QUOTES);
		if($searchfor == '')
			fatal_error($txt['gallery_error_no_search'],false);

		if(strlen($searchfor) <= 3)
			fatal_error($txt['gallery_error_search_small'],false);

		//Check the search options
		@$searchkeywords = $_REQUEST['searchkeywords'];
		@$searchtitle = $_REQUEST['searchtitle'];
		@$searchdescription = $_REQUEST['searchdescription'];

		$s1 = 1;
		$searchquery = '';
		if($searchtitle)
			$searchquery = "p.title LIKE '%$searchfor%' ";
		else
			$s1 = 0;

		$s2 = 1;
		if($searchdescription)
		{
			if($s1 == 1)
				$searchquery = "p.title LIKE '%$searchfor%' OR p.description LIKE '%$searchfor%'";
			else
				$searchquery = "p.description LIKE '%$searchfor%'";
		}
		else
			$s2 = 0;

		if($searchkeywords)
		{
			if($s1 == 1 || $s2 == 1)
				$searchquery .= " OR p.keywords LIKE '%$searchfor%'";
			else
				$searchquery = "p.keywords LIKE '%$searchfor%'";
		}


		if($searchquery == '')
			$searchquery = "p.title LIKE '%$searchfor%' ";

		$context['gallery_search_query'] = $searchquery;



		$context['gallery_search'] = $searchfor;
	}
	else
	{
		//Search for the keyword


		//Debating if I should add string length check for keywords...
		//if(strlen($keyword) <= 3)
			//fatal_error($txt['gallery_error_search_small']);

		$context['gallery_search'] = $keyword;

		$context['gallery_search_query'] = "p.keywords LIKE '%$keyword%'";
	}
	
	$dbresult = $smcFunc['db_query']('', "
    SELECT 
    	p.id_picture, p.commenttotal, p.keywords, p.filesize, p.thumbfilename, p.approved, p.views, p.id_member, m.real_name, p.date, p.width, p.height, p.filename FROM {db_prefix}gallery_pic as p
    LEFT JOIN {db_prefix}members AS m ON (p.id_member = m.id_member) 
    WHERE p.approved = 1 AND (" . $context['gallery_search_query'] . ")");
	$context['gallery_search_results'] = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$context['gallery_search_results'][] = $row;
		}
		$smcFunc['db_free_result']($dbresult);
		
	$context['sub_template']  = 'search_results';

	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_searchresults'];
}

function ReOrderCats($cat)
{
	global $smcFunc;

	$dbresult = $smcFunc['db_query']('', "
	SELECT
		id_cat, roworder
	FROM {db_prefix}gallery_cat ORDER BY roworder ASC");

	if ($smcFunc['db_affected_rows']() != 0)
	{
		$count = 1;
		while($row2 = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$smcFunc['db_query']('', "UPDATE {db_prefix}gallery_cat
			SET roworder = $count WHERE id_cat = " . $row2['id_cat']);
			$count++;
		}
	}
	$smcFunc['db_free_result']($dbresult);
}

function DoGalleryAdminTabs($overrideSelected = '')
{
	global $context, $txt, $smcFunc;


	$dbresult3 = $smcFunc['db_query']('', "
			SELECT
				COUNT(*) AS total
			FROM {db_prefix}gallery_pic
			WHERE approved = 0");
			$totalrow = $smcFunc['db_fetch_assoc']($dbresult3);
			$totalappoval = $totalrow['total'];
			$smcFunc['db_free_result']($dbresult3);

	$dbresult4 = $smcFunc['db_query']('', "
			SELECT
				COUNT(*) AS total
			FROM {db_prefix}gallery_report");
			$totalrow = $smcFunc['db_fetch_assoc']($dbresult4);
	$totalreport = $totalrow['total'];
	$smcFunc['db_free_result']($dbresult4);


	$context[$context['admin_menu_name']]['tab_data'] = array(
			'title' => $txt['smfgallery_admin'],
			'description' => '',
			'tabs' => array(
				'adminset' => array(
					'description' => $txt['gallery_set_description'],
				),
				'admincat' => array(
					'description' => '',
				),
				'approvelist' => array(
					'description' => '',
					'label' => $txt['gallery_form_approveimages'] . ' (' . $totalappoval . ')',
				),
				'reportlist' => array(
					'description' => '',
					'label' => $txt['gallery_form_reportimages'] . ' (' . $totalreport . ')',
				),
			),
		);



}

function GalleryUserTabs()
{
	global $id_member, $context, $scripturl, $txt;

	$g_add = allowedTo('smfgallery_add');

	// MyImages
	if ($g_add && !($context['user']['is_guest']))
	$context['gallery']['buttons']['mylisting'] =  array(
		'text' => 'gallery_myimages',
		'url' =>$scripturl . '?action=gallery;sa=myimages;u=' . $id_member,
		'lang' => true,
		'image' => '',

	);

	// Search
	$context['gallery']['buttons']['search'] =  array(
		'text' => 'gallery_search',
		'url' => $scripturl . '?action=gallery;sa=search',
		'lang' => true,
		'image' => '',

	);

	// Link Tree
	$context['linktree'][] = array(
					'url' => $scripturl . '?action=gallery',
					'name' => $txt['gallery_text_title']
				);
}

function DoToolBarStrip($button_strip, $direction )
{
	global $settings, $txt;

	if (!empty($settings['use_tabs']))
	{
		template_button_strip($button_strip, $direction);
	}
	else
	{
			echo '<td>';

			foreach ($button_strip as $tab)
			{
				/*
				if (!empty($tab['is_selected']))
				{
					echo '
							<img src="', $settings['images_url'], '/selected.gif" alt="*" /> <b><a href="', $tab['url'], '">', $tab['text'], '</a></b>';

					$selected_tab = $tab;
				}
				else
				*/


				echo '
							<a href="', $tab['url'], '">', $txt[$tab['text']], '</a>';

				if (empty($tab['is_last']))
					echo ' | ';
			}

			echo '</td>';

	}

}

function GetTotalPicturesByCATID($ID_CAT)
{
	global $smcFunc;
	$dbresult2 = $smcFunc['db_query']('', "
		  	SELECT 
		  		COUNT(*) AS total
		  	FROM {db_prefix}gallery_pic 
		  	WHERE id_cat = ". $ID_CAT . ' AND approved = 1');
	$rowTotal = $smcFunc['db_fetch_assoc']($dbresult2);
	return $rowTotal['total'];
}

function CheckGalleryCategoryExists($cat)
{
	global $smcFunc, $txt;
	
	$dbresult2 = $smcFunc['db_query']('', "
		  	SELECT 
		  		COUNT(*) AS total
		  	FROM {db_prefix}gallery_cat
		  	WHERE ID_CAT = $cat ");
	$rowTotal =$smcFunc['db_fetch_assoc']($dbresult2);
	$smcFunc['db_free_result']($dbresult2);
	
	if ($rowTotal['total'] == 0)
		fatal_error($txt['gallery_error_category'],false);  	
}

function gallery_format_size($size, $round = 0)
{
    //Size must be bytes!
    $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    for ($i=0; $size > 1024 && $i < count($sizes) - 1; $i++) $size /= 1024;
    return round($size,$round).$sizes[$i];
}

function ShowTopGalleryBar($title = '&nbsp;')
{
	global $txt, $context;
		echo '
	
	 <table class="table_list">
         <tbody class="header">

	<tr  class="catbg">
		<td align="center" colspan="4" class="catbg"><span class="left"></span>', $title, '
		 </td>
	</tr>
	</tbody>
</table>

				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
						<tr>
							<td style="padding-right: 1ex;" align="right" >
						<table cellpadding="0" cellspacing="0" align="right">
									<tr>
									<td align="right">
						', DoToolBarStrip($context['gallery']['buttons'], 'top'), '
						</td>	
						</tr>
							</table>
						</td>
						</tr>
					</table>

<br />';
}

function ReGenerateThumbnails()
{
	global $context, $mbname, $txt, $smcFunc;

	@$cat = (int) $_REQUEST['cat'];
	

	if (empty($cat))
		fatal_error($txt['gallery_error_no_cat']);

	isAllowedTo('smfgallery_manage');
	
	

	// Get the category name

		$dbresult1 = $smcFunc['db_query']('', "
			SELECT
				title
			FROM {db_prefix}gallery_cat
			WHERE id_cat = $cat");
	
		$row = $smcFunc['db_fetch_assoc']($dbresult1);
		$context['gallery_cat_name'] = $row['title'];
		$smcFunc['db_free_result']($dbresult1);
		
		
		$context['catid'] = $cat;
		$context['usercat'] = 0;

	
	
	$context['sub_template']  = 'regenerate';
	$context['page_title'] = $mbname . ' - ' . $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_regeneratethumbnails2'];
}

function ReGenerateThumbnails2()
{
	global $smcFunc, $txt, $modSettings, $gd2, $sourcedir, $context;

	$id = (int) $_REQUEST['id'];

	if (empty($id))
		return;

	isAllowedTo('smfgallery_manage');
	$catWhere = '';
	
		$context['catid'] = $id;
		$context['usercat'] = 0;
		$catWhere = " ID_CAT = $id";

	
	

	// Check if gallery path is writable
	if (!is_writable($modSettings['gallery_path']))
		fatal_error($txt['gallery_write_error'] . $modSettings['gallery_path']);

	// Increase the max time to process the images
	@ini_set('max_execution_time', '900');

	$testGD = get_extension_funcs('gd');
	$gd2 = in_array('imagecreatetruecolor', $testGD) && function_exists('imagecreatetruecolor');
	unset($testGD);

	require_once($sourcedir . '/Subs-Graphics.php');
	

		
	$context['start'] = empty($_REQUEST['start']) ? 25 : (int) $_REQUEST['start'];
	
	$request = $smcFunc['db_query']('', "
	SELECT 
		COUNT(*) 
	FROM {db_prefix}gallery_pic 
	WHERE $catWhere");
	list($totalProcess) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);
	
	// Initialize the variables.
	$increment = 25;
	if (empty($_REQUEST['start']))
		$_REQUEST['start'] = 0;


	$dbresult = $smcFunc['db_query']('', "
		SELECT
			filename, id_picture 
		FROM {db_prefix}gallery_pic
		WHERE $catWhere LIMIT " . $_REQUEST['start'] . ","  . ($increment));
	$counter = 0;
	$gallery_pics = array();
	while ($row = $smcFunc['db_fetch_assoc']($dbresult))
	{
		$gallery_pics[] = $row;
	}
	$smcFunc['db_free_result']($dbresult);
	
	foreach($gallery_pics as $row)
	{
		$filename = $row['filename'];
		$extra_path = '';

		$thumbnailPath = '';

		createThumbnail($modSettings['gallery_path'] . $extra_path .  $filename, $modSettings['gallery_thumb_width'], $modSettings['gallery_thumb_height']);
		unlink($modSettings['gallery_path'] . $extra_path . 'thumb_' . $filename);
		rename($modSettings['gallery_path'] . $extra_path .  $filename . '_thumb',  $modSettings['gallery_path']  . $extra_path . 'thumb_' . $filename);
		@chmod($modSettings['gallery_path'] . $extra_path  .  'thumb_' . $filename, 0755);
		$thumbnailPath = $extra_path  .  'thumb_' . $filename;
		

			$smcFunc['db_query']('', "
			UPDATE {db_prefix}gallery_pic SET thumbfilename = '$thumbnailPath'
			WHERE ID_PICTURE = " . $row['id_picture']);
		
		
		$counter++;
	
	}
	
	$_REQUEST['start'] += $increment;

	$complete = 0;
	if($_REQUEST['start'] < $totalProcess)
	{

		$context['continue_get_data'] = 'start=' . $_REQUEST['start'];
		$context['continue_percent'] = round(100 * $_REQUEST['start'] / $totalProcess);


	}
	else
		$complete = 1;
	
	// Redirect back to the category
	if ($complete == 1)
		redirectexit('action=gallery;cat=' .  $id);
	else 
	{
		$context['sub_template']  = 'regenerate2';

		$context['page_title'] =  $txt['gallery_text_title'] . ' - ' . $txt['gallery_text_regeneratethumbnails2'];

	}
	
}

?>