<?php
/**********************************************************************************
* BadBehavior_Admin.template.php - PHP template for BadBehavior mod
* Version 1.4.5 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

function template_badbehavior_settings()
{
//  Not used
}

function template_badbehavior_reports()
{
	global $context, $txt, $settings, $scripturl;

	$context['start'] = (int) $_REQUEST['start'];

	// Distribute query search
	$type ='';
	if ($context['sub_action'] == 'report_permit')
		$type = $txt['badbehavior_type_perm'];
	if ($context['sub_action'] == 'report_denied')
		$type = $txt['badbehavior_type_den'];
	if ($context['sub_action'] == 'report_all')
		$type = $txt['badbehavior_type_all'];

	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/badbehavior.css" />
	<div id="admincenter"><div class="title_bar">
	<h4 class="titlebg"><span class="ie6_header floatleft">'.$txt['badbehavior_log_title'].'</span></h4></div>';

	echo '
	<div class="pagesection">', $txt['pages'], $txt['badbehavior_colin'], $context['page_index'], '</div>';

	//display the table title
	echo '
	<table width="100%" class="table_grid" style="table-layout:fixed;word-wrap:break-word;width:650px;">
	<thead>
	<tr class="catbg">
		<th class="first_th smalltext" style="width:60px">'.$txt['badbehavior_log_id'].'</th>
		<th class="smalltext" style="width:120px">'.$txt['badbehavior_log_ip'].'</th>
		<th class="smalltext" style="width:100px"><a href="', $scripturl, '?action=admin;area=badbehavior;sa='.$context['sub_action'].';sort_by=date', $context['order_by'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['badbehavior_log_date'], $context['sort_by'] == 'date' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['order_by'] . '.gif" alt="" />' : '', '</a></th>
		<th class="smalltext" style="width:280px"><a href="', $scripturl, '?action=admin;area=badbehavior;sa='.$context['sub_action'].';sort_by=request_uri', $context['order_by'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['badbehavior_log_uri'], $context['sort_by'] == 'request_uri' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['order_by'] . '.gif" alt="" />' : '', '</a></th>
		<th class="last_th smalltext" style="width:90px"><a href="', $scripturl, '?action=admin;area=badbehavior;sa='.$context['sub_action'].';sort_by=key', $context['order_by'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['badbehavior_log_key'], $context['sort_by'] == 'key' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['order_by'] . '.gif" alt="" />' : '', '</a></th>
	</tr></thead><tbody>';

	$alternate_rows = false;
	$row_color = 'windowbg2';

	if (empty($context['badbehavior_log']))
		echo '
			<tr class="windowbg2"><td colspan="10">'.$txt['badbehavior_empty'].'</td></tr>';
	else {
		foreach ($context['badbehavior_log'] as $display) {

      if ($alternate_rows == true) { //Alternating colors for each row
        $alternate_rows = false;
        $row_color = 'windowbg2';
      } else {
        $alternate_rows = true;
        $row_color = 'windowbg'; }

	//display the table
	echo '
	<tr class="'.$row_color.' '.$row_color.'_hover smalltext" onclick="window.location.href=\'', $scripturl, '?action=admin;area=badbehavior;sa='.$context['sub_action'].';bbid=',$display['bbid'],'\'">
		<td>'.$display['bbid'].'</td>
		<td><a href="' . $scripturl . '?action=trackip;searchip=' . $display['ip'] . '">'.$display['ip'].'</a></td>
		<td>'.$display['date'].'</td>
		<td>'.$display['request_uri'].'</td>
		<td><a href="', $scripturl, '?action=admin;area=badbehavior;sa='.$context['sub_action'].'; bbid=',$display['bbid'], '">'.(($display['key'] == '00000000') ? $txt['badbehavior_permitted'] : $txt['badbehavior_denied']).'<br />#'.$display['key'].'</a></td>
	</tr>';
		}
	}

  echo '
	</tbody></table>'.$txt['badbehavior_rec_disp'].'&nbsp;&nbsp;<b>'.$context['bb2_where'].'</b>'.$txt['badbehavior_to'].'<b>'.$context['bb2_page_num'].'</b>&nbsp;&nbsp;'.$txt['badbehavior_from'].'<b>'.$context['bb2_per_page'].'</b>'.$txt['badbehavior_rec_tot'].$type.'</div><br class="clear" />';
}

function template_badbehavior_event() {
	global $txt, $context, $scripturl;
	
	echo '
	<div id="admincenter"><div class="cat_bar">
	<h3 class="catbg"><span class="ie6_header floatleft">'.$txt['badbehavior_event_title'].'</span></h3></div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">';

	echo '
	<table width="100%"  class="table_grid" style="table-layout:fixed;word-wrap:break-word;width:650px;">
	<thead></thead><tbody>';

foreach ($context['badbehavior_log'] as $display) {
	echo '
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_id'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['bbid'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_ip'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px"><a href="' . $scripturl . '?action=trackip;searchip=' . $display['ip'] . '">'.(empty($display['ip']) ? '' : $display['ip'] . ((function_exists('is_callable')) ? ((is_callable('gethostbyaddr')) ? ($display['ip'] == (gethostbyaddr($display['ip'])) ? "" : "<br />" . gethostbyaddr($display['ip'])) : "") : "")).'</a></td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_date'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['date'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_method'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['request_method'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_uri'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['request_uri'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_protocol'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['server_protocol'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_headers'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['http_headers'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_agent'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['user_agent'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_enity'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['request_entity'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_log_key'].$txt['badbehavior_colin'].'</strong></td><td style="width:500px">' . $display['key'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.(($display['key'] == '00000000') ? $txt['badbehavior_permitted'] : $txt['badbehavior_denied']).$txt['badbehavior_reason'].'</strong></td><td style="width:500px">' . $context['log'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_explain'].'</strong></td><td style="width:500px">' .$context['explanation'] . '</td></tr>
		<tr><td style="width:150px"><strong>'.$txt['badbehavior_error'].'</strong></td><td style="width:500px">' . $context['response'] . '</td></tr>';
}
	echo '
		</tbody></table></div><span class="botslice"><span></span></span></div></div><br class="clear" />';

}


function template_badbehavior_about()
{
	global $txt, $sourcedir;

	if (!defined('BB2_VERSION')) {
		if (!defined('BB2_CWD')) define('BB2_CWD', dirname(__FILE__));
		require_once($sourcedir . '/bad-behavior/bad-behavior/core.inc.php');
	}
	
	echo '
	<div id="admincenter"><div class="cat_bar">
	<h3 class="catbg"><span class="ie6_header floatleft">'.$txt['badbehavior_version_c'].'</span></h3></div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">
		<strong>'.$txt['badbehavior_core'].': ' . BB2_VERSION . '<br />
		'.$txt['badbehavior_author'].'<hr>
		'.$txt['badbehavior_cversion'].': ' . $txt['badbehavior_cversion_mod'] . '<br />
		'.$txt['badbehavior_mauthor'].'
		</strong></div><span class="botslice"><span></span></span></div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">
		<p>'.$txt['badbehavior_oview'].'</p><hr>
		<p>'.$txt['badbehavior_minfo'].'</p><hr>
		<p>'.$txt['badbehavior_coredesc'].'</p>
		</div><span class="botslice"><span></span></span></div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">
		<strong><p>'.$txt['badbehavior_csupport'].'</p></strong>
		<hr><strong><p>'.$txt['badbehavior_msupport'].'</p></strong>
		</div><span class="botslice"><span></span></span></div>
		</div><br class="clear" />';
}

?>