<?php
/*******************************************************************************
* Smart Pagination © 5771, Bugo											       *
********************************************************************************
* Subs-Pagination.php														   *
********************************************************************************
* License http://creativecommons.org/licenses/by-nc-nd/3.0/deed.ru CC BY-NC-ND *
* Support and updates for this software can be found at	http://dragomano.ru    *
*******************************************************************************/

if (!defined('SMF'))
	die('Hacking attempt...');

function smart_pagination_load_css()
{
	loadTemplate(false, 'pagination');
}

function smart_pagination_messageindex()
{
	global $context, $scripturl, $board_info;
	
	if (!empty($context['current_board']) && !empty($context['page_index'])) {
		if (strpos($context['page_index'], "...") || strpos($context['page_index'], "2")) {
			$context['prev_board_page'] = !empty($context['start']) ? '<a class="navPages" href="' . $scripturl . '?board=' . $context['current_board'] . '.' . ($context['start'] - $context['topics_per_page']) . '">&laquo;</a> ' : '';
			$context['next_board_page'] = $board_info['total_topics'] <= ($context['start'] + $context['topics_per_page']) ? '' : ' <a class="navPages" href="' . $scripturl . '?board=' . $context['current_board'] . '.' . ($context['start'] + $context['topics_per_page']) . '">&raquo;</a>';
			$context['page_index'] = $context['prev_board_page'] . $context['page_index'] . $context['next_board_page'];
		}
	}
}

function smart_pagination_display()
{
	global $context, $scripturl;
	
	if (!empty($context['current_topic']) && !empty($context['page_index'])) {
		if (strpos($context['page_index'], "...") || strpos($context['page_index'], "2")) {
			$context['prev_topic_page'] = !empty($context['start']) ? '<a class="navPages" href="' . $scripturl . '?topic=' . $context['current_topic'] . '.' . ($context['start'] - $context['messages_per_page']) . '">&laquo;</a> ' : '';
			$context['next_topic_page'] = $context['total_visible_posts'] <= ($context['start'] + $context['messages_per_page']) ? '' : ' <a class="navPages" href="' . $scripturl . '?topic=' . $context['current_topic'] . '.' . ($context['start'] + $context['messages_per_page']) . '">&raquo;</a>';
			$context['page_index'] = $context['prev_topic_page'] . $context['page_index'] . $context['next_topic_page'];
		}
	}
}

function smart_pagination_buffer(&$buffer)
{
	global $context, $txt;
	
	if (isset($_REQUEST['xml'])) return $buffer;
	
	$search = '~\[<strong>(\d+)<\/strong>\]~siU';
	$replace = '<strong>$1</strong>';
	$new_buffer = preg_replace($search, $replace, $buffer);
	
	if ((!empty($context['current_topic']) || !empty($context['current_board'])) && !empty($context['page_index'])) {
		if (!strpos($context['page_index'], "...") && !strpos($context['page_index'], "2")) {
			$search = $txt['pages'] . ': ' . '<strong>1</strong>';
			$replace = '<!-- Smart Pagination -->';
			$new_buffer = str_replace($search, $replace, $new_buffer);
		}
	}

	return $new_buffer;
}

?>