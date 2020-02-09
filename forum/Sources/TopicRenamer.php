<?php

if (!defined('SMF'))
	die ('Hacking attempt...');

function TopicRenamer(){
	global $txt, $context, $smcFunc;

	loadLanguage('TopicRenamer/');	

	// Check if allowed to renameTopic.
	isAllowedTo('rename_topic_own') || isAllowedTo('rename_topic_any');

	// Load the template.
	loadTemplate('TopicRenamer');

	// Check if a topic id is set.
	if (!isset($_REQUEST['topic']) || is_array($_REQUEST['topic']))
		fatal_lang_error('rename_topic_no_id');

	// Define ID_TOPIC.
	$ID_TOPIC = (int)$_REQUEST['topic'];

	// Select the current subject.
	$result = $smcFunc['db_query']('', '
		SELECT m.subject
		FROM ({db_prefix}messages AS m, {db_prefix}topics AS t)
		WHERE m.id_topic = {int:topic}
			AND t.id_first_msg = m.id_msg
		LIMIT 1',
		array(
			'topic' => $ID_TOPIC,
		)
	);

	// List the result.
	list ($currentSubject) = $smcFunc['db_fetch_row']($result);
	$smcFunc['db_free_result']($result);

	// Stripslashes and htmlspecialchars
	$context['currentSubject'] = strtr(censorText($smcFunc['db_unescape_string']($currentSubject)), array("\r" => '', "\n" => '', "\t" => ''));

	// If renameTopic isset do the renaming.
	if (isset($_POST['renameTopic2']) && !empty($_POST['subject']))
	{
		// Check if they have a valid session.
		checkSession();

		// Clean the new subject.
		$_POST['subject'] = strtr($smcFunc['htmlspecialchars']($_POST['subject']), array("\r" => '', "\n" => '', "\t" => ''));
		
		// At this point, we want to make sure the subject isn't too long (and subject fits in the tinytext of the database - 255).
		if (strlen($_POST['subject']) > 100)
			$_POST['subject'] = $smcFunc['db_escape_string'](substr($smcFunc['db_unescape_string']($_POST['subject']), 0, 100));

		// Do the dew.
		$update = $smcFunc['db_query']('', '
			UPDATE {db_prefix}messages
			SET subject = {string:subject}
			WHERE id_topic = {int:topic}',
			array(
				'subject' => $_POST['subject'],
				'topic' => $ID_TOPIC,
			)
		);

		// Check if it went through.  If so redirect.
		if ($update)
			redirectexit('topic=' . $ID_TOPIC);
		else
			redirectexit('action=renameTopic;topic=' . $ID_TOPIC);
	}

	// Set the title.
	$context['page_title'] = $txt['rename_topic'] . ' "' . $currentSubject . '"';
	
}

function TopicRenamer_permissions(&$permissionGroups, &$permissionList){
	loadLanguage('TopicRenamer/');	

	$permissionList['board'] += array(
		'rename_topic' => array(true, 'topic', 'moderate', 'moderate'),
	);
}
function TopicRenamer_actions(&$actionsArray){
	$actionsArray['renameTopic'] = array('TopicRenamer.php', 'TopicRenamer');
}

function TopicRenamer_mod_buttons(&$mod_buttonsArray){
	global $context, $scripturl;
	
	loadLanguage('TopicRenamer/');	

	$mod_buttonsArray['renameTopic'] = array('test' => 'can_delete', 'text' => 'rename_topic', 'image' => 'edit.gif', 'lang' => true,	'url' => $scripturl . '?action=renameTopic;topic=' . $context['current_topic']. ';' . $context['session_var'] . '=' . $context['session_id']);
}




?>